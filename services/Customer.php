<?php

class Customer {

    private $ID;
    private $Server = '';
    private $ExchangeTradedAssets = [];
    private $Currencys = [];

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

        $Sql = 'SELECT id, ticker, currency_id FROM exchange_traded_assets WHERE id IN ("'.implode('","', $CustomerAssetIDs).'")';
        $Stmt = $CommonInformationConnection->prepare($Sql);
        $Result = $Stmt->execute();

        $ExchangeTradedAssets = $Currencys = [];

        if($Result && $Stmt->rowCount() > 0){

            while($Row = $Stmt->fetch()){

                $Infos = [];

                foreach($Row as $Key => $Value){

                    if($Key == 'id')
                        continue;

                    $Infos[$Key] = $Value;

                }

                if(!isset($Currencys[$Row['currency_id']]))
                    $Currencys[$Row['currency_id']] = [];

                $ExchangeTradedAssets[$Row['id']] = $Infos;

            }

        }

        $CurrencyIDs = array_keys($Currencys);

        $Sql = 'SELECT id, name, symbol, iso_code FROM currencys WHERE id IN ("'.implode('","', $CurrencyIDs).'")';
        $Stmt = $CommonInformationConnection->prepare($Sql);
        $Result = $Stmt->execute();

        if($Result && $Stmt->rowCount() > 0){

            while($Row = $Stmt->fetch()){

                $Infos = [];

                foreach($Row as $Key => $Value){

                    if($Key == 'id')
                        continue;

                    $Infos[$Key] = $Value;

                }

                $Currencys[$Row['id']] = $Infos;

            }

        }

        $CommonInformationConnection->close();

        $this->ExchangeTradedAssets = $ExchangeTradedAssets;
        $this->Currencys = $Currencys;

    }

    public function getServer() : string {

        return $this->Server;

    }

    public function getExchangeTradedAssets() : array {

        return $this->ExchangeTradedAssets;

    }

    public function getCurrencys() : array {

        return $this->Currencys;

    }

    public function getPositions() : array {

        $Positions = [];

        $CommonInformationConnection = new MariaDB($this->Server, 'c_'.$this->ID);

        $Sql = 'SELECT asset_id, monetary_return, percentage_return, monetary_return_with_dividends, percentage_return_with_dividends FROM consolidated_exchange_traded_assets';
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