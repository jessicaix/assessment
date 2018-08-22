<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Yaml;

class ThemeChangeCommand extends Command
{
    protected $consoleDirectory = null;

    public function __construct($consoleDirectory, $name = null)
    {
        parent::__construct($name);

        $this->consoleDirectory = $consoleDirectory;
    }

    protected function configure()
    {
        $this
            ->setName('ps:theme:change')
            ->setDescription('Change admin and default themes')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Change admin and default themes ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $ymlPath = sprintf('%s/config/system.theme.yml', $this->consoleDirectory);

        if (!is_file($ymlPath)) {
            $output->writeln(sprintf('<error>%s not found.</error>', $ymlPath));

            return 1;
        }

        $yml = Yaml::parse(file_get_contents($ymlPath));

        if (is_null($yml)) {
            $output->writeln(sprintf('<info>No config found.</info>', $ymlPath));

            return 0;
        }

        if (isset($yml['admin']) && is_string($yml['admin'])) {
            $output->writeln(sprintf('<info>Changing admin theme to %s</info>', $yml['admin']));

            $commandInput = new ArrayInput([
                'command' => 'config:override',
                'name' => 'system.theme',
                'key' => 'admin',
                'value' => $yml['admin'],
            ]);

            $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

            if ($returnCode) {
                return $returnCode;
            }
        }

        if (isset($yml['default']) && is_string($yml['default'])) {
            $output->writeln(sprintf('<info>Changing default theme to %s</info>', $yml['default']));

            $commandInput = new ArrayInput([
                'command' => 'config:override',
                'name' => 'system.theme',
                'key' => 'default',
                'value' => $yml['default'],
            ]);

            $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

            if ($returnCode) {
                return $returnCode;
            }
        }

        return 0;
    }
}
