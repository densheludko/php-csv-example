<?php

namespace Interfaces\Files;

use Exception;

interface ICsvFileReader {

    /**
     * @param string $fileName
     * @param string $separator
     * @param int $skipRows
     * @param int $lineLength
     * @return array
     * @throws Exception
     */
    public function readToEnd(
        string $fileName,
        string $separator = ",",
        int    $skipRows = 0,
        int    $lineLength = 1000): array;
}