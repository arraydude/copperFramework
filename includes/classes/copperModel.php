<?php

class copperModel {
  /**
   *
   * @var PDO pdo instance
   */
  public $db;
  public $fb;

  public function __construct($fb) {
    $this->fb = $fb;
    $this->fb instanceof Facebook;
    $this->db = copperConfig::get('copperDb');
    $this->db instanceof copperDb;
  }


  public function getUserInfo($uid = false) {
    if ($uid) {
      $q = "SELECT * FROM members WHERE id = ? LIMIT 1;";
      $st = $this->db->prepare($q);
      $r = $st->execute(array(
                  $uid
              ));
    } else {
      $q = "SELECT * FROM members WHERE id = ? LIMIT 1;";
      $st = $this->db->prepare($q);
      $r = $st->execute(array(
                  $this->fb->me['id']
              ));
    }
    return $st->fetch();
  }
}
