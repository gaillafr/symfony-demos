<?php

declare(strict_types=1);

namespace App\Command;

use App\Contract\MechanicInterface;
use App\Entity\Car;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AnalyzeCarCommand extends Command
{
    private const SAMPLE_CAR_DATA = [
        'brand' => 'Delta',
        'color' => 'Blue',
        'model' => 'G12',
    ];

    protected static $defaultName = 'app:car:analyze';
    protected static $defaultDescription = 'Get car information.';

    private MechanicInterface $mechanic;

    public function __construct(MechanicInterface $mechanic)
    {
        parent::__construct(static::$defaultName);

        $this->mechanic = $mechanic;
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

        $car = (new Car())
            ->setBrand(self::SAMPLE_CAR_DATA['brand'])
            ->setColor(self::SAMPLE_CAR_DATA['color'])
            ->setModel(self::SAMPLE_CAR_DATA['model'])
        ;

        $details = $this->mechanic->analyze($car);

        $io->definitionList(
            ['#' => $details['#']],
            ['Brand' => $details['Brand']],
            ['Color' => $details['Color']],
            ['Model' => $details['Model']],
        );

        return Command::SUCCESS;
    }
}
