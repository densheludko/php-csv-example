<?php

namespace Interfaces\Persistence;


use Exception;
use PDO;

/**
 *
 */
interface IConnectionFactory {
    /**
     * @return PDO.
     * @throws Exception
     */
    function create(): \PDO;
}