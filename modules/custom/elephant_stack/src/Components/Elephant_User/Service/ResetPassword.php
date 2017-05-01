<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Random;

class ResetPassword {
  const ELEPHANT_USER_PASSWORD_RESET_TABLE = 'elephant_user_password_reset';
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

  public function storePassResetToken() {
    $fields = array('uid' => $this->getUserUid(), 'code' => $this->generatePassResetHash());
    $db = db_insert(self::ELEPHANT_USER_PASSWORD_RESET_TABLE);
    $db->fields($fields);

    if ($db->execute()) {
      return True;
    }

    return False;
  }

  public function getPassResetToken() {
    $db = db_select(self::ELEPHANT_USER_PASSWORD_RESET_TABLE, 'v');
    $result = $db->fields('v', array('code'));
    $result->condition('v.uid', $this->getUserUid());
    return $result->execute()->fetchField();
  }

  public function verifyResetToken($uid, $code) {
    $db = db_select(self::ELEPHANT_USER_PASSWORD_RESET_TABLE, 'v');
    $result = $db->fields('v', array('code'));
    $result->condition('v.uid', $uid);
    $orig_code = $result->execute()->fetchField();
    if ($orig_code == $code) {
      return True;
    }
    
    return False;
  }

  private function generatePassResetHash() {
    return Crypt::hashBase64($this->generateRandomString());
  }

  private function generateRandomString() {
    return $this->random->string();
  }

}