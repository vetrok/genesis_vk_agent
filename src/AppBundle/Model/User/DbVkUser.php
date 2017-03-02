<?php

namespace AppBundle\Model\User;

use AppBundle\Entity\Albums;
use AppBundle\Entity\Photos;
use AppBundle\Entity\PhotoSizes;
use AppBundle\Entity\Users;
use AppBundle\Model\User\AbstractVkUser;
use AppBundle\Model\VkApi\Common;
use AppBundle\Model\VkApi\CommonValidator;

/**
 * Class RabbitMQVKUser
 *
 * Proxy for user import, it puts user to query
 * later user data will be imported to system
 *
 * @package AppBundle\Model\User
 */
class DbVkUser implements AbstractVkUser
{
    /**
     * @var Common api data manipulator
     */
    protected $vkCommonApi;

    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    /**
     * @var CommonValidator
     */
    protected $vkApiValidator;

    /**
     * @var int
     */
    protected $offsetForApiResponses = 200;

    public function __construct(Common $vkCommonApi, $em, CommonValidator $vkApiValidator)
    {
        $this->setVkCommonApi($vkCommonApi);
        $this->setEm($em);
        $this->setVkApiValidator($vkApiValidator);
    }

    /**
     * Put user to query
     *
     * @param $id
     * @return bool
     */
    public function importUserFacade($id)
    {
        $api = $this->getVkCommonApi();
        $apiResponseUser = $api->getUser($id);
        $apiValidator = $this->getVkApiValidator();
        $apiValidator->checkIsUserValid($apiResponseUser);

        //Delete user
        $em = $this->getEm();
        $em->getConnection()->beginTransaction();
        $userOldDb = $em->getRepository('\AppBundle\Entity\Users')->findBy(['vkId' => $id]);
        if (!empty($userOldDb)) {
            //I'm sure that only one user can be found, because DB constraint
            $em->remove($userOldDb[0]);
            $em->flush();
        }

        //If user was in my DB and now he is deactivated - del user
        if (!$apiValidator->isUserDeactivated($apiResponseUser)) {
            $em->getConnection()->commit();
            return true;
        }

        $userApi = $apiResponseUser[0];
        //Create new user
        $userDb = new Users();
        $vkUserId = $userApi['id'];
        $userDb->setVkId($vkUserId);
        $userDb->setFirstName($userApi['first_name']);
        $userDb->setLastName($userApi['last_name']);
        $userDb->setScreenName($userApi['screen_name']);
        $em->persist($userDb);

        //Get user albums through cicle
        $keyForItemsInApiResponse = 'items';
        $stopFlagAlbums = true;
        for ($albumOffset = 0; $stopFlagAlbums; $albumOffset+= $this->getOffsetForApiResponses()) {
            $apiResponseAlbums = $api->getAlbums($vkUserId, $albumOffset);
            if (!$apiValidator->areItemsExists($apiResponseAlbums)) {

                //Add default albums and after that stop album seeding
                $apiResponseAlbums[$keyForItemsInApiResponse] = $api->getDefaultAlbums();
                $stopFlagAlbums = false;
            }

            foreach ($apiResponseAlbums[$keyForItemsInApiResponse ] as $singleAlbum) {
                //Create new album
                $albumDb = new Albums();
                $albumDb->setUser($userDb);
                $albumDb->setCreated($singleAlbum['created']);
                $albumDb->setTitle($singleAlbum['title']);
                $albumDb->setVkId($singleAlbum['id']);
                $em = $this->getEm();
                $em->persist($albumDb);

                $vkAlbumId = $singleAlbum['id'];
                for ($photoOffset = 0;;$photoOffset += $this->getOffsetForApiResponses()) {
                    $apiResponsePhotos = $api->getPhotosFromAlbum(
                        $vkUserId,
                        $vkAlbumId,
                        $photoOffset
                    );

                    if (!$apiValidator->areItemsExists($apiResponsePhotos)) {
                        break;
                    }

                    foreach ($apiResponsePhotos[$keyForItemsInApiResponse ] as $singlePhotoApi) {
                        $photoDb = new Photos();
                        $photoDb->setAlbum($albumDb);
                        $photoDb->setVkId($singlePhotoApi['id']);
                        $photoDb->setCreated($singlePhotoApi['date']);
                        $em->persist($photoDb);

                        //Every photo has few sizes - create new photo sizes
                        foreach ($singlePhotoApi['sizes'] as $singlePhotoSize) {
                            $photoSizeDb = new PhotoSizes();
                            $photoSizeDb->setPhoto($photoDb);
                            $photoSizeDb->setLink($singlePhotoSize['src']);
                            $photoSizeDb->setType($singlePhotoSize['type']);
                            $em->persist($photoSizeDb);
                        }
                    }
                }
            }
        }

        $em->flush();
        $em->getConnection()->commit();

        return true;
    }

    /**
     * @return Common
     */
    public function getVkCommonApi()
    {
        return $this->vkCommonApi;
    }

    /**
     * @param Common $vkCommonApi
     */
    public function setVkCommonApi($vkCommonApi)
    {
        $this->vkCommonApi = $vkCommonApi;
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return \AppBundle\Model\VkApi\CommonValidator
     */
    public function getVkApiValidator()
    {
        return $this->vkApiValidator;
    }

    /**
     * @param \AppBundle\Model\VkApi\CommonValidator $vkApiValidator
     */
    public function setVkApiValidator($vkApiValidator)
    {
        $this->vkApiValidator = $vkApiValidator;
    }

    /**
     * @return int
     */
    public function getOffsetForApiResponses()
    {
        return $this->offsetForApiResponses;
    }

    /**
     * @param int $offsetForApiResponses
     */
    public function setOffsetForApiResponses($offsetForApiResponses)
    {
        $this->offsetForApiResponses = $offsetForApiResponses;
    }
} 