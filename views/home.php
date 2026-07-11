<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Financial Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="index.css">

</head>
<body>

<div class="app">

    <?php

        $Page = isset($_GET['page']) ? $_GET['page'] : '';

        $LoggedIn = false;

        if($LoggedIn){

            require_once dirname(__DIR__, 1).'/views/dashboard/main.php';

        }elseif(empty($Page)){

            require_once dirname(__DIR__, 1).'/views/login.php';

        } else {

            $PagePath = dirname(__DIR__, 1)."/views/$Page.php";

            if(file_exists($PagePath))
                require_once $PagePath;
            else
                require_once dirname(__DIR__, 1).'/views/page-not-found.php';

        }

    ?>

</div>

<script>

function updateTheme() {
    document.documentElement.setAttribute('data-bs-theme',window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
}

updateTheme();

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateTheme);

</script>

</body>
</html>
