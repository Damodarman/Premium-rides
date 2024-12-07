    <div class="container footer footer-bottom clearfix mt-15 pt-15">
      <div class="copyright">
        &copy; Copyright <strong><span>Premium Rides</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="https://premium-rides.com/">Premium Rides</a>
      </div>

    
    
    </div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notification-list');
    const notificationCount = document.getElementById('notification-count');

    const chatDropdown = document.getElementById('chatDropdown');
    const chatList = document.getElementById('chat-list');
    const chatCount = document.getElementById('chat-count');

    const soundNotification = new Audio('https://notificationsounds.com/storage/sounds/file-sounds-1085-definite.mp3');

    // Utility: Fetch with error handling
    const fetchJson = async (url, options = {}) => {
        try {
            const response = await fetch(url, options);
            if (response.ok) {
                return await response.json();
            } else {
                console.error(`Fetch failed: ${response.statusText}`);
            }
        } catch (error) {
            console.error(`Error during fetch: ${error}`);
        }
        return null;
    };

    // Check session status
    const checkSession = async () => {
        const result = await fetchJson('<?= site_url('api/checkSession') ?>');
        return result?.loggedIn ?? false;
    };

    // Request browser notification permission
    const requestNotificationPermission = () => {
        if ('Notification' in window) {
            Notification.requestPermission().then(permission => {
                if (permission !== 'granted') {
                    console.warn('Browser notifications are not enabled');
                }
            });
        }
    };

    // Show browser notification
    const showBrowserNotification = (title, options) => {
        if (!document.hasFocus() && 'Notification' in window && Notification.permission === 'granted') {
            const notification = new Notification(title, options);
            notification.onclick = function () {
                window.focus(); // Focus the tab when notification is clicked
            };
        }
    };

    // Fetch notifications
    const fetchNotifications = async () => {
        const sessionActive = await checkSession();
        if (!sessionActive) {
            console.warn('Session destroyed. Stopping notifications script.');
            return;
        }

        const data = await fetchJson('<?= site_url('notifications/getNotifications') ?>');
        if (data?.status === 'success') {
            processNotifications(
                data.data.taskNotifications, 
                notificationList, 
                notificationDropdown, 
                notificationCount, 
                'Nema novih obavijesti'
            );
            processNotifications(
                data.data.chatNotifications, 
                chatList, 
                chatDropdown, 
                chatCount, 
                'Nema novih poruka'
            );
        }
    };

    // Process notifications (handle grouping, rendering, and alerts)
    const processNotifications = (notifications, list, dropdownIcon, badge, emptyMessage) => {
        list.innerHTML = ''; // Clear existing notifications

        if (notifications.length > 0) {
            badge.textContent = notifications.length;
            badge.classList.remove('d-none'); // Show badge

            // Group notifications by type and reference_id
            const groupedNotifications = notifications.reduce((acc, notification) => {
                const key = `${notification.type}_${notification.reference_id}`;
                if (!acc[key]) {
                    acc[key] = { ...notification, ids: [] };
                }
                acc[key].ids.push(notification.id);
                return acc;
            }, {});

            // Render grouped notifications
            Object.values(groupedNotifications).forEach(notification => {
                const listItem = document.createElement('li');
                listItem.classList.add('dropdown-item');
                listItem.innerHTML = `
                    <a href="javascript:void(0);" 
                       class="text-decoration-none notification-link" 
                       data-ids="${notification.ids.join(',')}" 
                       data-url="<?= site_url() ?>${notification.url}">
                        ${notification.ids.length > 1 
                            ? `${notification.ids.length} novih poruka u zadatku`
                            : notification.message}
                        <small class="text-muted d-block">${formatDate(notification.created_at)}</small>
                    </a>`;
                list.appendChild(listItem);

                // Play sound and show browser notification for new notifications
                if (!document.hasFocus() || notification.ids.length > 0) {
                    playSoundNotification();
                    showBrowserNotification(
                        notification.ids.length > 1 
                            ? 'Grouped Notification'
                            : 'New Notification', 
                        {
                            body: notification.ids.length > 1 
                                ? `${notification.ids.length} novih poruka u zadatku`
                                : notification.message,
                            icon: 'https://via.placeholder.com/100', // Replace with your logo
                        }
                    );
                }
            });
        } else {
            badge.classList.add('d-none'); // Hide badge
            const emptyItem = document.createElement('li');
            emptyItem.classList.add('dropdown-item', 'text-muted');
            emptyItem.textContent = emptyMessage;
            list.appendChild(emptyItem);
        }
    };

    // Mark notifications as read (or group as read)
    const markNotificationsAsRead = async (notificationIds, notificationUrl) => {
        const result = await fetchJson('<?= site_url('notifications/markAsRead') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: notificationIds }),
        });

        if (result?.status === 'success') {
            console.log('Notifications marked as read');
            window.location.href = notificationUrl; // Redirect after marking as read
        } else {
            console.error('Error marking notifications as read');
        }
    };

    // Attach click listener for notifications
    notificationList.addEventListener('click', (event) => {
        const notificationLink = event.target.closest('.notification-link');
        if (notificationLink) {
            const notificationIds = notificationLink.getAttribute('data-ids').split(',');
            const notificationUrl = notificationLink.getAttribute('data-url');

            if (notificationIds.length > 0 && notificationUrl) {
                markNotificationsAsRead(notificationIds, notificationUrl);
            }
        }
    });

    // Attach click listener for chat notifications
    chatList.addEventListener('click', (event) => {
        const notificationLink = event.target.closest('.notification-link');
        if (notificationLink) {
            const notificationIds = notificationLink.getAttribute('data-ids').split(',');
            const notificationUrl = notificationLink.getAttribute('data-url');

            if (notificationIds.length > 0 && notificationUrl) {
                markNotificationsAsRead(notificationIds, notificationUrl);
            }
        }
    });

    // Format date
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-based
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}.${month}.${year} ${hours}:${minutes}`;
    };

    // Play notification sound
    const playSoundNotification = () => {
        soundNotification.play().catch(error => console.error('Error playing sound:', error));
    };

    // Request permission for browser notifications on load
    requestNotificationPermission();

    // Periodically fetch notifications
    setInterval(fetchNotifications, 30000); // Adjust interval as needed

    // Initial fetch
    fetchNotifications();
});
</script>







</body>


</html>