<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
    <script>
        // Open the contract termination page in a new tab
        window.open('<?= base_url($newTabUrl) ?>', '_blank');

        // Redirect to the deactivate function in the same tab
        window.location.href = '<?= base_url($redirectUrl) ?>';
    </script>
    <p>Redirecting, please wait...</p>
</body>
</html>