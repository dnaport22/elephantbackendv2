<?php
namespace Drupal\elephant_rest_gateway\Http;


use Symfony\Component\HttpFoundation\JsonResponse;

class ElephantResponseHandler extends JsonResponse {

  public function onSuccess($data) {
    return $this->setData($data);
  }

  public function onEmptyQuery() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'set entity and type in the url'
    ));
  }

  public function onInvalidPassword() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'invalid password'
    ));
  }

  public function onItemCreatedResponse() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'item uploaded'
    ));
  }

  public function onItemDeletedResponse() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'item deleted'
    ));
  }

  public function onItemDonatedResponse() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'item donated'
    ));
  }

  public function onItemRequestedResponse() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'item requested'
    ));
  }

  public function onErrorResponse() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'system error'
    ));
  }

  public function onRegisterSuccessResponse() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'success register'
    ));
  }

  public function onEmailExistResponse() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'email exists'
    ));
  }

  public function onNameExistResponse() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'username exists'
    ));
  }

  public function onLoginErrorResponse() {
    return $this->setData(array(
      'status' => 0,
      'message' => 'error while logging in'
    ));
  }

  public function onLoginSuccessResponse($data) {
    return $this->setData(array(
      'status' => 1,
      'message' => 'success login',
      'body'=> $data
    ));
  }

  public function onUserIsActive() {
    return $this->setData(array(
      'status' => 1,
      'message' => 'user logged in'
    ));
  }

  public function onDebugResponse($content) {
    return $this->setData(array(
      'status' => 0,
      'message' => 'Debugging Message',
      'content' => $content
    ));
  }
}