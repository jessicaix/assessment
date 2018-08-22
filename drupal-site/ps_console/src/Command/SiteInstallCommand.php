<?php
namespace Tci\PsConsole\Command;

use Drupal\Console\Core\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Tci\PsConsole\Env;

class SiteInstallCommand extends ContainerAwareCommand
{
    protected $env = null;

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->env = new Env();
    }

    protected function configure()
    {
        $this
            ->setName('ps:site:install')
            ->setDescription('Install Drupal for default site.')
            ->setHelp(<<<HELP
If you answer 'y' to prompt, application will start installing Drupal.

Fixed settings:
- profile = standard
- site = default
- force install = ON ( If already installed, existing data will be <comment>deleted</comment> )
HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('Start installing drupal ? (y/N)', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $commandInput = new ArrayInput(array(
            'command' => 'site:install',

            'profile' => 'standard',

            '--langcode' => $this->env->get('DRUPAL_LANGCODE'),

            '--db-host' => $this->env->get('DATABASE_HOST'),
            '--db-name' => $this->env->get('DATABASE_NAME'),
            '--db-user' => $this->env->get('DATABASE_USER'),
            '--db-pass' => $this->env->get('DATABASE_PASSWORD'),
            '--db-port' => $this->env->get('DATABASE_PORT'),

            '--site-name' => $this->env->get('DRUPAL_SITE_NAME'),
            '--site-mail' => $this->env->get('DRUPAL_SITE_MAIL'),

            '--account-name' => $this->env->get('DRUPAL_ACCOUNT_NAME'),
            '--account-mail' => $this->env->get('DRUPAL_ACCOUNT_MAIL'),
            '--account-pass' => $this->env->get('DRUPAL_ACCOUNT_PASS'),

            '--uri' => 'http://default/',

            '--force' => true,
            '--no-interaction' => true,
        ));

        $this->getApplication()->getDrupalApplication()->run($commandInput, $output);

        $output->writeln('<info>Done.</info>');
    }
}
