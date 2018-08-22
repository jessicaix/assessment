<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tci\PsConsole\Env;

class ModuleListCommand extends ContainerAwareCommand
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
            ->setName('ps:module:list')
            ->setDescription('Execute vendor/bin/drupal debug:module');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir($this->consoleDirectory . '/../web');

        $commandInput = new ArrayInput(array(
            'command' => 'debug:module',
        ));

        $this->getApplication()->getDrupalApplication()->run($commandInput, $output);
    }
}
