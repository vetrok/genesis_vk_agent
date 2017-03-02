<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ImportVkUser extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('vk:import_user')

            // the short description shown while running "php bin/console list"
            ->setDescription('VK user importer')

            ->addArgument('user_id', InputArgument::REQUIRED)

            ->addOption(
                'data_type',
                null,
                InputArgument::OPTIONAL,
                'Enter data type for user id\'s',
                'int'
            )

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Import VK user by ID, you can pass single id or .csv file with multiple id\'s')
        ;
    }

    /**
     * Perform routing
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $x = 'vk.import_user_callback';
        $serviceContainer = $this->getContainer();
        $idRetriever = $serviceContainer->get($x);

        $this->setOutput($output);
        //VK id can be int or string
        $userId = $input->getArgument('user_id');
        //Recognize input type and get values from it
        if ($input->getOption('data_type') == 'csv') {
            $this->parseCSVAction($userId);
        } else {
            $this->indexAction($userId);
        }
    }

    /**
     * Default action - parse user by userId
     *
     * @param $userId
     */
    public function indexAction($userId)
    {
        $idRetrieverName = 'vk.from_single';
        $this->saveUser($userId, $idRetrieverName);
    }

    /**
     * Save users ids from .csv file
     *
     * @param $pathToFile
     */
    public function parseCSVAction($pathToFile)
    {
        $idRetrieverName = 'vk.from_csv';
        $this->saveUser($pathToFile, $idRetrieverName);
    }

    /**
     * Save user with polymorph objects
     *
     * @param $input
     * @param $idRetrieverName
     * @return bool
     */
    protected function saveUser($input, $idRetrieverName)
    {
        $serviceContainer = $this->getContainer();
        $idRetriever = $serviceContainer->get($idRetrieverName);
        $usersIds = $idRetriever->retrieveAllIds($input);

        foreach ($usersIds as $singleId) {
            if (empty(trim($singleId))) {
                $this->getOutput()->writeln('Request contains empty ID values!');
                return;
            }
        }

        //Insert id's
        $vkUser = $serviceContainer->get('vk.rabbitmq_user');
        foreach ($usersIds as $singleId) {

            $vkUser->importUserFacade($singleId);
        }

        $this->getOutput()->writeln('Your request is handling');
        return true;
    }


    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
} 