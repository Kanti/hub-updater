<?php
namespace Kanti;

interface HubUpdaterInterface {
    /**
     * @param $option
     */
    public function __construct($option);

    /**
     * @return bool
     */
    public function able();

    /**
     * @return bool
     */
    public function update();

    /**
     * @return array
     */
    public function getCurrentInfo();

    /**
     * @return array
     */
    public function getNewestInfo();
} 