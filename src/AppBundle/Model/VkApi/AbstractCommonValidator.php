<?php

namespace AppBundle\Model\VkApi;

/**
 * Interface AbstractCommon
 *
 * Api responses validator for common interface
 *
 * @see AppBundle\Model\VkApi\CommonAbstract
 *
 * @package AppBundle\Model\VkApi
 */
interface AbstractCommonValidator
{

    public function checkApiResponse($response);

    public function checkIsUserValid($apiResponse);

    public function areItemsExists($apiResponse);

    public function isUserDeactivated($apiResponse);
} 