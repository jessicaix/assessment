<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Tci\PsConsole\Env;

class ConfigExportCommand extends Command
{
    protected $consoleDirectory = null;

    protected $env = null;

    public function __construct($consoleDirectory, $name = null)
    {
        parent::__construct($name);

        $this->consoleDirectory = $consoleDirectory;
        $this->env = new Env();
    }

    protected function configure()
    {
        $this
            ->setName('ps:config:export')
            ->setDescription('Export config files')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Start exporting configs ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $commandInput = new ArrayInput([
            'command' => 'config_split:export',
            '-y' => true,
        ]);

        $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        if ($returnCode) {
            return $returnCode;
        }

        return 0;
    }
}
