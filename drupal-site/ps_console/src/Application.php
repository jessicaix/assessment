<?php
namespace Tci\PsConsole;

use Drupal\Console\Application as DrupalApplication;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * @var \Drupal\Console\Application
     */
    protected $drupalApplication = null;

    /**
     * @param \Drupal\Console\Application $application
     */
    public function setDrupalApplication(DrupalApplication $application)
    {
        $this->drupalApplication = $application;
    }

    /**
     * @return \Drupal\Console\Application
     */
    public function getDrupalApplication()
    {
        return $this->drupalApplication;
    }
}
