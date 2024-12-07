<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\DriverModel;
use App\Models\TaskModel;
use App\Models\TaskAssignmentModel;
use App\Models\TaskPriorityModel;
use App\Models\TaskStatusHistoryModel;
use App\Models\TaskChatMessageModel;
use App\Services\NotificationService;
use App\Libraries\UltramsgLib;

class TaskService
{
    protected $userModel;
    protected $driverModel;
    protected $taskModel;
    protected $taskAssignmentModel;
    protected $taskPriorityModel;
    protected $taskStatusHistoryModel;
	protected $chatModel;
	protected $notificationService;
	protected $ultramsgLib;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->driverModel = new DriverModel();
        $this->taskModel = new TaskModel();
        $this->taskAssignmentModel = new TaskAssignmentModel();
        $this->taskPriorityModel = new TaskPriorityModel();
        $this->taskStatusHistoryModel = new TaskStatusHistoryModel();
        $this->chatModel = new TaskChatMessageModel();
		$this->notificationService = new NotificationService();
		$this->ultramsgLib = new UltramsgLib();
	}

    /**
     * Create a new task and assign users.
     */
	public function createTask(array $data)
	{
		// Ensure task_type is valid
		$validTypes = ['general', 'obracun_related', 'vozac_related'];
		if (!in_array($data['task_type'], $validTypes)) {
			throw new \Exception('Invalid task type.');
		}
			$title = $data['title'];

		// Prepare task data
		$taskData = [
			'title' => $data['title'],
			'description' => $data['description'],
			'created_by' => $data['created_by'],
			'priority_id' => $data['priority_id'],
			'status' => 'pending',
			'due_time' => $data['due_time'],
			'task_type' => $data['task_type'],
			'related_entity_id' => $data['related_entity_id'] ?? null,
		];

		// Insert task
		$taskId = $this->taskModel->insert($taskData);

		if (!$taskId) {
			return false;
		}
		$this->logTaskHistory($taskId, $data['created_by'], 'created', 'Zadatak je kreiran.');
		$assignedUsers = $data['assigned_users'];
		// Assign users if provided
		if (!empty($assignedUsers)) {
			$url = 'tasks/show/' . $taskId;
			$notifyMsg = "Dodijeljen ti je novi zadatak: $title.";
			$details = null;
			$comment = 'Prvo zaduženje';
			$this->assignUsersToTask($taskId, $assignedUsers, $details, $comment);
		}
		


		return $taskId;
	}
	
	public function sendTaskInfoByWhatsapp($taskInfoData){
//		echo '<pre>';
//		var_dump($taskInfoData);
//		die();
		$users = $taskInfoData['users'];
		$msg = $taskInfoData['msg'];
		$to = null ;
		foreach($users as $user){
			//$userId = int $user ;
			$data['to'] = $this->userModel->getUserPhoneById($user);
			$data['msg'] = $msg;
			$this->ultramsgLib->sendMsg($data);
		}
	}
	
	public function getTaskById($taskId){
		$userId = session()->get('id');
		$accessRights = $this->taskModel->accessRights($taskId, $userId);
		if(!$accessRights){
			return false;
		}
		
		$task = $this->taskModel->getTaskById($taskId);
		return $task;
	}

	public function getTasksByType($taskType, $relatedEntityId = null)
	{
		$query = $this->taskModel->where('task_type', $taskType);

		if ($relatedEntityId !== null) {
			$query->where('related_entity_id', $relatedEntityId);
		}

		return $query->findAll();
	}

	public function getAllUsers(){
        $fleet = session()->get('fleet');
		$users = $this->userModel->getAllFleetUsers($fleet);
		return $users;

	}

    public function getTaskChatMessages($taskId, $lastMessageId = null)
    {
        return $this->chatModel->getMessagesByTaskId($taskId, $lastMessageId);
    }

    /**
     * Add a new chat message.
     */
	public function addTaskChatMessage(array $data)
	{
		// Save the chat message
		$messageId = $this->chatModel->addMessage($data);

		$taskId = (int)$data['task_id'];
		
		// Fetch assigned users excluding the sender
		$assignedUsers = $this->taskModel->getAssignedUserIds($data['task_id']);
		$taskCreator = $this->taskModel->getCreatedById($data['task_id']);
		$senderId = $data['sender_id'];
		$recipients = array_merge($assignedUsers, [$taskCreator]);

		// Remove duplicate IDs
		$recipients = array_unique($recipients);

		// Exclude the sender ID
		$recipients = array_filter($recipients, function ($userId) use ($senderId) {
			return $userId !== $senderId;
		});

		// Reindex the array (optional, to ensure it's zero-indexed)
		$recipients = array_values($recipients);

		$title = $this->taskModel->getTaskTitleById($data['task_id']);
		$senderName = $this->userModel->getUserNameById($data['sender_id']);
		// Create notifications for recipients
		$message = "$senderName je poslao novu poruku u zadatak $title.";
		$url = "tasks/show/{$data['task_id']}";

		$notification = $this->notificationService->createNotifications($recipients, 'chat', $message, $taskId, $url);
		return $messageId;
	}
    /**
     * Assign multiple users to a task.
     */
    public function assignUsersToTask($taskId, array $userIds, $details = null, $comment = null)
    {

		$task = $this->getTaskById($taskId);
		$title = $task['title'];
        $users = [];
        $assignments = [];
        foreach ($userIds as $userId) {
			$users[] = $this->userModel->getUserNameById($userId);
            $assignments[] = ['task_id' => $taskId, 'user_id' => $userId];
        }
		$userNames = implode(', ', $users);
        $this->taskAssignmentModel->insertBatch($assignments);
		if(!$details){
			$details = 'Korisnici: ' .$userNames .' su dodijeljeni ovom zadatku.';
		}
		if(!$comment){
			$comment = null;
		}
        $this->logTaskHistory($taskId, session()->get('id'), 'assigned', $details, $comment);
		
		if (!empty($users)) {
			$url = 'tasks/show/' . $taskId;
			$priority = $task['priority_name'];
			$taskUrl = site_url($url);
			$notifyMsg = "Dodijeljen ti je novi zadatak: $title.";
			$taskInfoMsg = 'Prioritet: '.$priority .'
			' .$notifyMsg .'
			Zadatak možeš otvoriti i pregledati klikom na sljedeći link.
			' .$taskUrl;
		 	$this->notificationService->createNotifications($userIds, 'task', $notifyMsg , $taskId, $url);
			$taskInfoData = array(
				'users' => $userIds,
				'msg' => $taskInfoMsg
			
			);
			$this->sendTaskInfoByWhatsapp($taskInfoData);
		}
		
		
    }

    /**
     * Update a task and log changes.
     */
    public function updateTask(int $taskId, array $data)
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            throw new \Exception('Task not found.');
        }

        $updated = $this->taskModel->update($taskId, $data);
        if ($updated) {
            $this->logTaskHistory($taskId, session()->get('id'), 'updated', 'Detalji zadatka su promjenjeni.');
        }

        return $updated;
    }
	
	public function requestHelp(int $taskId, array $data){
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            throw new \Exception('Task not found.');
        }
		$updatedById = $data['updated_by'];
		$userUpdated = $this->userModel->getUserNameById($updatedById);
		$newUsers = $data['assigned_users'];
		$userIds = $data['assigned_users'];
		$comment = $data['comment'];
        $users = [];
        $assignments = [];
        foreach ($newUsers as $userId) {
			$users[] = $this->userModel->getUserNameById($userId);
            $assignments[] = ['task_id' => $taskId, 'user_id' => $userId];
        }
		$userNames = implode(', ', $users);
		$details = "Korisnik $userUpdated je dodao Korisnike: $userNames ovom zadatku.";
		$this->assignUsersToTask($taskId, $userIds, $details, $comment);
