<?php

namespace AppBundle\Model\VkApi;

use AppBundle\Exception\ApiResponseException;
use AppBundle\Exception\ApiUserDoesntExist;

/**
 * Class CommonValidator
 * @package AppBundle\Model\VkApi
 */
class CommonValidator implements AbstractCommonValidator
{
    /**
     * @param $response
     * @throws ApiResponseException
     */
    public function checkApiResponse($response)
    {
        if (isset($response['error'])) {
            throw new ApiResponseException('Api error :' . serialize($response));
        }
    }

    /**
     * Api can return deleted user or empty user - check it
     *
     * @param $apiResponse
     * @throws \AppBundle\Exception\ApiUserDoesntExist
     */
    public function checkIsUserValid($apiResponse)
    {
        $this->checkApiResponse($apiResponse);

        if (empty($apiResponse)) {

            throw new ApiUserDoesntExist('Can\'t find user : ' . serialize($apiResponse));
        }
    }

    public function isUserDeactivated($apiResponse)
    {
        if (isset($apiResponse[0]['deactivated'])) {

            return true;
        }

        return false;
    }

    /**
     * Check are items exists in api response
     *
     * @param $apiResponse
     * @return bool
     */
    public function areItemsExists($apiResponse)
    {
        $this->checkApiResponse($apiResponse);

        if (!empty($apiResponse['count']) and !empty($apiResponse['items'])) {

            return true;
        }

        return false;
    }
} 