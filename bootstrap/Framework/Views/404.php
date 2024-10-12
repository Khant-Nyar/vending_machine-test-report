<?php
// Check if error reporting is enabled and retrieve error details if available
$errorDetails = isset($error) ? $error : null;

// Set default message and status code
$statusCode = 404;
$message = "Page Not Found";
if ($errorDetails) {
    // Parse error details if available
    list($file, $line) = explode(':', $errorDetails);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $statusCode; ?> - <?php echo $message; ?></title>
    <link href="https://cdn.tailwindcss.com?plugins=forms" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-md w-full">
        <h1 class="text-4xl font-bold text-red-600"><?php echo $statusCode; ?></h1>
        <p class="mt-2 text-gray-700"><?php echo $message; ?></p>

        <?php if ($errorDetails): ?>
        <div class="mt-4 text-sm text-gray-500">
            <p>Error Details:</p>
            <p class="whitespace-pre-wrap"><?php echo htmlspecialchars($errorDetails); ?></p>
        </div>
        <?php endif; ?>

        <div class="mt-6">
            <a href="javascript:history.back()"
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Go Back
            </a>
        </div>
    </div>
</body>

</html>