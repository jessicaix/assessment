<?php

namespace Drupal\gobear_jobs\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use Zend\Diactoros\Response\JsonResponse;

class JobsController extends \Drupal\Core\Controller\ControllerBase {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'gobear_jobs';
  }

  /**
   * Handler index
   */
  public function index() {
    try {
      $request = $this->httpClient->request('GET', 'https://jobs.github.com/positions.json', ['query' => ['location' => 'new+york']]);
      $jobs = $request->getBody()->getContents();
      $jobs = json_decode($jobs);

      foreach ($jobs as &$job) {
        $job->created_at = \Drupal::service('date.formatter')->formatInterval(\Drupal::time()->getRequestTime() - strtotime($job->created_at));
      }

      return [
        '#theme' => 'gobear_jobs',
        '#jobs' => $jobs,
      ];
    } catch (GuzzleException $e) {
      return new JsonResponse(['error' => $e->getMessage()]);
    }
  }
}
