<?php

namespace Application;

use Application\Interfaces\Configuration\IConfiguration;
use Application\Interfaces\Files\ICsvFileReader;
use Application\Interfaces\Persistence\IConnectionFactory;
use Application\Interfaces\Persistence\Repositories\IPersonsRepository;
use Application\Interfaces\Services\IPersonsService;
use Application\Services\PersonsService;
use Infrastructure\Configuration\Configuration;
use Exception;
use Infrastructure\Files\CsvFileReader;
use Infrastructure\Persistence\ConnectionFactory;
use Infrastructure\Persistence\Repositories\PersonsRepository;

/**
 * Simple service provider. But it's better to use a normal DI !!!.
 */
class ServiceProvider {
    /**
     * @var array
     */
    private $serviceCollection;

    /**
     *
     */
    public function __construct() {
        $this->serviceCollection = array();
    }

    /**
     * @param string $serviceName
     * @param $serviceBuilder
     * @return void
     */
    public function add(string $serviceName, $serviceBuilder) {
        $this->serviceCollection[$serviceName] = $serviceBuilder;
    }

    /**
     * @param string $serviceName
     * @return void
     */
    public function remove(string $serviceName) {
        unset($this->serviceCollection[$serviceName]);
    }

    /**
     * @param string $serviceName
     * @return object|null
     */
    public function get(string $serviceName) {
        $service = $this->serviceCollection[$serviceName];
        if ($service) {
            return $service($this);
        }
        return null;
    }


    /**
     * @return ServiceProvider
     * @throws Exception
     */
    public static function create(): ServiceProvider {
        $provider = new self();

        $provider->add(IConfiguration::class, function () {
            return new Configuration();
        });
        $provider->add(ICsvFileReader::class, function () {
            return new CsvFileReader();
        });

        $provider->add(IConnectionFactory::class, function (ServiceProvider $provider) {
            $configuration = $provider->get(IConfiguration::class);

            /** @var IConfiguration $configuration */
            return new ConnectionFactory($configuration);
        });

        $provider->add(IPersonsRepository::class, function (ServiceProvider $provider) {
            $connectionFactory = $provider->get(IConnectionFactory::class);

            /** @var IConnectionFactory $connectionFactory */
            return new PersonsRepository($connectionFactory);
        });

        $provider->add(IPersonsService::class, function (ServiceProvider $provider) {
            $csvFileReader = $provider->get(ICsvFileReader::class);
            $personsRepository = $provider->get(IPersonsRepository::class);

            /** @var ICsvFileReader $csvFileReader */
            /** @var IPersonsRepository $personsRepository */
            return new PersonsService($csvFileReader, $personsRepository);
        });

        return $provider;
    }
}