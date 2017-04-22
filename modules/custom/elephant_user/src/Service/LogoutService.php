<?php
namespace Drupal\elephant_user\Service;

use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;

class LogoutService {
  private $responder;

  public function __construct(ElephantResponseHandler $responseHandler) {
    $this->responder = $responseHandler;
  }

  public function logoutUser($uid) {
    // TODO: For now logging out will be done on client side only.
  }
}