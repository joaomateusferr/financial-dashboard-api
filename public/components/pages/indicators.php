<?php
    require_once("../start.php");
    $CurrentPage = basename(__FILE__, '.php');

    $Indicators = new Indicators();

    $UpdateIndicators = $_POST['UpdateIndicators'] ?? 0;

    if($UpdateIndicators){
        $Indicators->updateIndicators();
    }

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

                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="Indicators" id="Indicators">

                    <div class="row col-sm-12">
                        
                        <div class="col-sm-4">
                            <?php echo 'US$ '?> <input type="number" id="dollar" name="dollar" value="1">  
                        </div>

                        <div class="col-sm-4">
                            <?php echo ' -> R$ '?> <input type="number" id="real" name="real" value="<?php echo (isset($Indicators->DollarRealExchangeRate['AskPrice']) && $Indicators->DollarRealExchangeRate['AskPrice']) ? $Indicators->DollarRealExchangeRate['AskPrice'] : 0;?>">
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->DollarRealExchangeRate['LatestUpdate']) && $Indicators->DollarRealExchangeRate['LatestUpdate']) ? date('d/m/Y H:i:s', $Indicators->DollarRealExchangeRate['LatestUpdate']) : 0;?>
                        </div>
                    
                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <?php echo 'Selic: '?>
                            <?php echo (isset($Indicators->SpecialSettlementAndCustodySystem['Rate']) && $Indicators->SpecialSettlementAndCustodySystem['Rate']) ? $Indicators->SpecialSettlementAndCustodySystem['Rate'] : 0;?>
                            <?php echo '%'?>
                        </div>
                        
                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->SpecialSettlementAndCustodySystem['LatestUpdate']) && $Indicators->SpecialSettlementAndCustodySystem['LatestUpdate']) ? date('d/m/Y', $Indicators->SpecialSettlementAndCustodySystem['LatestUpdate']) : 0;?>
                        </div>

                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <?php echo 'DI: '?>
                            <?php echo (isset($Indicators->InterbankDepositRate['Rate']) && $Indicators->InterbankDepositRate['Rate']) ? $Indicators->InterbankDepositRate['Rate'] : 0;?>
                            <?php echo '%'?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->InterbankDepositRate['LatestUpdate']) && $Indicators->InterbankDepositRate['LatestUpdate']) ? date('d/m/Y', $Indicators->InterbankDepositRate['LatestUpdate']) : 0;?>
                        </div>

                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <?php echo 'Inflation: '?>
                            <?php echo (isset($Indicators->InflationRate['Rate']) && $Indicators->InflationRate['Rate']) ? $Indicators->InflationRate['Rate'] : 0;?>
                            <?php echo '%'?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Latest Update: '?>
                            <?php echo (isset($Indicators->InflationRate['LatestUpdate']) && $Indicators->InflationRate['LatestUpdate']) ? date('d/m/Y', $Indicators->InflationRate['LatestUpdate']) : 0;?>
                        </div>

                    </div>

                    <div class="row col-sm-12">

                        <div class="col-sm-8">
                            <button type="button" value="UpdateIndicators" onclick="UpdateIndicators()">Update Indicators</button>
                        </div>

                        <div class="col-sm-4">
                            <?php echo 'Last General Update: '?>
                            <?php echo (isset($Indicators->LatestUpdate) && $Indicators->LatestUpdate) ? date('d/m/Y H:i:s', $Indicators->LatestUpdate) : 0;?>
                        </div>

                    </div>
                
                </form>
                
            </div>

        </div>

        <script>

            function UpdateIndicators(){

                var Indicators = document.getElementById("Indicators");
                var UpdateIndicators = document.createElement("input");
                UpdateIndicators.setAttribute("type", "hidden");
                UpdateIndicators.setAttribute("name", "UpdateIndicators");
                UpdateIndicators.setAttribute("value", "1");
                Indicators.appendChild(UpdateIndicators);
                Indicators.submit();
            }

        </script>

	</body>
	
</html>