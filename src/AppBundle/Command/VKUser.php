<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class VKUser extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('vk:import_user')

            // the short description shown while running "php bin/console list"
            ->setDescription('VK user importer')

            ->addArgument('user_id', InputArgument::REQUIRED)

            ->addOption(
                'users_csv',
                null,
                InputArgument::OPTIONAL,
                'Flag for path to .csv file with id\'s',
                '0'
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
        //VK id can be int or string
        $userId = $input->getArgument('user_id');
        //Recognize input type and get values from it
        if ($input->getOption('users_csv') > 0) {
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
     */
    protected function saveUser($input, $idRetrieverName)
    {
        $serviceContainer = $this->getContainer();
        $idRetriever = $serviceContainer->get($idRetrieverName);
        $usersId = $idRetriever->retrieveAllIds($input);

        //TODO: validation open question - should validate on model or here?
        //Insert id's
        $vkUser = $this->getContainer()->get('vk.user');
        foreach ($usersId as $singleId) {

            $vkUser->importUserFacade($singleId);
        }
    }
} 