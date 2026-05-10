<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\Investidor10;
use \Exception;
use \DateTime;

class B3IncomeReport {

    private array $Operations = [];

    public function __construct(string $ReportPath) {

        if(!file_exists($ReportPath))
            throw new Exception('Report file not found');

        if(pathinfo($ReportPath, PATHINFO_EXTENSION) != 'xlsx')
            throw new Exception('The report file format must be xlsx (Excel)');

        $SpreadSheet = IOFactory::load($ReportPath);
        $Sheet = $SpreadSheet->getSheet(0);

        $SheetData = $Sheet->toArray(null, true, true, true);

        $this->Operations = [];
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

            $Line['PaymentDateTimestamp'] = (DateTime::createFromFormat('d/m/Y', $Line['PaymentDate']))->getTimestamp();

            $this->Operations[] = $Line;
            $Line = [];

        }

        uasort($this->Operations, function ($OperationA, $OperationB) {
            return $OperationA['PaymentDateTimestamp'] <=> $OperationB['PaymentDateTimestamp'];
        });

        $this->Operations = array_values($this->Operations);
        $this->filloutRefund();

    }

    private function filloutRefund() {

        $Investidor10Assets = [];

        foreach($this->Operations as $Index => $Operation){

            if($Operation['EventType'] == 'Reembolso'){

                $Investidor10Assets[$Operation['Asset']] = 'ACAO';

            }

        }

        $AssetsData = Investidor10::getAssetsData($Investidor10Assets);

        var_dump($AssetsData);exit;

    }

}