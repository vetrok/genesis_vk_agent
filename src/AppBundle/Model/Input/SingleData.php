<?php

namespace AppBundle\Model\Input;

/**
 * Class SingleData
 *
 * Retrieve id from single value and returns it i appropriate format
 *
 * @package AppBundle\Model\Input
 */
class SingleData implements \AppBundle\Model\Input\AbstractInputData
{
    /**
     * Abstraction should return array, do it
     *
     * @param $input
     * @return array
     */
    public function retrieveAllIds($input)
    {
        return [$input];
    }
} 