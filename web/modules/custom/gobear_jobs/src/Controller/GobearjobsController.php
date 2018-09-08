<?php

/**
 * @file
 * Contains \Drupal\gobear_jobs\Controller\GobearjobsController.
 */
namespace Drupal\gobear_jobs\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\gobear_jobs\Apis;

/**
 * Controller for position API.
 */
class GobearjobsController extends ControllerBase {
    
    protected $api_url;
    public static function create(ContainerInterface $container) {
        $api_service = \Drupal::service('gobear_jobs.apis');
        return new static($api_service);
    }
    
    /**
     * Constructor to set base url.
     */
    public function __construct($api_service) {
        $this->api_url = $api_service;
    }

    /**
     * Callback function to get position list.
     */
    public function getPositions() {
      
      $param = array(
      'location' => 'new+york'
      );
      $response = $this->api_url->api_call_request('GET', $param);
      //Pass the response in template
      return [
        '#theme' => 'positions_listing',
        '#positions' => $response,
      ];

    }
  
}

?>