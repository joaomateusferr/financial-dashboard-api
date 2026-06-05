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

        $FilePath = $this->AdapterFolder.uniqid().'.json';
        file_put_contents($FilePath, json_encode($this->AssetsByDate));

        $Command = 'python3 '.$this->ClosePriceOnDateAdapterPath.' '.$FilePath;
        $Output = [];
        $ResultCode = 0;
        exec($Command, $Output, $ResultCode);
        unlink($FilePath);

        if(!empty($ResultCode)){

            $ExceptionString = "$ResultCode - ";

            foreach($Output as $Line){
                $ExceptionString .= "$Line ";
            }

            $ExceptionString = substr($ExceptionString, 0, -1);
            $ExceptionString .= "!";

            throw new Exception($ExceptionString);
        }

        return json_decode($Output[0], true);

    }

}