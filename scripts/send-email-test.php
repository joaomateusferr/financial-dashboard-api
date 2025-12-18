<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\EmailSender;
use App\Services\EmailContent;
use App\Helpers\EmailHelper;

try{

    $EmailProvider = EmailHelper::buildProvider();
    $EmailContent =  new EmailContent('Teste de script', 'Veio do script!');
    $SMime = EmailHelper::buildSMime();

} catch (Exception $Exception) {

    error_log($Exception->getMessage());

}

$Recipients = ['joaomateusferr@gmail.com'];
$Email = new EmailSender($EmailProvider, 'joaomateusferr@gmail.com', $Recipients, $EmailContent, $SMime);
$Email->sendOneByOne();




