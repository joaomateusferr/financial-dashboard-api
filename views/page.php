<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My First Webpage</title>
</head>
<body>

    <?php

    foreach(['teste1', 'teste2'] as $Value){
        echo $Value;
    }

    if(!empty($_GET['a']))
        echo $_GET['a'];

    ?>
    <h1>Welcome to My Website</h1>
    <p>This is a simple paragraph of text on my new page.</p>
    <a href="<?php echo $Link; ?>">Visit <?php echo $Site; ?></a>

</body>
</html>