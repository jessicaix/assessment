<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Yaml;
use Tci\PsConsole\Env;

class ThemeInstallCommand extends Command
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
            ->setName('ps:theme:install')
            ->setDescription('Install themes')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Start installing themes ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $ymlPath = sprintf('%s/themes.yml', $this->consoleDirectory);

        if (!is_file($ymlPath)) {
            $output->writeln(sprintf('<error>%s not found.</error>', $ymlPath));

            return 1;
        }

        $themes = Yaml::parse(file_get_contents($ymlPath));

        if (is_array($themes) === false || count($themes) === 0) {
            $output->writeln(sprintf('<info>Nothing to install.</info>', $ymlPath));

            return 0;
        }

        $output->writeln(sprintf('<info>Installing %s</info>', implode(', ', $themes)));

        $commandInput = new ArrayInput([
            'command' => 'theme:install',
            'theme' => $themes,
        ]);

        $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        if ($returnCode) {
            return $returnCode;
        }

        return 0;
    }
}
