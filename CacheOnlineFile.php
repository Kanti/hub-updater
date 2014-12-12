<?php

namespace Kanti;

/**
 * Class CacheOnlineFile
 * @package Kanti
 */
class CacheOnlineFile implements CacheOnlineFileInterface {
    /**
     * @param $url
     * @param int $seconds
     * @param mixed $streamContext
     */
    public function __construct($url,$seconds = 4200,$streamContext = null){

    }

    /**
     * @return array
     */
    public function getHeaders(){
        return array();
    }

    /**
     * @return string
     */
    public function getFile(){
        return "";
    }

    /**
     *
     */
    public function force(){

    }

} 