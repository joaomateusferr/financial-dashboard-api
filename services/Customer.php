<?php

class Customer {

    private $ID;
    private $Server = '';
    private $ExchangeTradedAssets = [];

    function __construct(?int $ID = null) {

        if(is_null($ID))
            return;

        $this->ID = $ID;

        $this->getCustomerDetails();
        $this->getCustomerExchangeTradedAssetIDs();

    }

    private function getCustomerDetails() : void {

        $CustomersConnection = new MariaDB('customers', 'customers');

        $Sql = 'SELECT server FROM customers WHERE id = ?';
        $Stmt = $CustomersConnection->prepare($Sql);
        $Result = $Stmt->execute([$this->ID]);

        $CustomerServer = '';

        if($Result && $Stmt->rowCount() > 0)
            $this->Server = $Stmt->fetch()['server'];

        if(empty($this->Server))
            throw new Exception("Empty server for $this->ID!");

        $CustomersConnection->close();

    }

    private function getCustomerExchangeTradedAssetIDs() : void {

        $CustomerDatabaseConnection = new MariaDB($this->Server, 'c_'.$this->ID);

        $Sql = 'SELECT DISTINCT asset_id FROM exchange_traded_assets';
        $Stmt = $CustomerDatabaseConnection->prepare($Sql);
        $Result = $Stmt->execute();

        $CustomerAssetIDs = [];

        if($Result && $Stmt->rowCount() > 0)
            $CustomerAssetIDs = $Stmt->fetchAll(PDO::FETCH_COLUMN);

        $CustomerDatabaseConnection->close();

        $CommonInformationConnection = new MariaDB('common-information', 'common_information');

        $Sql = 'SELECT id, ticker FROM exchange_traded_assets WHERE id IN ("'.implode('","', $CustomerAssetIDs).'")';
        $Stmt = $CommonInformationConnection->prepare($Sql);
        $Result = $Stmt->execute();

        $ExchangeTradedAssets = [];

        if($Result && $Stmt->rowCount() > 0){

            while($Row = $Stmt->fetch()){

                $Infos = [];

                foreach($Row as $Key => $Value){

                    if($Key == 'id')
                        continue;

                    $Infos[$Key] = $Value;

                }

                $ExchangeTradedAssets[$Row['id']] = $Infos;

            }

        }

        $CommonInformationConnection->close();

        $this->ExchangeTradedAssets = $ExchangeTradedAssets;

    }

    public function getServer() : string {

        return $this->Server;

    }

    public function getExchangeTradedAssets() : array {

        return $this->ExchangeTradedAssets;

    }

    public function getPositions(bool $Dividends = false) : array {

        $Positions = [];

        $Columns = ['asset_id'];

        if($Dividends){
            $Columns = array_merge($Columns, ['monetary_return_with_dividends', 'percentage_return_with_dividends']);
            $Order = 'monetary_return_with_dividends';
        } else {
            $Columns = array_merge($Columns, ['monetary_return', 'percentage_return']);
            $Order = 'monetary_return';
        }

        $CommonInformationConnection = new MariaDB($this->Server, 'c_'.$this->ID);

        $Sql = 'SELECT '.implode(',', $Columns).' FROM consolidated_exchange_traded_assets ORDER BY '.$Order.' DESC';
        $Stmt = $CommonInformationConnection->prepare($Sql);
        $Result = $Stmt->execute();

        while($Row = $Stmt->fetch()){

            $Infos = [];

            foreach($Row as $Key => $Value){

                if($Key == 'asset_id')
                    continue;

                $Infos[$Key] = $Value;

            }

            $Positions[$Row['asset_id']] = $Infos;

        }

        return $Positions;

    }

}