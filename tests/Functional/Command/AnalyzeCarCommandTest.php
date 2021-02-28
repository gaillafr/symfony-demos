<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AnalyzeCarCommandTest extends KernelTestCase
{
    public function testCommand(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:car:analyze');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        self::assertStringContainsString('Brand', $output);
        self::assertStringContainsString('Color', $output);
        self::assertStringContainsString('Model', $output);
    }
}
