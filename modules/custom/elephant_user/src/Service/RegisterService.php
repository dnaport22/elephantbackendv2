<?php
namespace Drupal\elephant_user\Service;

use Drupal\Component\Utility\Html;
use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;
use Drupal\user\Entity\User;

class RegisterService {
  private $responder;
  private $activateAccount;

  public function __construct(ElephantResponseHandler $responseHandler, ActivateAccount $activateAccount) {
    $this->responder = $responseHandler;
    $this->activateAccount = $activateAccount;
  }

  /**
   * @param $manager
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function createUser($data) {
    if (!user_load_by_mail($data['email'])) {
      if (!user_load_by_name($data['name'])) {
        $user = User::create();
        $user->setUsername(
          RegisterService::htmlEscape($data['name'])
        );
        $user->setEmail(
          RegisterService::htmlEscape($data['email'])
        );
        $user->setPassword(
          RegisterService::htmlEscape($data['pass'])
        );
        $user->save();
        $this->setVerificationCode($data['email']);
        _user_mail_notify('register_no_approval_required', $user);

        return $this->responder->onRegisterSuccessResponse();

      }
      return $this->responder->onNameExistResponse();
    }

    return $this->responder->onEmailExistResponse();
  }

  private function setVerificationCode($email) {
    $this->activateAccount->loadUser($email);
    return $this->activateAccount->storeUserVerification();
  }

  private static function htmlEscape($input) {
    return Html::escape($input);
  }

}