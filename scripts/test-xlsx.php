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
    $Line['NumberOfShares'] = (int) $Line['NumberOfShares'];
    $Line['EarningsPerShare'] = (float) substr($Line['EarningsPerShare'], 3);
    $Line['NetEarnings'] = (float) substr($Line['NetEarnings'], 3);
    $Line['Earnings'] = round($Line['EarningsPerShare']*$Line['NumberOfShares'], 2);
    $Line['Taxes'] = round($Line['Earnings'] - $Line['NetEarnings'],2);
    $Line['NetEarningsPerShare'] = round($Line['NetEarnings']/$Line['NumberOfShares'],2);


    $Data[] = $Line;
    $Line = [];

}

var_dump($Data);exit;

