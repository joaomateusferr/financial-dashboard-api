<?php

class Options {
    
    private const DEFAULT_OPTIONS = [
        'Theme' => 'DARK',
        'UsefulLinks' => [],
    ];

    public $Theme;
    public $UsefulLinks;

    function __construct() {

        $OptionsFilePath = $_SERVER['DOCUMENT_ROOT'].'/dev-toolkit/config/options.json';

        if(file_exists($OptionsFilePath)){
            
            $Options = json_decode(file_get_contents($OptionsFilePath), true);

            foreach ($Options as $Key => $Option){
                $this->$Key = $Option;
            }

        } else {

            foreach (self::DEFAULT_OPTIONS as $Key => $Option){
                $this->$Key = $Option;
            }

            file_put_contents($OptionsFilePath, json_encode(self::DEFAULT_OPTIONS, JSON_PRETTY_PRINT)); 
        }
    
    }
    
}