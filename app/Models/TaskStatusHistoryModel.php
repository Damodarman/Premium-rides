<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskStatusHistoryModel extends Model
{
    protected $table = 'task_status_history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'changed_by', 'status', 'details','comment', 'created_at'];

    /**
     * Fetch the history of a specific task.
     */
    public function getHistoryByTask($taskId)
    {
        return $this->select('task_status_history.*, users.name as changed_by_name') // Include user name
                    ->join('users', 'task_status_history.changed_by = users.id', 'left') // Join with users table
                    ->where('task_status_history.task_id', $taskId) // Filter by task ID
                    ->orderBy('task_status_history.created_at', 'ASC') // Sort by time
                    ->findAll(); // Return all results
	}
}
