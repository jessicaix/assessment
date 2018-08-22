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

class ConfigImportCommand extends Command
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
            ->setName('ps:config:import')
            ->setDescription('Import config files')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Start importing configs ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        // create temporary directory to copy common and env specific YML files together.
        //
        $tmpDir = sprintf('%s/_tmp', realpath(sprintf('%s/../config', $this->consoleDirectory)));

        if (is_dir($tmpDir)) {
            foreach (new \DirectoryIterator($tmpDir) as $fileInfo) {
                if ($fileInfo->isFile()) {
                    unlink($fileInfo->getPathname());
                }
            }
            rmdir($tmpDir);
        }

        mkdir($tmpDir);

        $files = array_merge($this->commonFiles(), $this->envFiles());

        foreach ($files as $file) {
            $fileInfo = new \SplFileInfo($file);

            copy($file, sprintf('%s/%s', $tmpDir, $fileInfo->getBasename()));
        }

        $commandInput = new ArrayInput([
            'command' => 'config:import',
            '--directory' => $tmpDir,
            '--skip-uuid' => true,
        ]);

        $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        // clean up temporary directory.
        //
        foreach (new \DirectoryIterator($tmpDir) as $fileInfo) {
            if ($fileInfo->isFile()) {
                unlink($fileInfo->getPathname());
            }
        }
        rmdir($tmpDir);

        if ($returnCode) {
            return $returnCode;
        }

        $returnCode = $this->executeEnvSpecificConfigOverride($output);

        if ($returnCode) {
            return $returnCode;
        }

        return 0;
    }

    protected function commonFiles()
    {
        $result = [];

        $fileDir = realpath(sprintf('%s/../config/sync', $this->consoleDirectory));

        foreach (new \DirectoryIterator($fileDir) as $fileInfo) {
            if ($fileInfo->getExtension() === 'yml') {
                $result[] = $fileInfo->getPathname();
            }
        }

        return $result;
    }

    protected function envFiles()
    {
        $result = [];

        $fileDir = realpath(sprintf('%s/../config/%s', $this->consoleDirectory, $this->env->get('ENVIRONMENT')));

        foreach (new \DirectoryIterator($fileDir) as $fileInfo) {
            if ($fileInfo->getExtension() === 'yml') {
                $result[] = $fileInfo->getPathname();
            }
        }

        return $result;
    }

    /**
     * Env specific YAML override files are stored in config_override/[env]
     *
     * If directory not created or there is no YAML file,
     * the system does nothing.
     *
     * WARNING:
     * The system invokes command with '--no-interaction' to skip checking value.
     * But only 'name' is checked before invoking command.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function executeEnvSpecificConfigOverride(OutputInterface $output)
    {
        $fileDir = realpath(sprintf('%s/../config_override/%s', $this->consoleDirectory, $this->env->get('ENVIRONMENT')));

        if (is_dir($fileDir) === false) {
            return 0;
        }

        $yamlFiles = [];

        foreach (new \DirectoryIterator($fileDir) as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() === 'yml') {
                $yamlFiles[] = new \SplFileInfo($fileInfo->getPathname());
            }
        }

        $configFactory = $this->getApplication()->getDrupalApplication()->getContainer()->get('config.factory');

        $configNames = $configFactory->listAll();

        foreach ($yamlFiles as $yamlFile) {
            $name = $yamlFile->getBasename('.yml');

            if (in_array($name, $configNames, true) === false) {
                $output->writeln(sprintf('<error>config name not found: %s, override skipped.</error>', $name));

                continue;
            }

            $yml = Yaml::parse(file_get_contents($yamlFile->getPathname()));

            if (is_array($yml) === false) {
                continue;
            }

            $config = $configFactory->get($name);

            foreach ($yml as $key => $value) {
                $commandInput = new ArrayInput([
                    'command' => 'config:override',
                    'name' => $name,
                    'key' => $key,
                    'value' => $value,
                    '--no-interaction' => true,
                ]);

                $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

                if ($returnCode) {
                    return $returnCode;
                }
            }
        }

        return 0;
    }
}
