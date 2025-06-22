<?php

namespace App\Services;

use \Exception;

class SharedMemory {

    public $IDString = null;
    public $Exists = null;
    private $ID = null;
    private $Size = 0;
    private $Permission = 0777;

    public function __construct(?string $IDString = null) {

        if(!empty($IDString)){

            $CRC = crc32($IDString);

            if($CRC < 0)
                $CRC = $CRC*-1;

            $this->ID = $CRC;
            $this->IDString = $IDString;

            $this->fill();

        }

    }

    public function exist() :bool {

        $Shmop = @shmop_open($this->ID, "a", 0, 0);

        if($Shmop === false){
            return false;
        } else {
            shmop_close($Shmop);
            return true;
        }

    }

    public function size() :int | bool {

        $Shmop = @shmop_open($this->ID, "a", 0, 0);

        if($Shmop === false){
            return false;
        } else {
            $Size = shmop_size($Shmop);
            shmop_close($Shmop);
            return $Size;
        }

    }

    public function fill () {

        $this->Exists = $this->exist();
        $this->Size = $this->Exists ? $this->size() : 0;

    }

    public function write(array $Information = [], bool $Append = false) {

        if($this->Exists){

            $AssociativeArray = $this->read(true);

            if(isset($this->ID) && isset($AssociativeArray['Control']['ID']) && isset($this->IDString) && isset($AssociativeArray['Control']['String']) && $this->ID == $AssociativeArray['Control']['ID'] && $this->IDString != $AssociativeArray['Control']['String'])
                throw new Exception('Hash collision -> '.$this->ID.' - '.$AssociativeArray['Control']['ID'].' - '.$this->IDString.' - '.$AssociativeArray['Control']['String']);

            $this->delete();

        }

        $Data = [];

        if(!empty($AssociativeArray) && $Append)
            $Information = array_merge($Information, $AssociativeArray['Data']);

        $Information = array_merge($Data, $Information);

        $Data['Data'] = $Information;
        $Data['Control']['ID'] = $this->ID;
        $Data['Control']['String'] = $this->IDString;

        $DataString = json_encode($Data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

        $Shmop = shmop_open($this->ID, "c", $this->Permission, strlen($DataString));

        if($Shmop === false)
            throw new Exception('Unable to create shared memory - '.$this->IDString);

        $this->Size = shmop_write($Shmop, $DataString, 0);
        $this->Exists = true;
        shmop_close($Shmop);

    }

    public function read(bool $ShowControlKeys = false) :array | bool {

        if(!$this->Exists)
            return false;

        $Shmop = shmop_open($this->ID, "a", 0, 0);

        if($Shmop === false)
            throw new Exception('Unable to read shared memory - read - '.$this->IDString);

        $ShmopDataString = shmop_read($Shmop, 0, $this->Size);

        shmop_close($Shmop);

        if(empty($ShmopDataString))
            return false;

        $AssociativeArray = json_decode($ShmopDataString, true);

        if(json_last_error() === JSON_ERROR_NONE){

            if(!isset($AssociativeArray['Control']['ID']) || !isset($AssociativeArray['Control']['String']) || !isset($AssociativeArray['Data']))
                throw new Exception('Invalid structure! Probably not created by the php shared memory class.');


            if(!isset($this->IDString))
                $this->IDString = $AssociativeArray['Control']['String'];

            if($ShowControlKeys)
                return $AssociativeArray;
            else
                return $AssociativeArray['Data'];
        }

        return false;

    }

    public function delete() :bool {

        if(!$this->Exists)
            return true;

        $Shmop = shmop_open($this->ID, "a", 0, 0);

        if($Shmop === false)
            throw new Exception('Unable to read shared memory - delete - '.$this->IDString);

        $Result = @shmop_delete($Shmop);
        shmop_close($Shmop);

        if($Result == false)
            throw new Exception('Unable to delete shared memory - '.$this->IDString);

        $this->Exists = false;

        return $Result;

    }

    public function setPermissions(int $Permission) :bool {

        $this->Permission = $Permission;
        return true;

    }

    public function getPermissions() :int {

        return $this->Permission;

    }

    public function listSharedMemoryKeys() :array {

        $Output = [];
        $ResultCode = 0;

        $Command = "ipcs -m | awk '{print $1}' | grep -o '0x[0-9a-fA-F]*'";

        exec($Command, $Output, $ResultCode);

        if($ResultCode != 0)
            throw new Exception('Unable to list shared memory keys - '.$ResultCode);

        $Response = [];

        if($ResultCode == 0 && !empty($Output)){

            foreach($Output as $Line){

                $Line = trim($Line);

                if(hexdec($Line) > 0)
                    $Response[] = $Line;

            }
        }

        return $Response;

    }

    public function setID(int $ID) :bool {

        $this->ID = $ID;
        return true;

    }

}