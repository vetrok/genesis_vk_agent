<?php

namespace AppBundle\Consumer;

use AppBundle\Exception\ApiUserDoesntExist;
use AppBundle\Model\User\AbstractVkUser;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bridge\Monolog\Logger;

class UserConsumer implements ConsumerInterface
{
    /**
     * @var AbstractVkUser user importer class
     */
    protected $importer;

    /**
     * @var \Symfony\Bridge\Monolog\Logger logger instance
     */
    protected $logger;

    public function __construct(AbstractVkUser $userImporter, Logger $logger)
    {
        $this->setImporter($userImporter);
        $this->setLogger($logger);
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $this->getImporter()->importUserFacade($msg->body);
            return true;
        } catch (ApiUserDoesntExist $e) {
            $this->getLogger()->error($e->getMessage());

            return true;
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage() . ' Data put in queue again');

            //All other cases - put ID to queue again
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

    /**
     * @return \Symfony\Bridge\Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

}