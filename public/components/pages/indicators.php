<?php
    require_once("../start.php");
    $CurrentPage = basename(__FILE__, '.php');

    $Indicators = new Indicators();

?>

<!DOCTYPE html>

<html>
    
    <?php
        require_once("../head.php");
    ?>

	<body>

        <?php
            require_once("../sidebar.php");
        ?>

        <div class="main">

            <div class="container center">
                indicators
            </div>

        </div>

	</body>
	
</html>