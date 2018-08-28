<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * TODO: class docs.
 */
class JobsController extends ControllerBase {

  /**
   * Callback for the gobear_jobs.jobs route.
   */
  public function content() {

   return $this->redirect('<front>');

  }

}
