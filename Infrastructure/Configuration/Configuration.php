<?php

namespace Configuration;

use Interfaces\Configuration\IConfiguration;

require_once "Application\Interfaces\Configuration\IConfiguration.php";


/**
 * Main application config.
 */
class Configuration implements IConfiguration {
    /**
     * @var mixed
     */
    private $jsonConfig;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName = "config.json") {
        $json = file_get_contents($fileName);
        $this->jsonConfig = json_decode($json, true);
    }

    /**
     * Gets the value from env or json.
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key) {
        $result = getenv($key);
        if ($result) {
            return $result;
        }

        $splitData = explode(":", $key);
        $result = $this->jsonConfig[$splitData[0]];

        for ($i = 1; $i < count($splitData); $i++) {
            if (!$result) {
                return null;
            }
            $result = $result[$splitData[$i]];
        }

        return $result;
    }
}