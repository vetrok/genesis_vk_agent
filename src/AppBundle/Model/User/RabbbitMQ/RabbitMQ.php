<?php

namespace AppBundle\Model\User\RabbitMQ;

class RabbitMQ implements AbstractRabbitMQ
{
    public function produce($id)
    {
        echo $id;
    }

    public function consume()
    {

    }
} 