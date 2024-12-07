<?php

namespace App\Controllers;

use App\Services\NotificationService;

class NotificationController extends BaseController
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Fetch all notifications for the logged-in user.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function getNotifications()
    {
        $userId = session()->get('id'); // Get logged-in user ID
        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
		

        try {
            $taskNotifications = $this->notificationService->getNotifications($userId,'task',true);
            $chatNotifications = $this->notificationService->getNotifications($userId,'chat',true);
			


            // Group notifications by type for the frontend
            $groupedNotifications['taskNotifications'] = $taskNotifications;
            $groupedNotifications['chatNotifications'] = $chatNotifications;


			return $this->response->setJSON([
                'status' => 'success',
                'data' => $groupedNotifications
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
	public function markAsDelivered()
	{
		$rawInput = $this->request->getBody();
		$decodedInput = json_decode($rawInput, true);
		$notificationIds = $decodedInput['ids'] ?? [];

		if (empty($notificationIds)) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'Notification IDs are required',
			])->setStatusCode(400);
		}

		$notificationService = new \App\Services\NotificationService();
		$updated = $notificationService->markAsDelivered($notificationIds);

		if ($updated) {
			return $this->response->setJSON([
				'status' => 'success',
				'message' => 'Notifications marked as delivered',
			]);
		}

		return $this->response->setJSON([
			'status' => 'error',
			'message' => 'Failed to mark notifications as delivered',
		])->setStatusCode(500);
	}
    /**
     * Mark notifications as read.
     *
     * @return \CodeIgniter\HTTP\Response
     */
	public function markAsRead()
	{
		$rawInput = $this->request->getBody();
		$decodedInput = json_decode($rawInput, true);
		$notificationIds = $decodedInput['ids'] ?? [];

		if (empty($notificationIds)) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Notification IDs are required']);
		}

		$notificationService = new \App\Services\NotificationService();

		$updated = $notificationService->markNotificationsAsRead($notificationIds);

		if ($updated) {
			return $this->response->setJSON(['status' => 'success', 'message' => 'Notifications marked as read']);
		}

		return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to mark notifications as read']);
	}

}
