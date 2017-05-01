<?php
namespace Drupal\elephant_user\Service;

use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;
use Drupal\user\Entity\User;

class UpdatePassword {
  private $resetPassword;
  private $responseHandler;
  
  public function __construct(ResetPassword $resetPassword, ElephantResponseHandler $responseHandler) {
    $this->resetPassword = $resetPassword;
    $this->responseHandler = $responseHandler;
  }
  
  public function RequestReset($mail) {
    $user = user_load_by_mail($mail);
    if ($user) {
      // Set verification code for user
        $this->setResetToken($mail);
      // Send email to the for account activation
      _user_mail_notify('password_reset', $user);
      
      return $this->responseHandler->onPassResetEmailSent();
    }
    
    return $this->responseHandler->onPassResetEmailSentError();
  }


  public function UpdateUserPassword($uid, $code, $newpass) {
    if ($this->resetPassword->verifyResetToken($uid, $code)) {
      $user = User::load($uid);
      if ($user->setPassword($newpass)) {
        return $this->responseHandler->onPassResetSuccess();
      }
      
      return $this->responseHandler->onPassResetError();
    }
  }
  
  private function setResetToken($email) {
    $this->resetPassword->loadUser($email);
    return $this->resetPassword->storePassResetToken();
  }
}
