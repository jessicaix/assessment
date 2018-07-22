<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {
  
  /**
   * Display the jobs listing.
   *
   * @return array
   */
  public function content() {
    
    $url = "https://jobs.github.com/positions.json?location=new+york";
    $client = \Drupal::httpClient();
    $response = $client->get($url);
    $data = $response->getBody();
    $jobs_data = [];
    if ($data) {
      $jobs_data = Json::decode($data->getContents());
    }
    
    // Assigning theme.
    return [
      '#theme' => 'gobear_job_listing',
      '#jobs' => $jobs_data,
      '#cache' => [
        'max-age' => 0
      ]
    ];
  }
}