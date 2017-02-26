<?php

namespace AppBundle\Model\Input;

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
    public function retrieveAllIds($input)
    {
        if (!file_exists($input)) {
            throw new \Exception('File doesn\'t exist');
        }
        if (!is_readable($input)) {
            throw new \Exception('Can\'t read file');
        }
        if (filesize($input) == 0) {
            throw new \Exception('File is empty');
        }

        $idsArray = str_getcsv(file_get_contents($input));

        return $idsArray;
    }
} 