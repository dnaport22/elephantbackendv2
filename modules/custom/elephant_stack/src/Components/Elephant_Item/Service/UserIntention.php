<?php
namespace Drupal\elephant_stack\Components\Elephant_Item\Service;

use Drupal\elephant_stack\Components\Elephant_Item\Manager\ItemManager;
use Drupal\elephant_stack\REST_Gateway\Http\ElephantResponseHandler;

class UserIntention extends Item {
  private $itemManager;
  private $responder;

  private $msg;
  private $nid;
  private $uid;

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
    $this->setRequestMessageData($msg, $nid, $uid);
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
  public function donateItem($nid, $uid) {
    if (self::itemDonated($nid, $uid)) {
      return $this->responder->onItemDonatedResponse();
    }
    return $this->responder->onErrorResponse();
  }

  /**
   * Description: Delete item from db
   * @param $nid
   * @return bool
   */
  public function deleteItem($nid, $uid) {
    if (self::itemDelete($nid, $uid)) {
      return $this->responder->onItemDeletedResponse();
    }

    return $this->responder->onErrorResponse();
  }

  private function setRequestMessageData($msg, $nid, $uid) {
    $this->msg = $msg;
    $this->nid = $nid;
    $this->uid = $uid;
  }

  public function getRequestMessageData() {
    return array(
      "msg" => $this->msg,
      "nid" => $this->nid,
      "uid" => $this->uid,
    );
  }


}