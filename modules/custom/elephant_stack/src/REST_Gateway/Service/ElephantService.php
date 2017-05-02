<?php
namespace Drupal\elephant_stack\REST_Gateway\Service;

abstract class ElephantService {

  const USER_LOGIN = 'login';
  const USER_REGISTER = 'register';
  const USER_DELETE = 'user_delete';
  const USER_ACTIVATE = 'user_activate';
  const USER_REQUEST_RESET_PASS = 'user_request_reset_pass';
  const USER_RESET_PASS = 'user_reset_pass';
  const ITEM_UPLOAD = 'item_upload';
  const ITEM_DELETE = 'item_delete';
  const ITEM_DONATE = 'item_donate';
  const ITEM_REQUEST = 'item_request';
  const ITEM_ALL = 'item_all';
  const ITEM_USER_ONLY = 'item_user_only';
  
  // Elephant_User services
  const USER_SERVICE_HANDLER = MODULE_NAME . '.userservicehandler';
  // Elephant_Item services
  const ITEM_INTENTION = MODULE_NAME . '.userintention';
  const APP_INTENTION = MODULE_NAME . '.appintention';
  
  private static $inentData;

  public function getUserServiceType($type, $data) {
    switch ($type) {
      case self::USER_LOGIN: return $this->runLogin($data);
      case self::USER_REGISTER: return $this->runRegister($data);
      case self::USER_ACTIVATE: return $this->runActivate($data); 
      case self::USER_REQUEST_RESET_PASS: return $this->runPassResetRequest($data);
      case self::USER_RESET_PASS: return $this->runPassReset($data);  
      case self::USER_DELETE: return;
    }
  }

  public function getItemServiceType($type, $data) {
    switch ($type) {
      case self::ITEM_UPLOAD: return $this->runItemUpload($data);
      case self::ITEM_DELETE: return $this->runItemDelete($data);
      case self::ITEM_DONATE: return $this->runItemDonate($data);
      case self::ITEM_REQUEST: return $this->runItemRequest($data);
      case self::ITEM_ALL: return $this->runItemAll($data);
      case self::ITEM_USER_ONLY: return $this->runItemUserOnly($data);
    }
  }

  /**
   * Sample JSON data:
   * {
   *   "email": "mymail@lsbu.ac.uk",
   *   "pass": "mypassword"
   * }
   *
   * @param $data
   * @return mixed
   */
  private function runLogin($data) {
    $login_data = json_decode($data->getContent(), TRUE);
    $this->setIntentData($login_data);
    $loginProvider = \Drupal::service(self::USER_SERVICE_HANDLER);
    return $loginProvider->loadLogin();
  }

  /**
   * Sample JSON data:
   * c
   *
   * @param $data
   * @return mixed
   */
  private function runRegister($data) {
    $register_data = json_decode($data->getContent(), TRUE);
    $this->setIntentData($register_data);
    $registerProvider = \Drupal::service(self::USER_SERVICE_HANDLER);
    return $registerProvider->loadRegister();
  }

  /**
   * Sample JSON data:
   * {
   *   "uid": "1",
   *   "code": "sdkjfbskjdbf"
   * }
   *
   * @param $data
   * @return mixed
   */
  private function runActivate($data) {
    $data = json_decode($data->getContent(), TRUE);
    $activationService = \Drupal::service(self::USER_SERVICE_HANDLER);
    return $activationService->loadActivate($data['uid'], $data['code']);
  }

  /**
   * Sample JSON data:
   * {
   *   "email": "mymail@lsbu.ac.uk"
   * }
   *
   * @param $data
   * @return mixed
   */
  private function runPassResetRequest($data) {
    $data = json_decode($data->getContent(), TRUE);
    $resetRequestService = \Drupal::service(self::USER_SERVICE_HANDLER);
    return $resetRequestService->loadRequestPassReset($data['email']);
  }

  /**
   * Sample JSON data:
   * {
   *   "uid": "1",
   *   "code": "asfkbjsfhd",
   *   "pass": "mynewpassword"
   * }
   *
   * @param $data
   * @return mixed
   */
  private function runPassReset($data) {
    $data = json_decode($data->getContent(), TRUE);
    $resetService = \Drupal::service(self::USER_SERVICE_HANDLER);
    return $resetService->loadResetUserPass($data['uid'], $data['code'], $data['pass']);
  }

  private function runUserDelete($data) {
    //TODO: call methods to delete user
  }

  private function runUserLogout($data) {
    //TODO: call methods to logout user
  }

  private function runItemUpload($data) {
    $name = $data->request->get('name');
    $body = $data->request->get('desc');
    $file = $data->files->get('image');
    $uid = $data->request->get('uid');
    $intent = \Drupal::service(self::ITEM_INTENTION);
    return $intent->uploadItem($name, $body, $uid, $file);
  }

  private function runItemDelete($data) {
    $nid = json_decode($data->getContent(), TRUE)['nid'];
    $intent = \Drupal::service(self::ITEM_INTENTION);
    return $intent->deleteItem($nid);
  }

  private function runItemDonate($data) {
    $nid = json_decode($data->getContent(), TRUE)['nid'];
    $intent = \Drupal::service(self::ITEM_INTENTION);
    return $intent->donateItem($nid);
  }

  private function runItemRequest($data) {
    $data = json_decode($data->getContent(), TRUE);
    $msg = $data['msg'];
    $uid = $data['uid'];
    $nid = $data['nid'];
    $intent = \Drupal::service(self::ITEM_INTENTION);
    return $intent->requestItem($msg, $nid, $uid);
  }

  private function runItemAll($data) {
    $offset = json_decode($data->getContent(), TRUE)['offset'];
    $intent = \Drupal::service(self::APP_INTENTION);
    return $intent->mainList($offset);
  }

  private function runItemUserOnly($data) {
    $data = json_decode($data->getContent(), TRUE);
    $offset = $data['offset'];
    $uid = $data['uid'];
    $intent = \Drupal::service(self::APP_INTENTION);
    return $intent->userList($uid, $offset);
  }

  public function setIntentData($data) {
    ElephantService::$inentData = $data;
  }

  public static function getIntentData() {
    return ElephantService::$inentData;
  }

}