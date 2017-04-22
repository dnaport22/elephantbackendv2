<?php
namespace Drupal\elephant_rest_gateway\Http;

use Drupal\elephant_rest_gateway\Service\ElephantService;
use Symfony\Component\HttpFoundation\Request;

class ElephantRequestHandler extends ElephantService {

  private $intentEntity;
  private $intentType;
  private $responder;

//  public function __construct(ElephantResponseHandler $responseHandler) {
//    $this->responder = $responseHandler;
//  }

  public function getUserRequest(Request $request) {
    $this->intentEntity = $request->query->get('entity');
    $this->intentType = $request->query->get('type');

    return $this->prepareIntent($request);
  }

  private function prepareIntent($request) {
    switch ($this->intentEntity) {
      case 'user': return self::getUserServiceType($this->intentType, $request);
      case 'item': return self::getItemServiceType($this->intentType, $request);
      //default: return $this->responder->onEmptyQuery();
    }
  }

}