<?php
namespace Tci\PsConsole\Command;

use Drupal;
use Drupal\Console\Core\Command\Command;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Yaml;
use Tci\PsConsole\Env;

class ModuleInstallCommand extends Command
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
            ->setName('ps:module:install')
            ->setDescription('Install module with dependencies')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Start installing modules ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $ymlPath = sprintf('%s/modules.yml', $this->consoleDirectory);

        if (!is_file($ymlPath)) {
            $output->writeln(sprintf('<error>%s not found.</error>', $ymlPath));

            return 1;
        }

        $modules = $this->getInstallModules($ymlPath);

        if (count($modules) === 0) {
            $output->writeln('<info>Nothing to install.</info>');

            return 0;
        }

        $output->writeln(sprintf('<info>Installing %s</info>', implode(', ', $modules)));

        $commandInput = new ArrayInput([
            'command' => 'module:install',
            'module' => $modules,
        ]);

        $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        if ($returnCode) {
            return $returnCode;
        }

        return 0;
    }

    protected function getInstallModules($ymlPath)
    {
        $modules = Yaml::parse(file_get_contents($ymlPath));

        if (is_null($modules)) {
            return [];
        }

        $installedModules = array_keys(Drupal::moduleHandler()->getModuleList());

        return array_diff($modules, $installedModules);
    }
}
