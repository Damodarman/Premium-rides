<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Models\DriverModel;

class Tasks extends BaseController
{
    protected $taskService;
    protected $driverModel;

    public function __construct()
    {
        $this->taskService = new TaskService();
        $this->driverModel = new DriverModel();
    }

    /**
     * Display a list of tasks for the logged-in user.
     */
    public function index()
    {
        $userId = session()->get('id'); // Assuming user ID is stored in the session
        $tasks = $this->taskService->getTasksForUser($userId);
		
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$page = 'Zadaci';

        return view('adminDashboard/header', ['tasks' => $tasks, 'page' => $page, 'fleet' => $fleet, 'role'=> $role])
			. view('adminDashboard/navBar')
			. view('tasks/sidebar')
			. view('tasks/index')
			. view('footer');
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$page = 'Kreiraj zadatak';
		$priorities = $this->taskService->getAllPriorities();
        $users = $this->taskService->getAllUsers(); // Assuming this fetches all users

        return view('adminDashboard/header', ['priorities' => $priorities, 'users' => $users, 'page' => $page, 'fleet' => $fleet, 'role'=> $role])
			. view('adminDashboard/navBar')
			. view('tasks/sidebar')
			. view('tasks/create')
			. view('footer');
		
    }

    /**
     * Handle the submission of the task creation form.
     */
public function store()
{
    // Define validation rules
    $rules = [
        'title' => 'required|max_length[255]',
        'description' => 'required',
        'priority_id' => 'required|integer',
        'assigned_users' => 'required', // At least one user must be assigned
    ];

    // Validate the input
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $data = $this->request->getPost();
    $data['created_by'] = session()->get('id'); // Set the creator ID

    $priority_id = $data['priority_id'];
    $hours = $this->taskService->getPriorityHoursById($priority_id);
    $due_time = $this->taskService->calculateDueTime($hours);
    $data['due_time'] = $due_time;

    try {
        $taskId = $this->taskService->createTask($data);
        if ($taskId) {
            return redirect()->to('/tasks')->with('success', 'Novi zadatak je uspješno kreiran');
        }
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', $e->getMessage());
    }

    return redirect()->back()->withInput()->with('error', 'Failed to create task.');
}
    /**
     * Show the form for editing an existing task.
     */
    public function edit($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Task not found.');
        }

        $priorities = $this->taskService->getAllPriorities();
        $users = $this->taskService->getAllUsers();
        $assignedUsers = $this->taskService->getAssignedUsers($taskId);

        return view('tasks/edit', [
            'task' => $task,
            'priorities' => $priorities,
            'users' => $users,
            'assignedUsers' => array_column($assignedUsers, 'user_id'),
        ]);
    }

    /**
     * Handle the submission of the task update form.
     */
    public function update($taskId)
    {
        $data = $this->request->getPost();
        $data['updated_by'] = session()->get('id'); // Optionally track who updated the task

        try {
            $this->taskService->updateTask($taskId, $data);

            if (!empty($data['assigned_users'])) {
                $this->taskService->assignUsersToTask($taskId, $data['assigned_users']);
            }

            return redirect()->to('/tasks')->with('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
    public function requestHelp($taskId)
    {
        $data = $this->request->getPost();
        $data['updated_by'] = session()->get('id'); // Optionally track who updated the task

        try {
            $this->taskService->requestHelp($taskId, $data);
            return redirect()->to('/tasks')->with('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a task as completed.
     */
    public function markAsCompleted($taskId)
    {
        try {
            $this->taskService->markTaskAsCompleted($taskId);
            return redirect()->to('/tasks')->with('success', 'Task marked as completed!');
        } catch (\Exception $e) {
            return redirect()->to('/tasks')->with('error', $e->getMessage());
        }
    }
    public function start($taskId)
    {
		$userId = session()->get('id');
        try {
            $this->taskService->markTaskAsInProgress($taskId, $userId);
            return redirect()->to('/tasks/show/' .$taskId)->with('success', 'Uspješno započet zadatak!');
        } catch (\Exception $e) {
            return redirect()->to('/tasks')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the status of a task.
     */
    public function updateStatus($taskId)
    {
        $status = $this->request->getPost('status');

        try {
            $this->taskService->updateTaskStatus($taskId, $status);
            return redirect()->to('/tasks')->with('success', 'Task status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->to('/tasks')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the details and timeline of a task.
     */
    public function show($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Task not found.');
        }
		$driver = null;
		$obracun = null;
		if($task['task_type'] === 'vozac_related'){
			$driver = $this->driverModel->getDriverById($task['related_entity_id']);
		}
        $history = $this->taskService->getTaskHistory($taskId);
		$allUsers = $this->taskService->getAllUsers();
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$page = 'Detalji zadatka';

         return view('adminDashboard/header', ['driver' => $driver, 'task' => $task, 'history' => $history, 'page' => $page, 'fleet' => $fleet, 'role'=> $role, 'allUsers'=> $allUsers])
			. view('adminDashboard/navBar')
			. view('tasks/sidebar')
			. view('tasks/show')
			. view('footer');
		
    }

    public function getChatMessages($taskId)
    {
		$lastMessageId = $this->request->getGet('lastMessageId'); 
        $messages = $this->taskService->getTaskChatMessages($taskId, $lastMessageId);
        return $this->response->setJSON($messages);
    }

    /**
     * Handle a new chat message submission.
     */
	public function sendMessage()
	{
		$data = $this->request->getPost();

		// Validate input
		if (empty($data['message']) || empty($data['task_id']) || empty($data['sender_id'])) {
			return $this->response->setJSON(['error' => 'Invalid input'])->setStatusCode(400);
		}

		$messageData = [
			'task_id' => $data['task_id'],
			'sender_id' => $data['sender_id'],
			'sender_name' => $data['sender_name'],
			'content' => $data['message'],
		];

		try {
			$this->taskService->addTaskChatMessage($messageData);
			return $this->response->setJSON(['success' => 'Message sent']);
		} catch (\Exception $e) {
			return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
		}
	}
	
	public function createObracunTask($obracunId)
	{
		$data = $this->request->getPost();
		$data['created_by'] = session()->get('id');
		$data['task_type'] = 'obracun_related';
		$data['related_entity_id'] = $obracunId;

		try {
			$taskId = $this->taskService->createTask($data);
			if ($taskId) {
				return redirect()->to('/tasks')->with('success', 'Task created for report successfully!');
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}

		return redirect()->back()->with('error', 'Failed to create task.');
	}

	public function createVozacTask($vozacId)
	{
		$data = $this->request->getPost();
		$data['created_by'] = session()->get('id');
		$data['task_type'] = 'vozac_related';
		$data['related_entity_id'] = $vozacId;

		try {
			$taskId = $this->taskService->createTask($data);
			if ($taskId) {
				return redirect()->to('/tasks')->with('success', 'Task created for driver successfully!');
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}

		return redirect()->back()->with('error', 'Failed to create task.');
	}
	
}
