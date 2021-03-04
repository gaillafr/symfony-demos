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

class CreateUserAccountsCommand extends Command
{
    private const SAMPLE_USER_DATA = [
        'local' => [
            'email' => 'local_user@example.com',
            'username' => 'local_user',
        ],
        'remote' => [
            'email' => 'remote_user@example.com',
            'username' => 'remote_user',
        ],
    ];

    protected static $defaultName = 'app:users:create';
    protected static $defaultDescription = 'Create some user accounts.';

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

        // Do not forget to run "bin/console doctrine:schema:update --force --em=local"
        $localUser = $this->createLocalUserFromData(self::SAMPLE_USER_DATA['local']);
        $this->localManager->persist($localUser);
        $this->localManager->flush();

        // Do not forget to run "bin/console doctrine:schema:update --force --em=remote"
        $remoteUser = $this->createRemoteUserFromData(self::SAMPLE_USER_DATA['remote']);
        $this->remoteManager->persist($remoteUser);
        $this->remoteManager->flush();

        $io->success('User accounts created.');

        return Command::SUCCESS;
    }

    private function createLocalUserFromData(array $data): LocalUser
    {
        return (new LocalUser())
            ->setEmail($data['email'])
            ->setUsername($data['username'])
        ;
    }

    private function createRemoteUserFromData(array $data): RemoteUser
    {
        return (new RemoteUser())
            ->setEmail($data['email'])
            ->setUsername($data['username'])
        ;
    }
}
