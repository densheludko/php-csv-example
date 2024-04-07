<?php

namespace Interfaces\Persistence\Repositories;


use Domain\Entities\Person;

/**
 * Persons repository.
 */
interface IPersonsRepository {
    /**
     * @param Person[] $persons New persons.
     * @return int Count of persons saved.
     */
    public function addRange(array $persons): int;
}