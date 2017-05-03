<?php
namespace Drupal\elephant_stack\Components\Elephant_User\Service;

use Behat\Mink\Exception\Exception;
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
    if ($this->getPassResetToken() > 0) {
      $this->deletePassResetToken($this->getUserUid());
      return $this->insertNewResetToken();
    }

    return False;

  }

  private function insertNewResetToken() {
    $fields = array(
      'uid' => $this->getUserUid(),
      'code' => $this->generatePassResetHash(),
      'date' => date("Y-m-d H:i:s"),
    );

    $db = db_insert(self::ELEPHANT_USER_PASSWORD_RESET_TABLE);
    $db->fields($fields);
    if ($db->execute()) {
      return True;
    }

    return False;
  }

  /**
   * @return mixed
   */
  public function getPassResetToken() {
    $db = db_select(self::ELEPHANT_USER_PASSWORD_RESET_TABLE, 'p');
    $result = $db->fields('p', array('code'));
    $result->condition('p.uid', $this->getUserUid());
    return $result->execute();
  }

  public function deletePassResetToken($uid) {
    $db = db_delete(self::ELEPHANT_USER_PASSWORD_RESET_TABLE);
    $db->condition('uid', $uid);
    $db->execute();
  }

  public function verifyResetToken($uid, $code) {
    $db = db_select(self::ELEPHANT_USER_PASSWORD_RESET_TABLE, 'p');
    $result = $db->fields('p', array('code'));
    $result->condition('p.uid', $uid);
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