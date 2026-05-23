<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$File = '/home/john/Desktop/b3_dividend_data.xlsx';
$SpreadSheet = IOFactory::load($File);
$Sheet = $SpreadSheet->getSheet(0);

$SheetData = $Sheet->toArray(null, true, true, true);

$Data = [];
$Keys = [];
$Line = [];

$KeyMap = [
    'Produto' => 'Asset',
    'Pagamento' => 'PaymentDate',
    'Tipo de Evento' => 'EventType',
    'Instituição' => 'Brokerage',
    'Quantidade' => 'NumberOfShares',
    'Preço unitário' => 'EarningsPerShare',
    'Valor líquido' => 'NetEarnings',
];

foreach ($SheetData as $Index => $Row) {

    if($Index == 1){

        foreach($Row as $Key => $Value){

            $Keys[$Key] = $KeyMap[$Value];

        }


    } else {

        foreach($Row as $Key => $Value){

            $Line[$Keys[$Key]] = trim($Value);

        }

    }

    if(empty($Line['Asset']))
        continue;

    $Line['Asset'] = trim(explode('-', $Line['Asset'])[0]);
    unset($Line['EventType']);
    $Line['NumberOfShares'] = str_replace('.','',$Line['NumberOfShares']);
    $Line['NumberOfShares'] = (float) $Line['NumberOfShares'];
    $Line['EarningsPerShare'] = str_ireplace('R$ ', '', $Line['EarningsPerShare']);
    $Line['EarningsPerShare'] = (float) $Line['EarningsPerShare'];
    $Line['NetEarnings'] = str_ireplace('R$ ', '', $Line['NetEarnings']);
    $Line['NetEarnings'] = (float) $Line['NetEarnings'];
    $Line['Earnings'] = round($Line['EarningsPerShare']*$Line['NumberOfShares'], 2);
    $Line['Taxes'] = round($Line['Earnings'] - $Line['NetEarnings'],2);

    if(empty($Line['NumberOfShares']))
        $Line['NetEarningsPerShare'] = 0;
    else
        $Line['NetEarningsPerShare'] = round($Line['NetEarnings']/$Line['NumberOfShares'],2);


    $Data[] = $Line;
    $Line = [];

}

var_dump($Data);exit;

