<?php

namespace App\Helpers;

use \Exception;
use App\Services\MariaDB;

class DatabaseHelper {

    public static function connect(string $Server, ?string $Database = null) : bool {

        if(empty($Database))
            return false;

        try {

            $GLOBALS[$Database.'Connection'] = new MariaDB($Server, $Database);

        } catch (Exception $Exception) {

            //add logs here
            return false;

        }

        return true;

    }

    public static function connected(string $Database) : bool {

        if(empty($GLOBALS[$Database.'Connection']))
            return false;

        if(!$GLOBALS[$Database.'Connection'] instanceof MariaDB)
            return false;

        return $GLOBALS[$Database.'Connection']->connected();

    }

    public static function disconnect(string $Database) : void {

        $Connected = self::connected($Database);

        if($Connected)
            $GLOBALS[$Database.'Connection']->close();

    }

    public static function getConnection(string $Database) : MariaDB | bool {

        $Connected = self::connected($Database);

        if(!$Connected)
            return false;

        return $GLOBALS[$Database.'Connection'];

    }

    public static function resetConnection(string $Database) : bool {

        self::disconnect($Database);
        return self::connect($Database);

    }

}