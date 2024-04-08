<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Interfaces\Persistence\IConnectionFactory;
use Application\Interfaces\Persistence\Repositories\IPersonsRepository;
use Domain\Entities\Person;
use Exception;
use PDO;

/**
 *
 */
class PersonsRepository implements IPersonsRepository
{
    private static $BATCH_SIZE = 10;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param IConnectionFactory $connectionFactory
     * @throws Exception
     */
    public function __construct(IConnectionFactory $connectionFactory)
    {
        $this->pdo = $connectionFactory->create();
    }

    /**
     * @param array<Person> $persons
     * @return int
     * @throws Exception
     */
    public function addRange(array $persons): int
    {
        $result = 0;
        $personsCount = count($persons);

        $query = "INSERT INTO persons (name, age) VALUES(?, ?)";
        $stm = $this->pdo->prepare($query);

        for ($i = 0; $i < $personsCount; $i++) {
            $this->pdo->beginTransaction();
            $batchSize = PersonsRepository::$BATCH_SIZE;

            try {
                for ($j = $i; $j < $personsCount; $j++) {
                    if ($i++ >= $personsCount || $batchSize-- <= 0) {
                        break;
                    }
                    $stm->execute([$persons[$j]->name, $persons[$j]->age]);
                }
                $this->pdo->commit();
            } catch (Exception $ex) {
                $this->pdo->rollBack();
                throw $ex;
            }

            $result += PersonsRepository::$BATCH_SIZE - $batchSize;
        }

        return $result;
    }
}