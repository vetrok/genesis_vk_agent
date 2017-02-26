<?php

namespace AppBundle\Model\User;

class RabbitMQVKUser implements \AppBundle\Model\User\AbstractVKUser
{
    public function importUserFacade($id)
    {
        echo $id;
    }
} 