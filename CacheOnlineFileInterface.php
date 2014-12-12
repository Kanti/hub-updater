<?php

namespace Kanti;

/**
 * Interface CacheOnlineFileInterface
 * @package Kanti
 */
interface CacheOnlineFileInterface {
    /**
     * @param $url
     * @param int $seconds
     * @param mixed $streamContext
     */
    public function __construct($url,$seconds = 4200,$streamContext = null);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getFile();

    /**
     *
     */
    public function force();
}