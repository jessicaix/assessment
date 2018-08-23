<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ClearDBCacheCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ps:cache:clear')
            ->setDescription('Clear DB cache')
            ->setDefinition([
                new InputOption('yes', 'y', InputOption::VALUE_NONE)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yes = $input->hasOption('yes') ? $input->getOption('yes') : false;

        if ($yes === false) {
            $helper = $this->getHelper('question');

            $question = new ConfirmationQuestion('Start clear DB cache ? (y/N)', false);

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $output->writeln(sprintf('<info>Doing ...</info>'));

        $commandInput = new ArrayInput([
            'command' => 'cache:rebuild',
            'cache' => 'all',
        ]);

        $returnCode = $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        if ($returnCode) {
            return $returnCode;
        }

        return 0;
    }
}
