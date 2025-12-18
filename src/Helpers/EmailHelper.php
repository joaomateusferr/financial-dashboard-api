<?php

namespace App\Helpers;

use \App\Services\EmailProvider as Provider;
use \App\Services\EmailSMime as SMime;

class EmailHelper {

    public static function buildProvider() : Provider {

        $Environment = 'DEV';

        if($Environment == 'DEV' || $Environment == 'CLI' )
            return new Provider('localhost', 1025);


        $User = 'user';
        $Password = 'password';
        return new Provider('smtp.gmail.com', 465, $User, $Password);

    }

    public static function buildSMime() : ?SMime {

        $Environment = 'DEV';

        if($Environment == 'DEV' || $Environment == 'CLI' )
            return null;

        $CertificatePath = '';
        $KeyPath= '';
        $KeyPassword= '';

        return new SMime($CertificatePath, $KeyPath, $KeyPassword);

    }

}