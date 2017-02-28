<?php

namespace AppBundle\Model\User;

/**
 * Class RabbitMQVKUser
 *
 * Proxy for user import, it puts user to query
 * later user data will be imported to system
 *
 * @package AppBundle\Model\User
 */
class DbVkUser implements \AppBundle\Model\User\AbstractVKUser
{
    /**
     * @var \AppBundle\Model\VkApi\Common api data manipulator
     */
    protected $vkCommonApi;

    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    public function __construct(\AppBundle\Model\VkApi\Common $vkCommonApi, $em)
    {
        $this->setVkCommonApi($vkCommonApi);
        $this->setEm($em);
    }

    /**
     * Put user to query
     *
     * @param $id
     */
    public function importUserFacade($id)
    {
        $api = $this->getVkCommonApi();
        $userApi = $api->getUser(1)[0];
        $userDb = new \AppBundle\Entity\Users();
        $userDb->setVkId($userApi['id']);
        $userDb->setFirstName($userApi['first_name']);
        $userDb->setLastName($userApi['last_name']);
        $userDb->setScreenName('123');
        $userDb->setCreated('123');

        $em = $this->getEm();
        $em->persist($userDb);
        $em->flush();
    }

    /**
     * @return \AppBundle\Model\VkApi\Common
     */
    public function getVkCommonApi()
    {
        return $this->vkCommonApi;
    }

    /**
     * @param \AppBundle\Model\VkApi\Common $vkCommonApi
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


} 