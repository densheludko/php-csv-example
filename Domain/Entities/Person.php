<?php

namespace Domain\Entities;

class Person {
    public $id;
    public $name;
    public $age;

    /**
     * @param int $id
     * @param string $name
     * @param int $age
     */
    public function __construct(int $id, string $name, int $age) {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }


}