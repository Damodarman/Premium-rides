<?php

namespace App\Services;

use App\Models\NotificationModel;

class NotificationService
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Create a single notification for a user.
     *
     * @param int $userId - User to notify.
     * @param string $type - Notification type ('task', 'chat', 'system', etc.).
     * @param string $message - Notification message.
     * @param int|null $referenceId - Related entity ID (e.g., task ID, chat ID).
     * @param string|null $url - URL for the notification.
     * @return int|bool - Insert ID on success, false on failure.
     */
    public function createNotification(int $userId, string $type, string $message, ?int $referenceId = null, ?string $url = null)
    {
        $notificationData = [
            'user_id' => $userId,
            'type' => $type,
            'reference_id' => $referenceId,
            'message' => $message,
            'url' => $url,
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->notificationModel->createNotification($notificationData);
    }

    /**
     * Create notifications for multiple users.
     *
     * @param array $userIds - List of user IDs to notify.
     * @param string $type - Notification type.
     * @param string $message - Notification message.
     * @param int|null $referenceId - Related entity ID.
     * @param string|null $url - URL for the notification.
     * @return bool - True on success, false on failure.
     */
    public function createNotifications(array $userIds, string $type, string $message, $referenceId = null, ?string $url = null): bool
    {
		var_dump($userIds, $type, $message, $referenceId, $url);
        foreach ($userIds as $userId) {
            $result = $this->createNotification($userId, $type, $message, $referenceId, $url);
            if (!$result) {
                return false;
            }
        }
        return true;
    }
	public function markAsDelivered(array $notificationIds): bool
	{
		return $this->notificationModel->markAsDelivered($notificationIds);
	}

    /**
     * Fetch notifications by type for a user.
     *
     * @param int $userId - User ID.
     * @param array|string $types - Type(s) of notifications to fetch.
     * @param bool $unreadOnly - Whether to fetch only unread notifications.
     * @return array - List of notifications.
     */
    public function getNotifications(int $userId, $type, bool $unreadOnly = true): array
    {
        return $this->notificationModel->getNotifications($userId, $type, $unreadOnly);
    }

	public function markNotificationsAsRead(array $ids): bool
	{
		return $this->notificationModel->markNotificationsAsRead($ids);
	}
    /**
     * Mark all notifications as read for a user and optional type.
     *
     * @param int $userId - User ID.
     * @param string|null $type - Optional notification type.
     * @return bool - True on success.
     */
    public function markAllAsRead(int $userId, ?string $type = null): bool
    {
        return $this->notificationModel->markAllAsRead($userId, $type);
    }
	
    public function markAsRead($id)
    {
        return $this->notificationModel->markAsRead($id);
    }

    /**
     * Check if a user has unread notifications.
     *
     * @param int $userId - User ID.
     * @param string|null $type - Optional notification type.
     * @return bool - True if unread notifications exist.
     */
    public function hasUnreadNotifications(int $userId, ?string $type = null): bool
    {
        $notifications = $this->getNotifications($userId, $type ?? '%', true);
        return !empty($notifications);
    }
}
