<?php
/**
 * Created by PhpStorm.
 * User: nhan
 * Date: 7/17/18
 * Time: 10:47 AM
 */
namespace Drupal\gobear_jobs\Controller;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Serialization\Json;

class GobearJobs extends ControllerBase {

  const URL = 'https://jobs.github.com/positions.json?location=new+york';

  /**
   * List job by json
   * @return template
   */
  public function jobs() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, self::URL);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = NULL;
    if($result){
      $data = Json::decode($result);
    }
    return [
      '#theme' => ['jobs_list'],
      '#jobs' => $data,
      '#attached' => array(
        'library' => array(
          'gobear_jobs/gobear_jobs',
        ),
      )
    ];
  }
}