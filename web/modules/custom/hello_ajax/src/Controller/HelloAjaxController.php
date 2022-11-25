<?php

declare(strict_types=1);

namespace Drupal\hello_ajax\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns Ajax response for Hello Ajax route.
 */
class HelloAjaxController extends ControllerBase {

  /**
   * Returns Ajax Response containing the current time.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Ajax response containing html to render time.
   */
  public function createHelloAjaxResponse(): JsonResponse {
    $currentTime = \Drupal::service('date.formatter')->format(time());
    $time = [
      '#markup' => $this->t('Current time: @time', ['@time' => $currentTime]),
    ];

    $response['time'] = \Drupal::service('renderer')->render($time);
    return new JsonResponse($response);
  }

}
