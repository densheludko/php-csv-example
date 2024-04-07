<?php

namespace Interfaces\Services;

interface IPersonsService {
    /**
     * @param string $fileName
     * @param string $separator
     * @param int $skipRows
     * @param int $lintLength
     * @return mixed
     */
    public function importFromCsvFile(
        string $fileName,
        string $separator = ",",
        int    $skipRows = 0,
        int    $lintLength = 1000);
}