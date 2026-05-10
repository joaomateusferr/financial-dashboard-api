<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\B3IncomeReport;

$File = '/home/john/Desktop/b3_dividend_data.xlsx';
$B3IncomeReport = new B3IncomeReport($File);

