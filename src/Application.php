<?php

namespace Bamarni\Symfony\Console\Autocomplete;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class Application extends BaseApplication
{
    private $command;

    public function __construct(Command $command = null)
    {
        $this->command = $command ?? new DumpCommand;

        parent::__construct();
    }

    protected function getCommandName(InputInterface $input): ?string
    {
        return $this->command->getName();
    }

    protected function getDefaultCommands(): array
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = $this->command;

        return $defaultCommands;
    }

    public function getDefinition(): InputDefinition
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
