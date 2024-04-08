<?php

namespace Application\Interfaces\Configuration;

/**
 *
 */
interface IConfiguration {
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
}