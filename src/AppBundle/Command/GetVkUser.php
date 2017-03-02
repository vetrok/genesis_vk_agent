<?php

namespace AppBundle\Command;

use AppBundle\Exception\InputBadData;
use AppBundle\Exception\InputBadDataException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GetVkUser extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('vk:get_user')

            // the short description shown while running "php bin/console list"
            ->setDescription('Retrieve user data')

            ->addArgument('user_id', InputArgument::REQUIRED)
        ;
    }

    /**
     * Retrieve user
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \AppBundle\Exception\InputBadDataException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('user_id');
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $repo = $em->getRepository('AppBundle:Users');
        $query = $repo->createQueryBuilder('u');

        $result = $query->orWhere('u.vkId = ?1')
            ->orWhere('u.screenName = ?2')
            ->setParameter('1', $userId)
            ->setParameter('2', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $this->drawView($output, $result);
    }

    /**
     * Console view
     *
     * @param $output
     * @param $result
     */
    public function drawView($output, $result)
    {
        if (empty($result)) {
            $output->writeln('<error>User was not found</error>');
        } else {
            $userData = $result[0];
            $output->writeln('<info>User id: </info>' . $userData->getVkId());
            $output->writeln('<info>First name: </info>' . $userData->getFirstName());
            $output->writeln('<info>Last name: </info>' . $userData->getLastName());
            $output->writeln('<info>Screen name: </info>' . $userData->getScreenName());
        }
    }
} 