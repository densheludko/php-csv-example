<?php

namespace Application\Services;

use Application\Interfaces\Files\ICsvFileReader;
use Application\Interfaces\Persistence\Repositories\IPersonsRepository;
use Application\Interfaces\Services\IPersonsService;
use Application\Mappers\Persons\ObjectToPersonMapper;
use Exception;

/**
 *
 */
class PersonsService implements IPersonsService {

    /**
     * @var ICsvFileReader
     */
    private $csvFileReader;

    /**
     * @var IPersonsRepository
     */
    private $repository;

    /**
     * @param ICsvFileReader $csvFileReader
     * @param IPersonsRepository $repository
     */
    public function __construct(ICsvFileReader $csvFileReader, IPersonsRepository $repository) {
        $this->csvFileReader = $csvFileReader;
        $this->repository = $repository;
    }

    /**
     * @param string $fileName
     * @param string $separator
     * @param int $skipRows How many rows will be skipped
     * @param int $lintLength
     * @return int Count of persons saved.
     * @throws Exception
     */
    public function importFromCsvFile(
        string $fileName,
        string $separator = ",",
        int    $skipRows = 0,
        int    $lintLength = 1000): int {

        $rowPersons = $this->csvFileReader->readToEnd($fileName, $separator, $skipRows, $lintLength);
        $persons = ObjectToPersonMapper::mapArray($rowPersons);

        return $this->repository->addRange($persons);
    }
}