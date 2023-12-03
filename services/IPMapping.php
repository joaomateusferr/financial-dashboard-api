<?php

class IPMapping {

    public static function get(string $Server) : array {

        $ServerList = self::getServerListFromOptions();

        if(isset($ServerList[$Server]))
            return $ServerList[$Server];

        return [];

    }

    public static function getServerListFromOptions() : array {

        return (new Options())->ServersList;

    }

}