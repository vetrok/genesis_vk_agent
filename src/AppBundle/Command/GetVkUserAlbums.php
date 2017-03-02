<?php

namespace AppBundle\Command;

use AppBundle\Exception\InputBadDataException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GetVkUserAlbums extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('vk:get_user_albums')

            // the short description shown while running "php bin/console list"
            ->setDescription('Retrieve user albums')

            ->addArgument('user_id', InputArgument::REQUIRED)

            ->addOption(
                'limit',
                null,
                InputArgument::OPTIONAL,
                'Limit displayed records',
                10
            )
            ->addOption(
                'offset',
                null,
                InputArgument::OPTIONAL,
                'Offset for records',
                0
            )
        ;
    }

    /**
     * Retrieve user albums
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('user_id');
        $limit = $input->getOption('limit');
        $offset = $input->getOption('offset');

        $userResult = $this->getContainer()
            ->get("doctrine.orm.entity_manager")
            ->getRepository('AppBundle:Users')
            ->findOneBy(['vkId' => $userId]);

        if (empty($userResult)) {
            $output->writeln('<error>User was not found</error>');
            return;
        }

        $albumsCollection = $userResult->getAlbums()->slice($offset, $limit);
        $totalAlbums = $userResult->getAlbums()->count();
        $this->drawView($output, $albumsCollection, $totalAlbums);
    }

    /**
     * Console view
     * @param $output
     * @param $albumsCollection
     * @param $totalAlbums
     */
    public function drawView($output, $albumsCollection, $totalAlbums)
    {
        $output->writeln('Total albums: ' . $totalAlbums);
        foreach ($albumsCollection as $singleAlbum) {
            $output->writeln('');
            $output->writeln('<info>Album id: </info>' . $singleAlbum->getVkId());
            $output->writeln('<info>Title: </info>' . $singleAlbum->getTitle());
            $output->writeln('<info>Photos in album: </info>' . $singleAlbum->getPhotos()->count());
        }
    }
}