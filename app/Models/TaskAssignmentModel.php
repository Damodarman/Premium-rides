<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskAssignmentModel extends Model
{
    protected $table = 'task_assignments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'user_id'];

    /**
     * Get all users assigned to a specific task.
     *
     * @param int $taskId
     * @return array
     */
    public function getUsersByTask($taskId)
    {
        return $this->select('users.id, users.name, users.email')
                    ->join('users', 'task_assignments.user_id = users.id')
                    ->where('task_assignments.task_id', $taskId)
                    ->findAll();
    }

    /**
     * Get all tasks assigned to a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getTasksByUser($userId)
    {
        return $this->select('tasks.id, tasks.title, tasks.description, tasks.status, tasks.due_time')
                    ->join('tasks', 'task_assignments.task_id = tasks.id')
                    ->where('task_assignments.user_id', $userId)
                    ->findAll();
    }

    /**
     * Assign multiple users to a task.
     *
     * @param int $taskId
     * @param array $userIds
     * @return bool
     */
    public function assignUsersToTask($taskId, $userIds)
    {
        // Remove existing assignments
        $this->where('task_id', $taskId)->delete();

        // Prepare batch insert data
        $assignments = [];
        foreach ($userIds as $userId) {
            $assignments[] = ['task_id' => $taskId, 'user_id' => $userId];
        }

        // Insert new assignments
        return $this->insertBatch($assignments);
    }
}
