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
class RabbitMQVkUser implements \AppBundle\Model\User\AbstractVkUser
{
    /**
     * @var RabbitMQ producer object which puts data to query
     */
    protected $rabbitMQProducer;

    public function __construct($producer)
    {
        $this->setRabbitMQProducer($producer);
    }

    /**
     * Put user to query
     *
     * @param $id
     */
    public function importUserFacade($id)
    {
        $this->getRabbitMQProducer()->publish($id);
    }

    /**
     * @return mixed
     */
    public function getRabbitMQProducer()
    {
        return $this->rabbitMQProducer;
    }

    /**
     * @param mixed $rabbitMQProducer
     */
    public function setRabbitMQProducer($rabbitMQProducer)
    {
        $this->rabbitMQProducer = $rabbitMQProducer;
    }
} 