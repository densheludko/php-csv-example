<?php

require_once "Infrastructure\Files\CsvFileReader.php";
require_once "Application\Services\PersonsService.php";
require_once "Infrastructure\Configuration\Configuration.php";
require_once "Infrastructure\Persistence\ConnectionFactory.php";
require_once "Infrastructure\Persistence\Repositories\PersonsRepository.php";
require_once "Infrastructure\Persistence\Repositories\PersonsRepository.php";


use Configuration\Configuration;
use Files\CsvFileReader;
use Interfaces\Configuration\IConfiguration;
use Interfaces\Files\ICsvFileReader;
use Interfaces\Persistence\IConnectionFactory;
use Interfaces\Persistence\Repositories\IPersonsRepository;
use Interfaces\Services\IPersonsService;
use Persistence\ConnectionFactory;
use Persistence\Repositories\PersonsRepository;
use Services\PersonsService;


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
    public function get(string $serviceName): ?object {
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