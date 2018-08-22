<?php
namespace Drupal\ps_config_split_remove_uuid\Plugin\ConfigFilter;

use Drupal\config_filter\Plugin\ConfigFilterBase;

/**
 * Provides a SplitFilter.
 *
 * @ConfigFilter(
 *   id = "ps_config_split_remove_uuid_split_filter",
 *   label = @Translation("PS Config Split"),
 *   weight = 1000,
 *   storages = {"config.storage.sync"}
 * )
 */
class SplitFilter extends ConfigFilterBase
{
  /**
   * NOTE:
   *
   * config_split module filters blacklist.
   * It returns null.
   *
   * we can only remove UUID and default_config_hash from
   * configs stored in config/sync directory.
   *
   * see:
   * Drupal\config_split\Plugin\ConfigFilter::filterWrite()
   */
  public function filterWrite($name, array $data)
  {
    unset($data['uuid']);

    unset($data['_core']['default_config_hash']);

    if (empty($data['_core'])) {
      unset($data['_core']);
    }

    return $data;
  }
}
