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
    /**
     * Validate file and parse data from it
     *
     * @param $input
     * @return array
     * @throws \Exception
     */
    public function retrieveAllIds($input)
    {
        if (!file_exists($input)) {

            throw new \ParseError('File doesn\'t exist');
        }
        if (!is_readable($input)) {
            throw new \ParseError('Can\'t read file');
        }
        if (filesize($input) == 0) {
            throw new \ParseError('File is empty');
        }

        $idsArray = str_getcsv(file_get_contents($input));

        //TODO: check is array has empty values

        return $idsArray;
    }
} 