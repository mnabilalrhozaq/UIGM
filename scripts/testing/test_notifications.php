<!DOCTYPE html>
<html>

<head>
    <title>Test Notifications</title>
</head>

<body>
    <h1>Testing Notifications Page</h1>

    <h2>1. Login as Admin Pusat</h2>
    <p>Go to: <a href="http://localhost:8080/auth/login" target="_blank">http://localhost:8080/auth/login</a></p>
    <p>Use credentials: admin_pusat / password123</p>

    <h2>2. Access Notifications Page</h2>
    <p>Go to: <a href="http://localhost:8080/admin-pusat/notifikasi" target="_blank">http://localhost:8080/admin-pusat/notifikasi</a></p>

    <h2>3. Test API Endpoints</h2>
    <p>Mark notification as read: POST to /api/notifications/{id}/read</p>
    <p>Mark all as read: POST to /api/notifications/mark-all-read</p>

    <script>
        // Test API endpoints after login
        function testMarkAsRead(notifId) {
            fetch(`/api/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => console.log('Mark as read result:', data))
                .catch(error => console.error('Error:', error));
        }

        function testMarkAllAsRead() {
            fetch('/api/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => console.log('Mark all as read result:', data))
                .catch(error => console.error('Error:', error));
        }

        console.log('Test functions available: testMarkAsRead(id), testMarkAllAsRead()');
    </script>
</body>

</html>