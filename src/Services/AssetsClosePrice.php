<?php

namespace App\Services;

use \DateTime;
use \Exception;

class AssetsClosePrice {

    private string $ClosePriceOnDateAdapterPath;
    private string $AdapterFolder;
    private array $AssetsByDate;

    public function __construct(array $AssetsByDate = []) {

        if(empty($AssetsByDate))
            throw new Exception("Assets by date cannot be empty!");

        $this->ClosePriceOnDateAdapterPath = __DIR__.'/../../adapters/assets-close-price-on-date.py';
        $this->AdapterFolder = '/tmp/';

        foreach($AssetsByDate as $DateString => $Assets){

            $Date = DateTime::createFromFormat('Y-m-d', $DateString);
            $IsValidDate = $Date && $Date->format('Y-m-d') === $DateString;

            if(!$IsValidDate)
                throw new Exception("Invalid date ($DateString) on assets by date!");

            if(empty($Assets))
                unset($AssetsByDate[$DateString]);

        }

        if(empty($AssetsByDate))
            throw new Exception("Assets by date must contain at least one asset on at least one valid date!");

        $this->AssetsByDate = $AssetsByDate;

    }

    public function fetch() : array {

        return [];

    }

}