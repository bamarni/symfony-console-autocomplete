<?php

namespace Bamarni\Symfony\Console\Autocomplete;

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
                new InputOption('shell', null, InputOption::VALUE_REQUIRED, 'Shell type ("bash", "fish" or "zsh")', basename($_SERVER['SHELL'])),
            ))
            ->setDescription('Dumps shell autocompletion for any executable based on a Symfony Console Application.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shell = $input->getOption('shell');
        $script = $input->getArgument('script');

        if (!in_array($shell, array('bash', 'zsh', 'fish'))) {
            throw new \InvalidArgumentException(sprintf(
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
                    'composer',
                    'php-cs-fixer',
                    'phpspec',
                    'behat',
                );
            }

            if ($extraTools = $input->getOption('aliases')) {
                $extraTools = array_filter(preg_split('/\s+/', implode(' ', $extraTools)));
                $tools = array_unique(array_merge($tools, $extraTools));
            }

            $output->write($this->render($shell . '/default', compact('tools')));

            return;
        }

        /* =====================================
         * STATIC SCRIPT
         * ===========================================
         */
        $scriptOptions = $input->getOption('script-options');

        // find all commands
        $process = new Process($script . ' ' . $scriptOptions . ' list --raw | awk \'{if (NF>1) print $1 " " substr($0, index($0,$2)); else print $1}\'');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $rawCommands = explode("\n", $process->getOutput());
        array_pop($rawCommands);

        $commands = array();
        $commandsDescriptions = array();
        $commandsOptionsDescriptions = array();
        foreach ($rawCommands as $rawCommand) {
            $rawCommand = explode(' ', $rawCommand, 2);
            $commands[] = $rawCommand[0];
            $commandsDescriptions[$rawCommand[0]] = !empty($rawCommand[1]) ? $rawCommand[1] : null;
            $commandsOptionsDescriptions[$rawCommand[0]] = array();
        }

        // find all options
        $commandsOptions = array();
        $globalOptions = array();
        $optionsDescriptions = array();
        foreach ($commands as $command) {
            // get command help as xml
            $process = new Process($script . ' ' . $scriptOptions . ' help --format=xml ' . $command);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
            $xmlHelp = $process->getOutput();

            // extract options from xml help
            $commandOptions = array();
            $xml = simplexml_load_string($xmlHelp);
            foreach ($xml->xpath('/command/options/option') as $commandOption) {
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
