<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\B3IncomeReport;

$File = '/home/john/Desktop/b3_dividend_data.xlsx';
$B3IncomeReport = new B3IncomeReport($File, ['BBDC4' => 'ACAO']);
$Operations = $B3IncomeReport->getOperations();
$Folder = '/tmp/';

$AssetsByDate = [];

foreach($Operations as $Operation){

    $Date = date('Y-m-d', $Operation['PaymentDateTimestamp']);

    if(!isset($AssetsByDate[$Date]))
        $AssetsByDate[$Date] = [];

    $AssetsByDate[$Date][$Operation['Asset'].'.SA'] = true;

}

foreach($AssetsByDate as $Date => $Assets){

    $AssetsByDate[$Date] = array_keys($Assets);

}

$FilePath = $Folder.uniqid().'.json';
file_put_contents($FilePath, json_encode($AssetsByDate));

$Command = 'python3 '.__DIR__.'/../adapters/assets-close-price-on-date.py '.$FilePath;
$Output = [];
$ResultCode = 0;
exec($Command, $Output, $ResultCode);

unlink($FilePath);

if(empty($ResultCode)){
    $Output = $Output[0];
} else {
    var_dump($Output);exit;
}

$AssetsByDate = json_decode($Output, true);

foreach($AssetsByDate as $Date => $Infos){

    $AssetsByDate[strtotime($Date)] = $Infos;
    unset($AssetsByDate[$Date]);

}

$AveragePrice = [
    'BCIA11' => 104.68,
    'BBDC4' => 13.42,
    'B3SA3' => 10.58,
    'HGBS11' => 20.25,
    'HGLG11' => 157.44,
    'KNRI11' => 149.72,
    'NDIV11' => 110.65,
    'XPLG11' => 104.02,
    'CPTS11' => 7.88,
    'ALZR11' => 10.69,
    'XPML11' => 106.22,
];

$ExportFields = ['PaymentDate', 'Asset', 'NumberOfShares', 'Earnings', 'Taxes', 'NetEarnings', 'NetEarningsPerShare', 'ClosingPrice' , 'DividendYield', 'AveragePrice', 'NetDividendYieldOnCost', 'Type', 'Brokerage'];

$Data = [];
$Data[] = $ExportFields;

foreach($Operations as $Index => $Operation){

    $Operations[$Index]['ClosingPrice'] = $AssetsByDate[$Operation['PaymentDateTimestamp']][$Operation['Asset'].'.SA'];
    $Operations[$Index]['AveragePrice'] = $AveragePrice[$Operation['Asset']];
    preg_match('/\d+$/', $Operation['Asset'], $Matches);
    $Operations[$Index]['Type'] = $Matches[0] ?? null;

    $Line = [];

    foreach($ExportFields as $ExportField){

        if(!in_array($ExportField,['PaymentDate', 'Brokerage'])){
            $Operations[$Index][$ExportField] = isset($Operations[$Index][$ExportField]) ? (string) $Operations[$Index][$ExportField] : '-';
            $Operations[$Index][$ExportField] = str_replace(".", ",", $Operations[$Index][$ExportField]);
        }

        $Line[] = isset($Operations[$Index][$ExportField]) ? $Operations[$Index][$ExportField] : '-';

    }

    $Data[] = $Line;

}

$File = fopen('data.csv', 'w');

foreach ($Data as $Fields) {
    fputcsv($File, $Fields);
}

fclose($File);

