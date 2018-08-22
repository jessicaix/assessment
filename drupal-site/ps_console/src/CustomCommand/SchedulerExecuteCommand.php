<?php
namespace Tci\PsConsole\CustomCommand;

use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class SchedulerExecuteCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('yawata:scheduler:execute')
      ->setDescription('(proxy) Execute workflow scheduler - drupal cron:execute scheduler')
      ->setDefinition([]);
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $commandInput = new ArrayInput([
      'command' => 'cron:execute',
      'module' => ['scheduler'],
    ]);

    $commandOutput = new NullOutput();

    return $this->getApplication()->getDrupalApplication()->run($commandInput, $commandOutput);
  }
}
