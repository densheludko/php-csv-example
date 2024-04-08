<?php

namespace Infrastructure\Utils;

/**
 *
 */
class CsvUtils
{
    /**
     * @param resource $file
     * @param int $skipRows
     * @param string $separator
     * @param int $lineLength
     * @return void
     */
    public static function skipRows($file, int $skipRows, string $separator, int $lineLength = 1000)
    {
        while (true) {
            if ($skipRows-- <= 0 || !fgetcsv($file, $lineLength, $separator)) {
                break;
            }
        }
    }
}