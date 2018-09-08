<?php

namespace Drupal\gobear_jobs;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Http\ClientFactory;
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
   * @var \Drupal\Core\Http\ClientFactory
   */
  protected $httpClientFactory;

  /**
   * JobFetcher constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Http\ClientFactory $http_client_factory
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ClientFactory $http_client_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->httpClientFactory = $http_client_factory;
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
    $response = $this->httpClientFactory->fromOptions($options)->send($request);

    $data = Json::decode($response->getBody()->getContents());
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
