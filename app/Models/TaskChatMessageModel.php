<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskChatMessageModel extends Model
{
    protected $table = 'task_chat_messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'sender_id', 'sender_name', 'content', 'created_at'];

    /**
     * Get all chat messages for a specific task.
     */
public function getMessagesByTaskId($taskId, $lastMessageId = null)
{

    $this->where('task_id', $taskId);

    if ($lastMessageId) {
        $this->where('id >', $lastMessageId); // Fetch only messages with ID greater than the lastMessageId
    }

    $this->orderBy('created_at', 'ASC');
    return $this->get()->getResultArray();
}
    /**
     * Insert a new chat message.
     */
    public function addMessage(array $data)
    {
        return $this->insert($data);
    }
}
