<?php
namespace Drupal\elephant_stack\Components\Elephant_Item\Service;

use Drupal\Component\Utility\Html;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

abstract class Item {

  /**
   * Description: Load all the items from db and return their nid
   * @param $offset
   * @return static[]
   */
  protected static function loadAll($offset) {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'item');
    $query->range($offset, $offset+10);
    $nid = $query->execute();

    return Node::loadMultiple($nid);
  }

  /**
   * Description: Load all the items where $uid == $uid and return their nid
   * @param $uid
   * @param $offset
   * @return static[]
   */
  protected static function loadOwner($uid, $offset) {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'item');
    $query->condition('uid', $uid);
    $query->range($offset, $offset+10);
    $nid = $query->execute();

    return Node::loadMultiple($nid);

  }

  /**
   * Description: Deletes the item where $nid == $nid
   * @param $nid
   * @return bool
   */
  protected function itemDelete($nid, $uid) {
    $node = $this->getNode($nid);
    if ($node) {
      $node->delete();
      return True;
    }

    return False;
  }

  /**
   * Description: Sets, item donated status where $nid == $nid
   * @param $nid
   * @return bool
   */
  protected function itemDonated($nid, $uid) {
    $node = $this->getNode($nid);
    $node->set('field_item_status', 'donated');
    $node->setRevisionAuthorId($uid);
    if ($node->save()) {
      return True;
    }

    return False;
  }

  /**
   * Description: Create new message for item request
   * @param $msg
   * @param $nid
   * @param $uid
   * @return bool
   */
  protected static function itemInterest($msg, $nid , $uid) {
    $item = Node::load($nid);
    $node = Node::create(array(
      'type' => 'item_interest_message',
      'title' => 'Elephant App Item Request - ' . $item->title->getString(),
      'body' => Html::escape($msg),
      'field_item_id' => $nid,
      'field_user_id' => $uid,
    ));

    if ($node->save()) {
      // Register pending approval mail type is modified for item request messages.
      _user_mail_notify('register_pending_approval', User::load($uid));
      return True;
    }

    return False;
  }

  /**
   * @param $name
   * @param $desc
   * @param $uid
   * @param $file
   * @return bool
   */
  protected static function createItem($name, $desc, $uid, $file) {
    $node = Node::create(array(
      'type' => 'item',
      'uid' => $uid,
      'title' => $name,
      'body' => $desc,
      'field_item_status' => 'pending_approval',
      'field_item_image' => [
        'target_id' => self::uploadFile($file),
        'alt' => $name,
        'title' => $name
      ],
    ));

    if ($node->save()) {
      return True;
    }

    return False;
  }

  /**
   * @param $file
   * @return bool|int|null|string
   */
  public function uploadFile($file) {
    $up_file = file_get_contents($file);
    $data = file_save_data($up_file, 'public://'. time() . '.jpg', FILE_EXISTS_REPLACE);
    if ($data) {
      return $data->id();
    }

    return False;
  }

  /**
   * Description: Loads node object
   * @param $nid
   * @return null|static
   */
  private function getNode($nid) {
    return Node::load($nid);
  }
}
