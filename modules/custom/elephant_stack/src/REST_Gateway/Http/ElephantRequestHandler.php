<?php
namespace Drupal\elephant_stack\REST_Gateway\Http;

use Drupal\elephant_stack\REST_Gateway\Service\ElephantService;
use Symfony\Component\HttpFoundation\Request;

/**
 * The url will be updated once we moved to cloud server.
 * Base url: http://elephantv2.local
 * User related ajax calls:
 * url => http://elephantv2.local/elephantapp/rest?entity=user& \
 *            type={login, register, user_activate, user_delete, user_request_reset_pass, user_reset_pass}
 *
 * Item related ajax calls:
 * url => http://elephantv2.local/elephantapp/rest?entity=item& \
 *            type={item_upload, item_delete, item_donate, item_request, item_all, item_user_only} \
 *              key=user_unique_key
 *
 * Class ElephantRequestHandler
 * @package Drupal\elephant_stack\REST_Gateway\Http
 */

class ElephantRequestHandler extends ElephantService {
  private $intentEntity;
  private $intentType;

  public function getUserRequest(Request $request) {
    $this->intentEntity = $request->query->get('entity');
    $this->intentType = $request->query->get('type');
     
    return $this->prepareIntent($request);
  }

  private function prepareIntent($request) {
    switch ($this->intentEntity) {
      case 'user': return self::getUserServiceType($this->intentType, $request);
      case 'item': return self::getItemServiceType($this->intentType, $request);
    }
  }

}