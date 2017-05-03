<?php
namespace Drupal\elephant_stack\Components\Elephant_Item\Manager;

use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemManager extends JsonResponse {

  /**
   * Description: Item properties
   * @var
   */
  private $itemID;
  private $itemName;
  private $itemOwner;
  private $postDate;
  private $itemDescription;
  private $itemImage; /** http://elephantv2.local/sites/default/files/file_uri **/

  /**
   * Description: Item list (including all the properties above)
   * @var array
   */
  private $itemData = array();

  /**
   * Description: Set all the item properties and prepares itemData array
   * @param $items
   * @return bool
   */
  public function loadList($items) {
    if (is_array($items)) {
      foreach ($items as $node) {
        $this->setItemID(
          $node->nid->getString()
        );
        $this->setItemOwner(
          $node->uid->getString()
        );
        $this->setItemName(
          $node->title->getString()
        );
        $this->setItemDescription(
          $node->body->getString()
        );
        $this->setItemImage(
          $node->field_item_image->getString()[0]
        );
        $this->setPostDate(
          $node->created->getString()
        );

        $this->itemData[] = array(
          'id' => $this->getItemID(),
          'name' => $this->getItemName(),
          'description' => $this->getItemDescription(),
          'owner' => $this->getItemOwner(),
          'image_src' => $this->getItemImage(),
          'post_date' => $this->getPostDate(),
          );
      }

      return True;
    }

    return False;
  }

  /**
   * Description: Trigger loadList method to set data prepare itemData array
   * @param $items
   * @return mixed;
   */
  public function itemList($items) {
    if ($this->loadList($items)) {
      return $this->itemData;
    }

    return False;
  }

  /**
   * @return int
   */
  public function getItemID() {
    return $this->itemID;
  }

  /**
   * @return string
   */
  public function getItemName() {
    return $this->itemName;
  }

  /**
   * @return int
   */
  public function getItemOwner() {
    return $this->itemOwner;
  }

  /**
   * @return int
   */
  public function getItemImage() {
    return $this->itemImage;
  }

  /**
   * e.g = March 10, 2001, 5:16 pm
   * @return date
   */
  public function getPostDate() {
    return $this->postDate;
  }

  /**
   * @return string
   */
  public function getItemDescription() {
    return $this->itemDescription;
  }

  /**
   * @param $itemID | int
   */
  public function setItemID($itemID) {
    $this->itemID = $itemID;
  }

  /**
   * @param $itemName | string
   */
  public function setItemName($itemName) {
    $this->itemName = $itemName;
  }

  /**
   * @param $itemOwner | int
   */
  public function setItemOwner($itemOwner) {
    $this->itemOwner = $itemOwner;
  }

  /**
   * e.g = March 10, 2001, 5:16 pm
   * @param $postDate | datetime
   */
  public function setPostDate($postDate) {
    $this->postDate =  date("\"F j, Y, g:i a\"",$postDate);
  }

  /**
   * @param $imgID | url
   */
  public function setItemImage($imgID) {
    if ($imgID) {
      $file = File::load($imgID);
      $this->itemImage = $file->getFileUri();
    }
    $this->itemImage = 'no_image';
  }

  /**
   * @param $itemDescription | string
   */
  public function setItemDescription($itemDescription) {
    $this->itemDescription = $itemDescription;
  }

}