<?php

namespace Drupal\gobear_jobs;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Default base class for fetching jobs.
 */
class JobFetcher implements JobFetcherInterface {

  const ENTITY_TYPE = 'gobear_job';

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * JobFetcher constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   *
   * @param $url
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function fetch($url) {
    $jobs = [];

    $options = [
      'timeout' => 30,
    ];

    $request = new Request('GET', $url);
    $client = new Client($options);
    $response = $client->send($request);

    $data = Json::decode($response->getBody()->__toString());
    foreach ($data as $item) {
      if ($job = $this->createEntityFromData($item)) {
        $jobs[] = $job;
      }
    }

    return $jobs;
  }

  /**
   * Instantiates a new Job entity using the given values.
   *
   * @param array $data
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function createEntityFromData($data) {
    return $this->entityTypeManager->getStorage(static::ENTITY_TYPE)
      ->create($data);
  }
}
