<?php

namespace AppBundle\Model\Input;

use AppBundle\Exception\CsvParseException;
use Symfony\Component\Finder\Finder;

/**
 * Class CSVData
 *
 * Get data from csv format
 *
 * @package AppBundle\Model\Input
 */
class CSVData implements \AppBundle\Model\Input\AbstractInputData
{
    /**
     * Validate file and parse data from it
     *
     * @param $input
     * @throws \AppBundle\Exception\CsvParseException
     * @return array
     */
    public function retrieveAllIds($input)
    {
        if (!file_exists($input)) {

            throw new CsvParseException('File doesn\'t exist by path: ' . serialize($input));
        }
        if (!is_readable($input)) {
            throw new CsvParseException('Can\'t read file: ' . serialize($input));
        }
        if (filesize($input) == 0) {
            throw new CsvParseException('File is empty: ' . serialize($input));
        }

        $idsArray = str_getcsv(file_get_contents($input));

        return $idsArray;
    }
} 