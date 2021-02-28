<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Service\DataTransformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserTransformerCommand extends Command
{
    private const SAMPLE_USER_DATA = [
        'email' => 'j.doe@example.com',
        'username' => 'john.doe',
    ];

    protected static $defaultName = 'app:transform';
    protected static $defaultDescription = 'Transform some demo data.';

    private DataTransformer $transformer;

    public function __construct(DataTransformer $transformer)
    {
        parent::__construct(static::$defaultName);

        $this->transformer = $transformer;
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

        /** @var User $user */
        $user = $this->transformer->transform(static::SAMPLE_USER_DATA, User::class);

        $io->definitionList(
            ['#' => $user->getId()],
            ['Email' => $user->getEmail()],
            ['Username' => $user->getUsername()]
        );

        return Command::SUCCESS;
    }
}
