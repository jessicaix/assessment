<?php

/**
 * @file
 * Define costom block base.
 *
 * @created author: can
 * @created date: 2018/08/22
 */

namespace Drupal\gobear_jobs;

use Drupal\Core\Block\BlockBase;

/**
 * Defines custom block base.
 */
abstract class CustomBlockBase extends BlockBase {

  use UncacheableRenderableArrayTrait;

}
