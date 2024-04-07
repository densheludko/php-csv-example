<?php

use Interfaces\Services\IPersonsService;

require_once "Application\ServiceProvider.php";


try {
    $provider = ServiceProvider::create();

    /** @var IPersonsService $personsService */
    $personsService = $provider->get(IPersonsService::class);

    $count = $personsService->importFromCsvFile("file.csv");
    var_dump($count);

} catch (Exception $e) {
    var_dump($e);
}

