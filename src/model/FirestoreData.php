<?php

namespace App\Model;

use Firebase\JWT\JWT;

class FirestoreData
{
    private $value;
    private $timestamp;

    public function __construct($value, $timestamp)
    {
        $this->value = (string) $value; 
        $this->timestamp = $timestamp;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getFormattedTimestamp($format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($this->timestamp));
    }
}
