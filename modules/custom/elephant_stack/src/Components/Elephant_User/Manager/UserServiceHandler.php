<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Manager;

use Drupal\elephant_stack\REST_Gateway\Service\ElephantService;

class UserServiceHandler {
  
  const LOGIN_SERVICE = MODULE_NAME . '.loginservice';
  const REGISTER_SERVICE = MODULE_NAME . '.registerservice';
  const ACTIVATE_USER_SERVICE = MODULE_NAME . '.activateuser';
  const UPDATE_PASSWORD_SERVICE = MODULE_NAME . '.updatepassword';

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function loadLogin() {
    $loginService = \Drupal::service(self::LOGIN_SERVICE);
    $data = ElephantService::getIntentData();
    return $loginService->loginUser($data);
  }

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function loadRegister() {
    $registerService = \Drupal::service(self::REGISTER_SERVICE);
    $data = ElephantService::getIntentData();
    return $registerService->createUser($data);
  }
  
  public function loadActivate($uid, $code) {
    $activateUser = \Drupal::service(self::ACTIVATE_USER_SERVICE);
    return $activateUser->ActivateUserAccount($uid, $code);
  }
  
  public function loadRequestPassReset($email) {
    $requestService = \Drupal::service(self::UPDATE_PASSWORD_SERVICE);
    return $requestService->RequestReset($email);
  }
  
  public function loadResetUserPass($uid, $code, $newpass) {
    $requestService = \Drupal::service(self::UPDATE_PASSWORD_SERVICE);
    return $requestService->UpdateUserPassword($uid, $code, $newpass);
  }

  public function authByUserToken($uid, $key) {
    $activateUser = \Drupal::service(self::ACTIVATE_USER_SERVICE);
    return $activateUser->AccountVerify($uid, $key);
  }

}