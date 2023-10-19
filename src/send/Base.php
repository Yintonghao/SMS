<?php

namespace Mydom\Sms\send;

class Base
{
    public $appid = null;
    public $secret = null;
    public $platform = null;

    public function setPlatform($name)
    {
        $this->platform = $name;
    }

    public function setAppid($appid)
    {
        $this->appid = $appid;
    }
    
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }
    
    public function getCode($length = 6)
    {
        $characters = '0123456789';
        $charLength = strlen($characters);
        $code = '';
        $maxAttempts = $charLength ** $length;
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charLength - 1)];
        }
        return $code;
    }
}