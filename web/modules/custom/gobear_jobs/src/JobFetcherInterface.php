<?php

namespace Drupal\gobear_jobs;

/**
 * Defines the interface for job fetchers.
 */
interface JobFetcherInterface {

  /**
   * Retrieves the job listing.
   *
   * @param string $url
   *   The URL where the job listing can be retrieved.
   *
   * @return \Drupal\gobear_jobs\Entity\Job[]
   */
  public function fetch($url);

}
