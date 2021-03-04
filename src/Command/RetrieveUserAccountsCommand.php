<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Local\User as LocalUser;
use App\Entity\Remote\User as RemoteUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RetrieveUserAccountsCommand extends Command
{
    protected static $defaultName = 'app:users:retrieve';
    protected static $defaultDescription = 'Retrieve user accounts.';

    private EntityManagerInterface $localManager;
    private EntityManagerInterface $remoteManager;

    public function __construct(EntityManagerInterface $localManager, EntityManagerInterface $remoteManager)
    {
        parent::__construct(static::$defaultName);

        $this->localManager = $localManager;
        $this->remoteManager = $remoteManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var LocalUser $localUsers */
        $localUsers = $this->localManager->getRepository(LocalUser::class)->findOneBy([]);

        $io->definitionList(
            ['#' => $localUsers->getId()],
            ['Email' => $localUsers->getEmail()],
            ['Username' => $localUsers->getUsername()]
        );

        /** @var RemoteUser $remoteUsers */
        $remoteUsers = $this->remoteManager->getRepository(RemoteUser::class)->findOneBy([]);

        $io->definitionList(
            ['#' => $remoteUsers->getId()],
            ['Email' => $remoteUsers->getEmail()],
            ['Username' => $remoteUsers->getUsername()]
        );

        return Command::SUCCESS;
    }
}
