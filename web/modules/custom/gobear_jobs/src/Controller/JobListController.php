<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\gobear_jobs\JobFetcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class JobListController.
 */
class JobListController extends ControllerBase {

  /**
   * @var \Drupal\gobear_jobs\JobFetcherInterface
   */
  protected $fetcher;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new JobListController object.
   *
   * @param \Drupal\gobear_jobs\JobFetcherInterface $fetcher
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(JobFetcherInterface $fetcher, LoggerInterface $logger) {
    $this->fetcher = $fetcher;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('gobear_jobs.fetcher'),
      $container->get('logger.channel.gobear_jobs')
    );
  }

  /**
   * Build the job listing page.
   *
   * @return array
   */
  public function build() {
    $url = 'https://jobs.github.com/positions.json?location=new+york';
    $jobs = [];

    try {
      $jobs = $this->fetcher->fetch($url);
    }
    catch (\Exception $e) {
      $this->logger->warning('Unable to retrieve job list from %site. Error encountered: "%error".', ['%site' => $url, '%error' => $e->getMessage()]);
      drupal_set_message(t('Unable to retrieve job list from %site.', ['%site' => $url]), 'warning');
    }

    return [
      '#theme' => 'gobear_job_list',
      '#jobs' => $jobs,
    ];
  }

}
