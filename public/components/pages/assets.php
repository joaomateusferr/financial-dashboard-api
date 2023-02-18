<?php
    require_once("../start.php");
    $CurrentPage = basename(__FILE__, '.php');

    $Assets = ['0'=>'IVVB11'];//test only
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
                
                <form class="col-sm-12" id="FormAssets" action="" method="post">

                    <div class="row col-sm-12">

                        <table id="Table" class="table">

                            <thead>
                                <tr>
                                    <th scope="col">Assets</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php

                                foreach($Assets as $ID => $AssetName){
                                    
                                    echo'<tr>
                                            <td>'.$AssetName.'</td>
                                            <td>
                                                <button type="button" onclick="RemoveAssetRow(this)">Remove</button>
                                                <button type="button" onclick="EditAssetRow(this)">Edit</button>
                                                <input type="hidden" name="IDs[]" value="'.$ID.'">
                                            </td>
                                        </tr>';
                                }
                            ?>

                            </tbody>


                        </table>
                    </div>

                    <br><br>

                    <div class="row col-sm-12">
                        <div class="col-sm-8">
                            <button type="button" onclick="AddAssetRow(this)">+ Add Asset</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit"> Save </button>
                        </div>
                    </div>

                
                </form>
    
            </div>

        </div>

        <script>

            function RemoveAssetRow(El) {
                var Table = document.getElementById('Table');
                Table.deleteRow(El.parentNode.parentNode.rowIndex);
            }

        </script>

	</body>
	
</html>