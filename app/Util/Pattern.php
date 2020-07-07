<?php

namespace App\Util;

class Pattern {

    protected static $UUIDv4Pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    public static function verifyValidUUID($receivedUUID){
        if(preg_match(Pattern::$UUIDv4Pattern, $receivedUUID)){
            return true;
        }
        return false;
    }
}
