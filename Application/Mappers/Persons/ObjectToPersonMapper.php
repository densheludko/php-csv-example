<?php

namespace Mappers\Persons;

use Domain\Entities\Person;


/**
 *
 */
class ObjectToPersonMapper {
    /**
     * @param array-key $object
     * @return Person
     */
    public static function map($object): Person {
        return new Person(intval($object["id"]), $object["name"], intval($object["age"]));
    }

    /**
     * @param array $rowPersons
     * @return array<Person>.
     */
    public static function mapArray(array $rowPersons): array {
        $persons = [];

        foreach ($rowPersons as $rowPerson) {
            $persons[] = ObjectToPersonMapper::map($rowPerson);
        }

        return $persons;
    }
}