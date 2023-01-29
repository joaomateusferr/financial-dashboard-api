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

            <div class="container">

                <div class="row">
                    
                    <div class="col-sm-2">
                        <?php echo 'R$ 1' $Indicators->$DollarRealExchangeRate->AskPrice.'<br>'.
                    </div>

                    <div class="col-sm-2">
                        
                    </div>
                
                </div>
                
            </div>

        </div>

	</body>
	
</html>