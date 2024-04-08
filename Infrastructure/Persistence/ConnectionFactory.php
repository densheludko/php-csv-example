<?php

namespace Infrastructure\Persistence;

use Application\Interfaces\Configuration\IConfiguration;
use Application\Interfaces\Persistence\IConnectionFactory;
use Infrastructure\Constants\ConfigConstants;
use Exception;
use PDO;

/**
 *
 */
class ConnectionFactory implements IConnectionFactory
{

    /**
     * @var IConfiguration
     */
    private $configuration;

    public function __construct(IConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return PDO.
     */
    function create(): PDO
    {
        $host = $this->configuration->get(ConfigConstants::$dbHost);
        $user = $this->configuration->get(ConfigConstants::$dbUser);
        $password = $this->configuration->get(ConfigConstants::$dbPassword);
        $dbName = $this->configuration->get(ConfigConstants::$dbName);

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_AUTOCOMMIT => false
        ];

        try {
            return new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password, $opt);
        } catch (Exception $ex) {
            die($ex->getMessage() . PHP_EOL . 'trace:' . PHP_EOL . print_r($ex->getTrace(), true));
        }
    }
}