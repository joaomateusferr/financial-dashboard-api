<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Financial Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/index.css">

</head>

<body>

    <div class="app">
        <?php echo $content; ?>
    </div>

    <script>

        function updateTheme() {
            document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }

        updateTheme();

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateTheme);

    </script>

</body>

</html>