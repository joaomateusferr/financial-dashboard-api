<?php

namespace App\Helpers;

use \Exception;
use \DOMDocument;
use \DOMXPath;

class Investidor10WebParser {

    private $MarketPrice = 0;
    private $DividendsHistory = [];

    public function __construct(string $Url, array $SpecificData = ['MarketPrice', 'DividendsHistory']) {

        $Page = $this->getPage($Url);

        if(empty($Page)){

            $ClassName = static::class;
            throw new Exception("Error - $ClassName - $Url");

        }

        $SpecificData = array_flip($SpecificData);

        if(isset($SpecificData['MarketPrice']))
            $this->MarketPrice = $this->parseMarketPrice($Page);

        if(isset($SpecificData['DividendsHistory']))
            $this->DividendsHistory = $this->parseDividendsHistory($Page);

    }

    private function getPage (string $Url) : string {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $Url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;

    }

    private function parseMarketPrice (string $Page) : float {

        $Document = new DOMDocument();
        @$Document->loadHTML($Page);
        $XPath = new DOMXPath($Document);

        $Div = $XPath->query("//div[contains(@class, '_card cotacao')]");

        if(empty($Div->length))
            return 0;

        $Spam = $XPath->query(".//span[contains(@class, 'value')]", $Div[0]);

        if(empty($Spam->length))
            return 0;

        $Content = trim($Spam[0]->nodeValue);
        $Content = explode(" ",$Content);

        $MarketPrice = trim($Content[array_key_last($Content)]);
        $MarketPrice = (float) str_replace(",",".",$MarketPrice);

        return $MarketPrice;

    }

    private function parseDividendsHistory (string $Page) : array {

        $TableData = [];
        $TableFields = [];

        $Document = new DOMDocument();
        @$Document->loadHTML($Page);
        $XPath = new DOMXPath($Document);

        $Table = $XPath->query("//table[contains(@class, 'table-dividends-history')]");

        if(empty($Table->length))
            return [];

        $TableHead = $XPath->query(".//thead/tr", $Table[0]);

        if(empty($TableHead->length))
            return [];

        foreach($TableHead[0] as $ColumnsContent){

            $TableColumnsLines = $XPath->query(".//th/h3", $ColumnsContent);

            foreach($TableColumnsLines as $ColumnsLines){

                $TableFields[] = trim($ColumnsLines->nodeValue);

            }

        }

        $TableRows = $XPath->query(".//tbody/tr", $Table[0]);

        foreach ($TableRows as $TableRowsContent) {

            $TableDataLines = $XPath->query(".//td", $TableRowsContent);

            $LineInformation = [];

            foreach($TableDataLines as $Index => $DataLine){

                $LineInformation[$TableFields[$Index]] = trim($DataLine->nodeValue);

            }

            $TableData[] = $LineInformation;

        }

        $DividendTableFieldMap = \App\Constants\Investidor10Constants::getDividendTableFieldMap();

        foreach($TableData as $Index => $Line){

            $TableData[$Index][$DividendTableFieldMap['pagamento']] = $TableData[$Index]['pagamento'];
            unset($TableData[$Index]['pagamento']);

            $DataParcial = explode('/', $TableData[$Index][$DividendTableFieldMap['pagamento']]);
            $TableData[$Index][$DividendTableFieldMap['pagamento']] = $DataParcial[1] . '/' . $DataParcial[0] . '/' . $DataParcial[2];
            $TableData[$Index][$DividendTableFieldMap['pagamento']] = strtotime($TableData[$Index][$DividendTableFieldMap['pagamento']].' 00:00:00');

            $TableData[$Index][$DividendTableFieldMap['data com']] = $TableData[$Index]['data com'];
            unset($TableData[$Index]['data com']);

            $DataParcial = explode('/', $TableData[$Index][$DividendTableFieldMap['data com']]);
            $TableData[$Index][$DividendTableFieldMap['data com']] = $DataParcial[1] . '/' . $DataParcial[0] . '/' . $DataParcial[2];
            $TableData[$Index][$DividendTableFieldMap['data com']] = strtotime($TableData[$Index][$DividendTableFieldMap['data com']].' 00:00:00');


            $TableData[$Index][$DividendTableFieldMap['valor']] = $TableData[$Index]['valor'];
            unset($TableData[$Index]['valor']);

            $TableData[$Index][$DividendTableFieldMap['valor']] = (float) str_replace(",",".",$TableData[$Index][$DividendTableFieldMap['valor']]);


            $TableData[$Index][$DividendTableFieldMap['tipo']] = $TableData[$Index]['tipo'];
            unset($TableData[$Index]['tipo']);

        }

        return $TableData;

    }

    public function getMarketPrice() : float {
        return $this->MarketPrice;
    }

    public function getDividendsHistory() : array {
        return $this->DividendsHistory;
    }

}