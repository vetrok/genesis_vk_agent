<?php

namespace AppBundle\Model\VkApi;


class Common
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

    public function getUser($userId)
    {
        return $this->getVkApi()->api('users.get', [
            'user_id' => $userId,
            'access_token' => $this->getAccessToken()
        ]);
    }

    public function getAlbums($userId, $offset = 0, $limit = 200)
    {
        return $this->getVkApi()->api('photos.getAlbums', [
            'owner_id' => $userId,
            'offset' => $offset,
            'count' => $limit,
            'access_token' => $this->getAccessToken(),
        ]);
    }

    public function getImagesFromAlbum($userId, $albumId, $offset = 0, $limit = 200)
    {
        return $this->getVkApi()->api('photos.get', [
            'owner_id' => $userId,
            'album_id' => $albumId,
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
    public function setVkApi($vkApi)
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
     * @return mixed
     */
    public function getDefaultAlbums()
    {
        return $this->defaultAlbums;
    }

} 