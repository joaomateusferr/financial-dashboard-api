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

                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="Indicators">

                    <div class="row col-sm-12">
                        
                        <div class="col-sm-4">
                            <?php echo 'US$ '?> <input type="number" id="dollar" name="dollar" value="1">  
                        </div>

                        <div class="col-sm-4">
                            <?php echo ' -> R$ '?> <input type="number" id="real" name="real" value="<?php echo (isset($Indicators->DollarRealExchangeRate['AskPrice']) && $Indicators->DollarRealExchangeRate['AskPrice']) ? $Indicators->DollarRealExchangeRate['AskPrice'] : 0;?>">
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->DollarRealExchangeRate['LatestUpdate']) && $Indicators->DollarRealExchangeRate['LatestUpdate']) ? $Indicators->DollarRealExchangeRate['LatestUpdate'] : 0;?>
                        </div>
                    
                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <?php echo 'Selic: '?>
                            <?php echo (isset($Indicators->SpecialSettlementAndCustodySystem['Rate']) && $Indicators->SpecialSettlementAndCustodySystem['Rate']) ? $Indicators->SpecialSettlementAndCustodySystem['Rate'] : 0;?>
                        </div>
                        
                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->SpecialSettlementAndCustodySystem['LatestUpdate']) && $Indicators->SpecialSettlementAndCustodySystem['LatestUpdate']) ? $Indicators->SpecialSettlementAndCustodySystem['LatestUpdate'] : 0;?>
                        </div>

                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <button type="submit" value="Submit">Update Indicators</button>
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Last General Update: '?>
                            <?php echo (isset($Indicators->LatestUpdate) && $Indicators->LatestUpdate) ? $Indicators->LatestUpdate : 0;?>
                        </div>

                    </div>
                
                </form>
                
            </div>

        </div>

	</body>
	
</html>