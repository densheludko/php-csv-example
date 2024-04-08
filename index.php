<?php

spl_autoload_register(function ($className)
{
    $class_path = str_replace('\\', '/', $className);
    $file = __DIR__ . '/' . $class_path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Application\Interfaces\Services\IPersonsService;
use Application\ServiceProvider;

try {
    $provider = ServiceProvider::create();

    /** @var IPersonsService $personsService */
    $personsService = $provider->get(IPersonsService::class);

    $count = $personsService->importFromCsvFile("file.csv");
    var_dump($count);

} catch (Exception $e) {
    var_dump($e);
}

