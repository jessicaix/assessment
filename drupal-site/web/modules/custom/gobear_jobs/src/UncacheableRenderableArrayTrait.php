<?php

/**
 * @file
 * Seeting no cache.
 *
 * @created author: can
 * @created date: 2018/08/22
 */

namespace Drupal\gobear_jobs;

/**
 * A trait for making render array uncacheable
 */
trait UncacheableRenderableArrayTrait {

  /**
   * Makes render array uncacheable.
   *
   * @param array $build
   *   The render array to make uncacheable.
   *
   * @return array
   *   Modified render array.
   */
  protected function makeUncacheable(array $build) {
    $build['#cache'] = [
      'max-age' => 0,
    ];
    return $build;
  }

}
