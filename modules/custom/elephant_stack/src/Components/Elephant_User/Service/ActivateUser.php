<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Drupal\user\Entity\User;
use Drupal\elephant_stack\Components\Elephant_User\Service\ActivateAccount;
use Drupal\elephant_stack\REST_Gateway\Http\ElephantResponseHandler;

class ActivateUser {
  private $activateAccount;
  private $responseHandler;

  public function __construct(ActivateAccount $activateAccount, ElephantResponseHandler $responseHandler) {
    $this->activateAccount = $activateAccount;
    $this->responseHandler = $responseHandler;
  }

  public function ActivateUserAccount($uid, $code) {
    if ($this->activateAccount->verifyUser($uid, $code)) {
      $user = User::load($uid);
      if ($user->isBlocked()) {
        $user->activate();
        $user->save();
        return $this->responseHandler->onAccountActivateSuccess();
      }

      return $this->responseHandler->onAccountAlreadyActive();
    }

    return $this->responseHandler->onAccountActivateError();
  }

  public function AccountVerify($uid, $code) {
    return $this->activateAccount->verifyUser($uid, $code);
  }

}