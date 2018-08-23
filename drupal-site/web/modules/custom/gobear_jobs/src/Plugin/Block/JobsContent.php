<?php

/**
 * @file
 * Define costom block called 'Jobs Content' Block.
 *
 * @created author: can
 * @created date: 2018/08/22
 */

namespace Drupal\gobear_jobs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\gobear_jobs\CustomBlockBase;
/**
 * Provides a 'Jobs Content' Block.
 *
 * @Block(
 *   id = "jobs_content_block",
 *   admin_label = @Translation("Jobs Content"),
 *   category = @Translation("Jobs Content"),
 * )
 */

class JobsContent extends CustomBlockBase {
  public const END_POINT = 'https://jobs.github.com/positions.json';
  public const LOCATION = ['location' => 'new+york'];

  private $data;
  private $url;
  private $getParams;

   /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
	$this->url = JobsContent::END_POINT;
    $this->getParams = JobsContent::LOCATION;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$this->data = \GuzzleHttp\json_decode($this->getData($this->url, $this->getParams, null));

    return [
      '#theme' => 'jobs_content_list_block',
      '#job_list' => $this->data,
      '#attached' => array(
        'library' => array(
          'gobear/jobs',
        ),
      )
    ];
  }

  /**
   * {@inheritdoc}
   */
  private function getData( $url, $getParams, $cookieParams = null)
    {
        $query = [];

        $client = new \GuzzleHttp\Client([
            "base_uri" => $url
        ]);

        if(isset($getParams["location"])){
          $query["location"] = $getParams["location"];
        }

        $response = $client->request("GET", $url, ["query" => $query]);
        if($response->getStatusCode() != "200" ){
            throw new \Exception("faild to connect API server");
        }

        $response_body = (string)$response->getBody();

        return $response_body;
    }

}