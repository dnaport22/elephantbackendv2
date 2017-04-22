<?php
namespace Drupal\elephant_items\Service;

use Drupal\elephant_items\Manager\ItemManager;
use Drupal\elephant_rest_gateway\Http\ElephantResponseHandler;

class UserIntention extends Item {
  private $itemManager;
  private $responder;

  public function __construct(ItemManager $itemManager, ElephantResponseHandler $responseHandler) {
    $this->itemManager = $itemManager;
    $this->responder = $responseHandler;
  }

  /**
   * Description: Upload new item
   * @param $name
   * @param $desc
   * @param $uid
   * @param $file
   * @return bool
   */
  public function uploadItem($name, $desc, $uid, $file) {
    if (self::createItem($name, $desc, $uid, $file)) {
      return $this->responder->onItemCreatedResponse();
    }

    return $this->responder->onErrorResponse();
  }

  /**
   * Description: Item request message
   * @param $msg
   * @param $nid
   * @param $uid
   * @return bool
   */
  public function requestItem($msg, $nid, $uid) {
    if (self::itemInterest($msg, $nid, $uid)) {
      return $this->responder->onItemRequestedResponse();
    }

    return $this->responder->onErrorResponse();
  }

  /**
   * Description: Set item status to donated
   * @param $nid
   * @return bool
   */
  public function donateItem($nid) {
    if (self::itemDonated($nid)) {
      return $this->responder->onItemDonatedResponse();
    }
    return $this->responder->onErrorResponse();
  }

  /**
   * Description: Delete item from db
   * @param $nid
   * @return bool
   */
  public function deleteItem($nid) {
    if (self::itemDelete($nid)) {
      return $this->responder->onItemDeletedResponse();
    }

    return $this->responder->onErrorResponse();
  }


}