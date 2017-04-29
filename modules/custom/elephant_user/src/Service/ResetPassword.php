<?php
namespace Drupal\elephant_user\Service;


use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;

class ResetPassword {
  private $responseHandler;

  public function __construct(ElephantResponseHandler $responseHandler) {
    $this->responseHandler = $responseHandler;
  }

  public function RequestReset($mail) {
    if (user_load_by_mail($mail)) {
      $user = user_load_by_mail($mail);
      // Send password reset mail to user
      _user_mail_notify('password_reset', $user);
    }
  }
}