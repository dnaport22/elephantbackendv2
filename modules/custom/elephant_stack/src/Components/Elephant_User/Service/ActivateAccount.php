<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Random;

class ActivateAccount {
  const ELEPHANT_USER_VERIFICATION_TABLE = 'elephant_user_verification';
  private $random;
  private $user;

  public function __construct() {
    $this->random = new Random();
  }

  public function loadUser($email) {
    $this->user = user_load_by_mail($email);
  }

  public function getUserUid() {
    return $this->user->uid->value;
  }

  public function storeUserVerification() {
    $fields = array(
      'uid' => $this->getUserUid(),
      'code' => $this->generateVerififcationHash(),
      'date' => date("Y-m-d H:i:s"),
    );
    $db = db_insert(self::ELEPHANT_USER_VERIFICATION_TABLE);
    $db->fields($fields);

    if ($db->execute()) {
      return True;
    }

    return False;
  }

  public function getUserVerification() {
    $db = db_select(self::ELEPHANT_USER_VERIFICATION_TABLE, 'v');
    $result = $db->fields('v', array('code'));
    $result->condition('v.uid', $this->getUserUid());
    return $result->execute()->fetchField();
  }

  public function verifyUser($uid, $code) {
    $db = db_select(self::ELEPHANT_USER_VERIFICATION_TABLE, 'v');
    $result = $db->fields('v', array('code'));
    $result->condition('v.uid', $uid);
    $orig_code = $result->execute()->fetchField();
    if ($orig_code == $code) {
      return True;
    }
    
    return False;
  }

  private function generateVerififcationHash() {
    return Crypt::hashBase64($this->generateRandomString());
  }

  private function generateRandomString() {
    return $this->random->string();
  }

}