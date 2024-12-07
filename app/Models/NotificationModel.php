<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications'; // Table name
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = [
        'user_id', 'type', 'reference_id', 'message', 'url', 'is_read', 'created_at', 'updated_at', 'delivered'
    ];

    /**
     * Fetch notifications by type for a specific user.
     *
     * @param int $userId - The user ID.
     * @param array|string $types - The notification type(s) (e.g., 'task', 'chat').
     * @param bool $unreadOnly - Whether to fetch only unread notifications.
     * @return array - List of notifications.
     */
    public function getNotifications(int $userId, $type, bool $unreadOnly = true)
    {
		
        $builder = $this->where('user_id', $userId);

            $builder->where('type', $type);


        if ($unreadOnly) {
            $builder->where('is_read', false);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
	
	public function markAsDelivered(array $notificationIds): bool
	{
		return $this->whereIn('id', $notificationIds)
			->set(['delivered' => true, 'updated_at' => date('Y-m-d H:i:s')])
			->update();
	}

	public function markNotificationsAsRead(array $ids): bool
	{
		return $this->whereIn('id', $ids)
					->set(['is_read' => true, 'updated_at' => date('Y-m-d H:i:s')])
					->update();
	}
    /**
     * Mark all notifications as read for a specific user.
     *
     * @param int $userId - The user ID.
     * @param string|null $type - Optional notification type to filter.
     * @return bool - True on success.
     */
    public function markAllAsRead(int $userId, ?string $type = null): bool
    {
        $this->where('user_id', $userId);

        if ($type) {
            $this->where('type', $type);
        }

        return $this->set(['is_read' => true, 'updated_at' => date('Y-m-d H:i:s')])->update();
    }
	
    public function markAsRead($id)
    {
        $this->where('id', $id);


        return $this->set(['is_read' => true, 'updated_at' => date('Y-m-d H:i:s')])->update();
    }

    /**
     * Create a new notification.
     *
     * @param array $data - Notification data (user_id, type, message, etc.).
     * @return int|bool - Insert ID on success, false on failure.
     */
    public function createNotification(array $data)
    {
        return $this->insert($data);
    }
}
