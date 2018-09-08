<?php

namespace Drupal\gobear_jobs\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Job entity.
 *
 * @ContentEntityType(
 *   id = "gobear_job",
 *   label = @Translation("Job"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Entity\ContentEntityNullStorage"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title"
 *   }
 * )
 */
class Job extends ContentEntityBase implements JobInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Job ID"));

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Title"));

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Type"));

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Description'));

    $fields['location'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Location"));

    $fields['how_to_apply'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t("How to apply"));

    $fields['company'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Company"));

    $fields['company_url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t("Company website"));

    $fields['company_logo'] = BaseFieldDefinition::create('uri')
      ->setLabel(t("Company logo"));

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Job URL'));

    $fields['created_at'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created at'));

    return $fields;
  }

  /**
   * Returns the job description.
   *
   * @return string
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * Returns the job type.
   *
   * @return string
   */
  public function getType() {
    return $this->get('type')->value;
  }

  /**
   * Returns the location.
   *
   * @return string
   */
  public function getLocation() {
    return $this->get('location')->value;
  }

  /**
   * Returns the application steps.
   *
   * @return string
   */
  public function getApplicationSteps() {
    return $this->get('how_to_apply')->value;
  }

  /**
   * Returns the company.
   *
   * @return string
   */
  public function getCompany() {
    return $this->get('company')->value;
  }

  /**
   * Returns the company website URL
   *
   * @return string
   */
  public function getCompanyUrl() {
    return $this->get('company_url')->value;
  }

  /**
   * Returns the company logo
   *
   * @return string
   */
  public function getCompanyLogo() {
    return $this->get('company_logo')->value;
  }

  /**
   * Returns the job URL.
   *
   * @return string
   */
  public function getExternalUrl() {
    return $this->get('url')->value;
  }

  /**
   * Returns the ISO timestamp when the job was posted.
   *
   * @return string
   */
  public function getCreatedAt() {
    return $this->get('created_at')->value;
  }
}
