<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\RequestException;

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
    try {
      $response = $client->get($url);
      $data = $response->getBody();
      $code = $response->getStatusCode();
      $header = $response->getHeaders();
    }
    catch (RequestException $e) {
      watchdog_exception('gobear_jobs', $e);
    }

    if ($data) {
      $list = json_decode($data->getContents());
    }
    else {
      drupal_set_message(t('No jobs found.'));
    }

    return [
      '#theme' => 'gobear',
      '#items' => $list,
      '#attached' => [
        'library' => ['gobear_jobs/gobear_jobs'],
      ],
    ];
  }
}
