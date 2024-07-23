<?php

namespace Bamarni\Symfony\Console\Autocomplete;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DumpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dump')
            ->setDefinition(array(
                new InputArgument('script', InputArgument::OPTIONAL, "The script to generate completion for."),
                new InputOption('script-options', null, InputOption::VALUE_REQUIRED, "Options to be passed to the script."),
                new InputOption('aliases', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "Extra aliases to be used."),
                new InputOption('disable-default-tools', null, InputOption::VALUE_NONE),
                new InputOption('shell', null, InputOption::VALUE_REQUIRED, 'Shell type ("bash", "fish" or "zsh")', isset($_SERVER['SHELL']) ? basename($_SERVER['SHELL'], '.exe') : null),
            ))
            ->setDescription('Dumps shell autocompletion for any executable based on a Symfony Console Application.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shell = $input->getOption('shell');
        $script = $input->getArgument('script');

        if (!in_array($shell, array('bash', 'zsh', 'fish'))) {
            throw new InvalidArgumentException(sprintf(
                'Completion is only available for Bash, Fish and Zsh, "%s" given.',
                $shell
            ));
        }

        /* =====================================
         * DEFAULT SCRIPT
         * ===========================================
         */
        if (!$script) {
            if ($input->getOption('disable-default-tools')) {
                $tools = array();
            } else {
                $tools = array(
                    'console',
                    'acli',
                    'artisan',
                    'behat',
                    'composer',
                    'dep',
                    'magento',
                    'php-cs-fixer',
                    'phpspec',
                    'roadiz',
                    'robo',
                );
            }

            if ($extraTools = $input->getOption('aliases')) {
                $extraTools = array_filter(preg_split('/\s+/', implode(' ', $extraTools)));
                $tools = array_unique(array_merge($tools, $extraTools));
            }

            $output->write($this->render($shell . '/default', compact('tools')));

            return 0;
        }

        /* =====================================
         * STATIC SCRIPT
         * ===========================================
         */
        $scriptOptions = $input->getOption('script-options');

        // find all commands
        $command = $script . ' list ' . $scriptOptions . ' --format=xml';
        if (method_exists(Process::class, 'fromShellCommandline')) {
            // Symfony 4+
            $process = Process::fromShellCommandline($command);
        } else {
            // old Symfony way
            $process = new Process(explode(' ', $command));
        }
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $xmlCommands = $process->getOutput();
        $xml = simplexml_load_string($xmlCommands);

        $commands = array();
        $commandsDescriptions = array();
        $commandsOptionsDescriptions = array();
        // find all options
        $commandsOptions = array();
        $globalOptions = array();
        $optionsDescriptions = array();
        foreach ($xml->xpath('/symfony/commands/command') as $xmlCommand) {
            $command = (string) $xmlCommand['name'];
            $commands[] = $command;
            $commandsDescriptions[$command] = !empty($xmlCommand->description) ? strip_tags($xmlCommand->description) : null;
            $commandsOptionsDescriptions[$command] = array();
            $commandOptions = array();
            foreach ($xmlCommand->xpath('options/option') as $commandOption) {
                $name = (string)$commandOption['name'];
                $commandOptions[] = $name;
                $optionsDescriptions[$name] = $commandsOptionsDescriptions[$command][$name] = (string)$commandOption->description;
            }

            $commandsOptions[$command] = $commandOptions;
            if ('list' !== $command) {
                if (empty($globalOptions)) {
                    $globalOptions = $commandOptions;
                }

                $globalOptions = array_intersect($globalOptions, $commandOptions);
            }
        }

        $script = explode('/', $script);
        $script = array_pop($script);
        $tools = array($script);

        if ($extraTools = $input->getOption('aliases')) {
            $extraTools = array_filter(preg_split('/\s+/', implode(' ', $extraTools)));
            $tools = array_unique(array_merge($tools, $extraTools));
        }

        $output->write($this->render($shell . '/cached', array(
            'script' => $script,
            'options_global' => $globalOptions,
            'options_command' => $commandsOptions,
            'options_descriptions' => $optionsDescriptions,
            'commands' => $commands,
            'commands_descriptions' => $commandsDescriptions,
            'commands_options_descriptions' => $commandsOptionsDescriptions,
            'tools' => $tools,
        )));

        return 0;
    }

    private function render($template, $vars)
    {
        $helpers = array(
            'zsh_describe' => function($value, $description = null) {
                $value = '"'.str_replace(':', '\\:', $value);
                if (!empty($description)) {
                    $value .= ':'.escapeshellcmd($description);
                }

                return $value.'"';
            }
        );

        ob_start();

        include __DIR__.'/../resources/'.$template.'.php';

        return ob_get_clean();
    }
}
