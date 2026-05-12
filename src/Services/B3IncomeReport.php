<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Constants\TaxesConstants;
use App\Services\Investidor10;
use \Exception;
use \DateTime;

class B3IncomeReport {

    private array $Operations = [];

    public function __construct(string $ReportPath, array $Assets = []) {

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
        $Refund = [];

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

            if(empty($Line['NumberOfShares'])){

                $Line['NetEarningsPerShare'] = 0;
                $Refund[$Line['Asset']] = true;


            } else {

                $Line['NetEarningsPerShare'] = round($Line['NetEarnings']/$Line['NumberOfShares'],2);

            }

            $Line['PaymentDateTimestamp'] = (DateTime::createFromFormat('d/m/Y', $Line['PaymentDate']))->setTime(0, 0, 0)->getTimestamp();

            $this->Operations[] = $Line;
            $Line = [];

        }

        uasort($this->Operations, function ($OperationA, $OperationB) {
            return $OperationA['PaymentDateTimestamp'] <=> $OperationB['PaymentDateTimestamp'];
        });

        $this->Operations = array_values($this->Operations);

        if(!empty($Refund)){

            foreach($Refund as $Ticker => $Value){

                if(!isset($Assets[$Ticker]))
                    throw new Exception("When refunds exist, it is necessary to insert the asset into the assets array, $Ticker not found");

                $Refund[$Ticker] = $Assets[$Ticker]; //Add refund asset type

            }

            $this->filloutRefund($Refund);
        }

    }

    private function filloutRefund(array $RefundAssets) {

        $RefundAssetsDetails = [];
        $Taxes = TaxesConstants::getTaxesMap();

        foreach($RefundAssets as $Ticker => $Type){

            $RefundAssetsDetails[$Ticker] = Investidor10::getAssetData($Ticker, $Type);

        }

        foreach($this->Operations as $Index => $Operation){

            if($Operation['EventType'] == 'Reembolso'){

                $Data = null;

                foreach($RefundAssetsDetails[$Operation['Asset']]['DividendsHistory'] as $Event){

                    if($Event['PaymentDate'] == $Operation['PaymentDateTimestamp']){

                        $Data = $Event;
                        break;

                    }

                }

                if(empty($Data))
                    continue;

                $AssetType = $RefundAssets[$Operation['Asset']];
                $OperationType = $Data['Type'];
                $Multiplier = isset($Taxes[$AssetType][$OperationType]) ? $Taxes[$AssetType][$OperationType] : 0;

                $this->Operations[$Index]['EarningsPerShare'] = $Data['Income'];
                $this->Operations[$Index]['NetEarningsPerShare'] = $this->Operations[$Index]['EarningsPerShare'] * (1 - $Multiplier);
                $this->Operations[$Index]['NumberOfShares'] = ceil($this->Operations[$Index]['NetEarnings']/$this->Operations[$Index]['NetEarningsPerShare']);
                $this->Operations[$Index]['Earnings'] = $this->Operations[$Index]['NumberOfShares']*$this->Operations[$Index]['EarningsPerShare'];
                $this->Operations[$Index]['Taxes'] = round($this->Operations[$Index]['Earnings'] - $this->Operations[$Index]['NetEarnings'],2);

            }

        }

    }

}