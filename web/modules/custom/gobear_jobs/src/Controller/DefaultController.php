<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Gobear.
   *
   * @return string
   *   Return Hello string.
   */
  public function gobear() {

   $client = \Drupal::httpClient();
     try {
      $response = $client->get('https://jobs.github.com/positions.json?location=new+york');
      //$data = $response->getBody();
      $data = json_decode($response->getBody());
      //print_r($data);
    }
    catch (RequestException $e) {
      watchdog_exception('gobear_jobs', $e->getMessage());
    }



    return [
      '#theme' => 'gobear_jobs',
      '#jobs_var' => $data,
    ];
  }

}