//        $this->taskAssignmentModel->insertBatch($assignments);
		
       // $this->logTaskHistory($taskId, $updatedById, 'assigned', $details, $comment);
	}

    /**
     * Mark a task as completed and log the change.
     */
	public function markTaskAsCompleted(int $taskId)
	{
		$task = $this->taskModel->getTaskById($taskId);
		if (!$task) {
			throw new \Exception('Task not found.');
		}

		$title = $task['title'];
		$created_by = $task['created_by']; // Creator of the task
		$currentUser = session()->get('id'); // User who completed the task
		$assigned_user_ids = $task['assigned_user_ids']; // Comma-separated assigned user IDs
		$currentUserName = $this->userModel->getUserNameById($currentUser);

		// Update the task status
		$updated = $this->taskModel->update($taskId, ['status' => 'completed']);
		if ($updated) {
			$this->logTaskHistory($taskId, $currentUser, 'completed', 'Zadatak je izvršen.');

			// Create notification for all users except the one who completed the task
			if (!empty($assigned_user_ids)) {
				// Convert the string of IDs into an array
				$assignedUsers = array_map('trim', explode(',', $assigned_user_ids));

				// Include the creator if not already in the assigned users
				if (!in_array($created_by, $assignedUsers)) {
					$assignedUsers[] = $created_by;
				}

				// Exclude the current user (the one who marked the task as completed)
				$users = array_filter($assignedUsers, function ($userId) use ($currentUser) {
					return $userId != $currentUser;
				});

				if (!empty($users)) {
					$url = 'tasks/show/' . $taskId;
					$notifyMsg = " $currentUserName je završio zadatak $title.";
					$taskUrl = site_url($url);
					$taskInfoMsg = $notifyMsg .'
					' .$taskUrl;
					$this->notificationService->createNotifications($users, 'task', $notifyMsg, $taskId, $url);
					$taskInfoData = array(
						'users' => $users,
						'msg' => $taskInfoMsg

					);
					$this->sendTaskInfoByWhatsapp($taskInfoData);
				}
			}
		}

		return $updated;
	}

    public function markTaskAsInProgress(int $taskId, int $userId)
    {
        $task = $this->taskModel->getTaskById($taskId);
        if (!$task) {
            throw new \Exception('Task not found.');
        }
		$userName = $this->userModel->getUserNameById($userId);
		$assigned_user_ids = $task['assigned_user_ids']; // Comma-separated assigned user IDs
		$created_by = $task['created_by']; // Comma-separated assigned user IDs

		$curretUser =  session()->get('id');
        $updated = $this->taskModel->update($taskId, ['status' => 'in_progress']);
        if ($updated) {
            $this->logTaskHistory($taskId, session()->get('id'), 'in_progress', 'Zadatak je započet');
			if (!empty($assigned_user_ids)) {
				// Convert the string of IDs into an array
				$assignedUsers = array_map('trim', explode(',', $assigned_user_ids));

				// Include the creator if not already in the assigned users
				if (!in_array($created_by, $assignedUsers)) {
					$assignedUsers[] = $created_by;
				}

				// Exclude the current user (the one who marked the task as completed)
				$users = array_filter($assignedUsers, function ($userId) use ($currentUser) {
					return $userId != $currentUser;
				});

				if (!empty($users)) {
					$url = 'tasks/show/' . $taskId;
					$notifyMsg = "Korisnik $currentUserName je započeo zadatak $title.";
					$taskUrl = site_url($url);
					$this->notificationService->createNotifications($users, 'task', $notifyMsg, $taskId, $url);
				}
			}			
        }

        return $updated;
    }

    /**
     * Update the status of a task and log the change.
     */
    public function updateTaskStatus(int $taskId, string $status)
    {
        $validStatuses = ['pending', 'in_progress', 'completed', 'approaching_due', 'overdue'];
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status.');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            throw new \Exception('Task not found.');
        }

        $updated = $this->taskModel->update($taskId, ['status' => $status]);
        if ($updated) {
            $this->logTaskHistory($taskId, session()->get('id'), $status, 'Status zadatka je promjenjen u ' . $status . '.');
        }

        return $updated;
    }

    /**
     * Log a change in task status history.
     */
    private function logTaskHistory(int $taskId, int $userId, string $status, string $details = null, string $comment = null)
    {
        $this->taskStatusHistoryModel->insert([
            'task_id' => $taskId,
            'changed_by' => $userId,
            'status' => $status,
            'details' => $details,
			'comment' => $comment
        ]);
    }

    /**
     * Get the timeline of a task.
     */
    public function getTaskHistory(int $taskId)
    {
        return $this->taskStatusHistoryModel->getHistoryByTask($taskId);
    }

    /**
     * Get all tasks for a user.
     */
    public function getTasksForUser($userId)
    {
		// Pending
		$pendingTasks = $this->taskModel->getTasksByUser($userId, 'pending');
		// Open
		$in_progress = $this->taskModel->getTasksByUser($userId, 'in_progress');
		//Completed
		$completed = $this->taskModel->getTasksByUser($userId, 'completed');
		$data = array(
			'pendingTasks' => $pendingTasks,
			'in_progress' => $in_progress,
			'completed' => $completed,
		);
		return $data;
        return $this->taskModel->getTasksByUser($userId);
    }

    /**
     * Get all priorities for tasks.
     */
    public function getAllPriorities()
    {
        return $this->taskPriorityModel->findAll();
    }
	
	public function getPriorityHoursById($priorityId){
        $priority =  $this->taskPriorityModel->getPriorityById($priorityId);
		return $priority['due_hours'];
	}
	
	public function calculateDueTime($hours){
		$currentTime = new \DateTime(); // Get current date and time
		$due_time = $currentTime->modify("+{$hours} hours"); // Add the given hours to the current time

		return $due_time->format('Y-m-d H:i:s'); // Return the formatted due time	
	}
	

}
