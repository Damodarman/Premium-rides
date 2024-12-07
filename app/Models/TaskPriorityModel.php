<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskPriorityModel extends Model
{
    protected $table = 'task_priorities';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'due_hours'];

    /**
     * Get all priorities.
     *
     * @return array
     */
    public function getAllPriorities()
    {
        return $this->findAll();
    }

    /**
     * Fetch a single priority by ID.
     *
     * @param int $priorityId
     * @return array
     */
    public function getPriorityById($priorityId)
    {
        return $this->find($priorityId);
    }
}
