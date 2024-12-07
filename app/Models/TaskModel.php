<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'description',
        'created_by',
        'priority_id',
        'status',
        'due_time',
        'created_at',
        'updated_at',
		'task_type',
		'related_entity_id'
    ];

    /**
     * Fetch all tasks created by or assigned to a user.
     *
     * @param int $userId
     * @return array
     */
public function getTasksByUser($userId, $status)
{
    return $this->select('tasks.*, 
                          task_priorities.name as priority_name,
                          task_priorities.bs-class as priority_class,
                          created_by_user.name as created_by_name,
                          GROUP_CONCAT(DISTINCT assigned_user.name SEPARATOR ", ") as assigned_user_names')
                ->join('task_priorities', 'tasks.priority_id = task_priorities.id', 'left')
                ->join('users as created_by_user', 'tasks.created_by = created_by_user.id', 'left')
                ->join('task_assignments', 'tasks.id = task_assignments.task_id', 'left')
                ->join('users as assigned_user', 'task_assignments.user_id = assigned_user.id', 'left')
				->where('status', $status)
                ->groupStart()
                    ->where('tasks.created_by', $userId)
                    ->orWhere('task_assignments.user_id', $userId)
                ->groupEnd()
                ->groupBy('tasks.id')
				->orderBy('tasks.due_time', 'ASC')
                ->findAll();
}

	public function accessRights($taskId, $userId){

		return $this->select('tasks.id')
			->join('task_assignments', 'tasks.id = task_assignments.task_id', 'left')
			->groupStart()
				->where('tasks.created_by', $userId)
				->orWhere('task_assignments.user_id', $userId)
			->groupEnd()
			->where('tasks.id', $taskId)
			->first() !== null;
	
	}
	public function getTaskTitleById($taskId){
		$task = $this->where('id', $taskId)->first();
		return $task['title'];
	}
	public function getCreatedById($taskId){
		$task = $this->where('id', $taskId)->first();
		return $task['created_by'];
	}
	
public function getTaskById($taskId)
{
    return $this->select('tasks.*, 
                          task_priorities.bs-class as priority_class,
                          task_priorities.name as priority_name, 
                          created_by_user.name as created_by_name, 
                          GROUP_CONCAT(assigned_user.name SEPARATOR ", ") as assigned_user_names,
                          GROUP_CONCAT(assigned_user.id SEPARATOR ",") as assigned_user_ids') // Collect assigned user IDs
                ->join('task_priorities', 'tasks.priority_id = task_priorities.id', 'left')
                ->join('users as created_by_user', 'tasks.created_by = created_by_user.id', 'left')
                ->join('task_assignments', 'tasks.id = task_assignments.task_id', 'left')
                ->join('users as assigned_user', 'task_assignments.user_id = assigned_user.id', 'left')
                ->where('tasks.id', $taskId)
                ->groupBy('tasks.id') // Ensures one row per task
                ->first(); // Fetch a single record
}
	
	public function getTasksByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Fetch overdue tasks.
     *
     * @return array
     */
    public function getOverdueTasks()
    {
        return $this->where('status !=', 'completed')
                    ->where('due_time <', date('Y-m-d H:i:s'))
                    ->findAll();
    }
	
	public function getAssignedUserIds(int $taskId): array
	{
		$results = $this->db->table('task_assignments')
			->select('user_id')
			->where('task_id', $taskId)
			->get()
			->getResultArray();

		return array_column($results, 'user_id');
	}

}
