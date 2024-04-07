<?php

namespace Persistence;

require_once "Application\Interfaces\Configuration\IConfiguration.php";
require_once "Application\Interfaces\Persistence\IConnectionFactory.php";
require_once "Infrastructure\Constants\ConfigConstants.php";

use Constants\ConfigConstants;
use Exception;
use Interfaces\Configuration\IConfiguration;
use Interfaces\Persistence\IConnectionFactory;
use PDO;

/**
 *
 */
class ConnectionFactory implements IConnectionFactory {

    /**
     * @var IConfiguration
     */
    private $configuration;

    public function __construct(IConfiguration $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * @return PDO.
     */
    function create(): PDO {
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
            die($ex->getTrace());
        }
    }
}