<?php

namespace AppBundle\Model\VkApi;

/**
 * Class Common
 *
 * Adapter for VK api
 *
 * @package AppBundle\Model\VkApi
 */
class Common implements AbstractCommon
{
    /**
     * @var token for pi access
     */
    protected $accessToken;
    /**
     * @var api manipulation object
     */
    protected $vkApi;
    /**
     * @var array vk has few default album names, which has every customer
     */
    protected $defaultAlbums = ['wall', 'profile ', 'saved'];

    public function __construct(\BW\Vkontakte $vk, $accessToken)
    {
        $this->setAccessToken($accessToken);
        $this->setVkApi($vk);
    }

    /**
     * Returns response from api for user data
     *
     * @param $userId
     * @return mixed
     */
    public function getUser($userId)
    {
        $apiResponse = $this->getVkApi()->api('users.get', [
            'user_ids' => $userId,
            'fields' => 'screen_name,',
            'access_token' => $this->getAccessToken()
        ]);

        return $apiResponse;
    }

    /**
     * Returns response from api for user albums data
     *
     * @param $userId
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getAlbums($userId, $offset = 0, $limit = 200)
    {
        return $this->getVkApi()->api('photos.getAlbums', [
            'owner_id' => $userId,
            'offset' => $offset,
            'count' => $limit,
            'access_token' => $this->getAccessToken(),
        ]);
    }

    /**
     * Returns response from api for user photos data
     *
     * @param $userId
     * @param $albumId
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getPhotosFromAlbum($userId, $albumId, $offset = 0, $limit = 200)
    {
        return $this->getVkApi()->api('photos.get', [
            'owner_id' => $userId,
            'album_id' => $albumId,
            'photo_sizes' => 1,
            'offset' => $offset,
            'count' => $limit,
            'access_token' => $this->getAccessToken(),
        ]);
    }

    /**
     * @return mixed
     */
    public function getVkApi()
    {
        return $this->vkApi;
    }

    /**
     * @param mixed $vkApi
     */
    protected function setVkApi($vkApi)
    {
        $this->vkApi = $vkApi;
    }

    protected function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Every user has list of default albums
     *
     * @return mixed
     */
    public function getDefaultAlbums()
    {
        return [
            [
                'id' => -7,
                'title' => 'wall',
                'created' => 0
            ],
            [
                'id' => -6,
                'title' => 'profile',
                'created' => 0
            ],
        ];
    }
} 