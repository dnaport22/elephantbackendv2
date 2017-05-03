<?php
namespace Drupal\elephant_stack\Components\Elephant_Item\Service;

use Drupal\elephant_stack\Components\Elephant_Item\Manager\ItemManager;
use Drupal\elephant_stack\Components\Elephant_Item\Service\Item;
use Drupal\elephant_stack\REST_Gateway\Http\ElephantResponseHandler;

class AppIntention extends Item {

  /**
   * Description: Data manager for items
   * @var ItemManager
   */
  private $itemManager;
  private $responder;

  public function __construct(ItemManager $itemManager, ElephantResponseHandler $responseHandler) {
    $this->itemManager = $itemManager;
    $this->responder = $responseHandler;
  }

  /**
   * Description: Load all the items
   * @param $offset
   * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
   */
  public function mainList($offset) {
    $items = self::loadAll($offset);
    if ($items) {
      return $this->responder->onSuccess(
        $this->itemManager->itemList($items)
      );
    }

    return $this->responder->onErrorResponse();
  }

  /**
   * Description: Load all the items posted by specific user
   * @param $uid
   * @param $offset
   * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
   */
  public function userList($uid, $offset) {
    $items = self::loadOwner($uid, $offset);
    if ($items) {
      return $this->responder->onSuccess(
        $this->itemManager->itemList($items)
      );
    }

    return $this->responder->onErrorResponse();
  }
}