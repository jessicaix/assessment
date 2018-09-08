<?php
namespace Drupal\gobear_jobs\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface JobInterface extends ContentEntityInterface {

  /**
   * Returns the ISO timestamp when the job was posted.
   *
   * @return string
   */
  public function getCreatedAt();

  /**
   * Returns the job description.
   *
   * @return string
   */
  public function getDescription();

  /**
   * Returns the job type.
   *
   * @return string
   */
  public function getType();

  /**
   * Returns the location.
   *
   * @return string
   */
  public function getLocation();

  /**
   * Returns the application steps.
   *
   * @return string
   */
  public function getApplicationSteps();

  /**
   * Returns the company.
   *
   * @return string
   */
  public function getCompany();

  /**
   * Returns the company website URL
   *
   * @return string
   */
  public function getCompanyUrl();

  /**
   * Returns the company logo
   *
   * @return string
   */
  public function getCompanyLogo();

  /**
   * Returns the job URL.
   *
   * @return string
   */
  public function getExternalUrl();

}
