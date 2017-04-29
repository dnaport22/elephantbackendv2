<?php
namespace Drupal\elephant_user\Service;


use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;
use Drupal\user\Entity\User;

class ActivateUser {
  private $activateAccount;
  private $responseHandler;

  public function __construct(ActivateAccount $activateAccount, ElephantResponseHandler $responseHandler) {
    $this->activateAccount = $activateAccount;
    $this->responseHandler = $responseHandler;
  }

  public function ActivateUserAccount($uid) {
    if ($this->activateAccount->verifyUser($uid)) {
      $user = User::load($uid);
      if (!$user->isActive()) {
        $user->activate();
        return $this->responseHandler->onAccountActivateSuccess();
      }

      return $this->responseHandler->onAccountAlreadyActive();
    }

    return $this->responseHandler->onAccountActivateError();
  }

}