<?php

namespace Files;

require_once 'Application\Interfaces\Files\ICsvFileReader.php';
require_once 'Infrastructure\Utils\CsvUtils.php';


use Exception;
use Interfaces\Files\ICsvFileReader;


/**
 * Class for working with csv files.
 */
class CsvFileReader implements ICsvFileReader {


    /**
     * @throws Exception
     */
    public function readToEnd(
        string $fileName,
        string $separator = ",",
        int    $skipRows = 0,
        int    $lineLength = 1000): array {

        $file = $this->open_file($fileName);
        $header = fgetcsv($file, $lineLength, $separator);

        \CsvUtils::skipRows($file, $skipRows, $lineLength);

        $result = array();

        while (($data = fgetcsv($file, $lineLength, $separator)) !== FALSE) {
            $row = array();
            foreach ($header as $key => $header_name) {
                $row[$header_name] = $data[$key];
            }
            $result[] = $row;
        }

        fclose($file);
        return $result;
    }

    /**
     * @param string $fileName
     * @return resource
     * @throws Exception
     */
    private function open_file(string $fileName) {
        $fileHandle = fopen($fileName, "r");
        if (!$fileHandle) {
            throw new Exception("Could not read file: " . $fileName);
        }
        return $fileHandle;
    }
}