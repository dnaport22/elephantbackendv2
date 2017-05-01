<?php
namespace Drupal\elephant_user\Manager;

use Drupal\elephant_rest_gateway\Service\ElephantService;

class UserServiceHandler {

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function loadLogin() {
    $loginService = \Drupal::service('elephant_rest_gateway.loginservice');
    $data = ElephantService::getIntentData();
    return $loginService->loginUser($data);
  }

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function loadRegister() {
    $registerService = \Drupal::service('elephant_rest_gateway.registerservice');
    $data = ElephantService::getIntentData();
    return $registerService->createUser($data);
  }
  
  public function loadActivate($uid, $code) {
    $activateUser = \Drupal::service('elephant_rest_gateway.activateuser');
    return $activateUser->ActivateUserAccount($uid, $code);
  }
  
  public function loadRequestPassReset($email) {
    $requestService = \Drupal::service('elephant_rest_gateway.updatepassword');
    return $requestService->RequestReset($email);
  }
  
  public function loadResetUserPassword($uid, $code, $newpass) {
    $requestService = \Drupal::service('elephant_rest_gateway.updatepassword');
    return $requestService->UpdateUserPassword($uid, $code, $newpass);
  }

}