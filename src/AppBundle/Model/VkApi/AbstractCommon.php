<?php

namespace AppBundle\Model\VkApi;

/**
 * Interface AbstractCommon
 *
 * Api interface for common endpoints
 *
 * @package AppBundle\Model\VkApi
 */
interface AbstractCommon
{
    public function getUser($userId);

    public function getAlbums($userId, $offset, $limit);

    public function getPhotosFromAlbum($userId, $albumId, $offset, $limit);

    public function getVkApi();

    public function setAccessToken($accessToken);

    public function getDefaultAlbums();
} 