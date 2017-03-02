<?php

namespace AppBundle\Consumer;

use AppBundle\Exception\ApiUserDoesntExist;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class UserConsumer implements ConsumerInterface
{
    /**
     * @var \AppBundle\Model\User\AbstractVkUser user importer class
     */
    protected $importer;

    public function __construct(\AppBundle\Model\User\AbstractVkUser $userImporter, $logger = 1)
    {
        $this->setImporter($userImporter);
    }

    public function test()
    {
        $x = 22;
    }

    public function execute(AMQPMessage $msg)
    {
        file_put_contents(__DIR__ . '/'.microtime().'.txt', date('r') . '  ' . $msg->body);
        //Process picture upload.
        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.

//        $isUploadSuccess = someUploadPictureMethod();
//        if (!$isUploadSuccess) {
//            // If your image upload failed due to a temporary error you can return false
//            // from your callback so the message will be rejected by the consumer and
//            // requeued by RabbitMQ.
//            // Any other value not equal to false will acknowledge the message and remove it
//            // from the queue
//            return false;
//        }

        //TODO: write exception to log
        try {
            $this->getImporter()->importUserFacade($msg->body);
            return true;
        } catch (ApiUserDoesntExist $e) {
            return true;
        } finally {
            //All other cases are put ID to queue again
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getImporter()
    {
        return $this->importer;
    }

    /**
     * @param mixed $importer
     */
    public function setImporter($importer)
    {
        $this->importer = $importer;
    }
}