<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GetVkUserPhotos extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('vk:get_user_photos')

            // the short description shown while running "php bin/console list"
            ->setDescription('Retrieve user photos')

            ->addArgument('user_id', InputArgument::REQUIRED)

            ->addOption(
                'album_id',
                null,
                InputArgument::OPTIONAL,
                //Default ID for 'wall' album
                -7
            )

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
     * Find user photos in album
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('user_id');
        $albumId = $input->getOption('album_id');
        $limit = $input->getOption('limit');
        $offset = $input->getOption('offset');

        //Find photos in user album
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $repo = $em->getRepository('AppBundle:Photos');
        $query = $repo->createQueryBuilder('p')
            ->innerJoin('AppBundle:Albums', 'a', 'WITH', 'a.id = p.album')
            ->innerJoin('AppBundle:Users', 'u', 'WITH', 'a.user = u.id')
            ->andWhere('u.vkId = ?1')
            ->andWhere('a.vkId = ?2')
            ->setParameter('1', $userId)
            ->setParameter('2', $albumId)
            ->orderBy('p.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $result = $query->getResult();

        $this->drawView($output, $result);
    }

    /**
     * Console view
     * @param $output
     * @param $result
     */
    public function drawView($output, $result)
    {
        if (empty($result)) {
            $output->writeln('<error>No results found</error>');
            return;
        }

        foreach ($result as $singlePhoto) {
            $output->writeln('');
            $output->writeln('<info>Vk photo id: </info>' . $singlePhoto->getVkId());
            $output->writeln('<info>Photos');
            $photoSizes = $singlePhoto->getPhotoSizes();
            if (!$photoSizes->isEmpty()) {
                foreach ($singlePhoto->getPhotoSizes() as $singlePhotoSize) {
                    $output->writeln(
                        '<info>Photo size: ' . $singlePhotoSize->getType() .
                        ', link: ' . $singlePhotoSize->getLink() . '</info>'
                    );
                }
            }
            $output->writeln('</info>');
        }
    }
} 