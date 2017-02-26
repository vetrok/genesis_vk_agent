<?php

namespace AppBundle\Model\Input;

/**
 * Customer can input data in different format, this class provide polymorph methods for it
 *
 * Interface AbstractInputData
 * @package AppBundle\Model\Input
 */
interface AbstractInputData
{
    /**
     * @param $input
     * @return array
     */
    public function retrieveAllIds($input);
} 