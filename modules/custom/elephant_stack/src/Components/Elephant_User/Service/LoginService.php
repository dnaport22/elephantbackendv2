<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Drupal\Core\Password\PhpassHashedPassword;
use Drupal\elephant_stack\REST_Gateway\Http\ElephantResponseHandler;

class LoginService {
  private $responder;

  public function __construct(ElephantResponseHandler $responseHandler) {
    $this->responder = $responseHandler;
  }

  public function loginUser($data) {
    $user = user_load_by_mail($data['email']);
    if ($user) {
      if ($this->verifyPassword($user, $data['pass'])) {
        user_login_finalize($user);
        return $this->prepareLoginResponse();
      }
      return $this->responder->onInvalidPassword();
    }
    return $this->responder->onLoginErrorResponse();
  }


  private function verifyPassword($user, $pass) {
    $original_pass = $user->get('pass')->value;
    $checker = new PhpassHashedPassword(1);

    return $checker->check($pass, $original_pass);
  }

  // TODO: Check if the user if already logged in

  private function prepareLoginResponse() {
    $user = \Drupal::currentUser();
    $loginResponse =  array(
      "username" => $user->getDisplayName(),
      "email" => $user->getEmail(),
      "uid" => $user->id(),
    );

    return $this->responder->onLoginSuccessResponse($loginResponse);
  }

}