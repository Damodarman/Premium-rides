<!DOCTYPE html>
<html>
<head>
    <title>Backup Management</title>
</head>
<body>
    <h1>Backup Management</h1>

    <!-- Manual Backup Button -->
    <button onclick="triggerManualBackup()">Create Manual Backup</button>

    <script>
        function triggerManualBackup() {
            if (confirm("Do you want to create a manual backup?")) {
                window.location.href = '/backups/manual';
            }
        }
    </script>

    <hr>

    <table border="1">
        <thead>
            <tr>
                <th>Backup Name</th>
                <th>Size</th>
                <th>Created At</th>
                <th>Download</th>
                <th>Restore</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><?= $backup['name'] ?></td>
                    <td><?= round($backup['size'] / 1024 / 1024, 2) ?> MB</td>
                    <td><?= date('Y-m-d H:i:s', $backup['created_at']) ?></td>
                    <td><a href="<?= base_url('backups/download/' . $backup['name']) ?>">Download</a></td>
                    <td>
                        <button onclick="restoreBackup('<?= $backup['name'] ?>')">Restore</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function restoreBackup(backupName) {
            if (confirm("Are you sure you want to restore this backup? This will overwrite current data.")) {
                window.location.href = '/backups/restore/' + backupName;
            }
        }
    </script>
</body>
</html>
