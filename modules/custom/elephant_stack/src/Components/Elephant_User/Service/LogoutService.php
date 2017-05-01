<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Drupal\elephant_stack\REST_Gateway\Http\ElephantResponseHandler;

class LogoutService {
  private $responder;

  public function __construct(ElephantResponseHandler $responseHandler) {
    $this->responder = $responseHandler;
  }

  public function logoutUser($uid) {
    // TODO: For now logging out will be done on client side only.
  }
}