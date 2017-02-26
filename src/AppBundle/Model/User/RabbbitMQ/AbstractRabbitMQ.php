<?php

namespace AppBundle\Model\User\RabbitMQ;


interface AbstractRabbitMQ
{
    public function produce($id);

    public function consume();
} 