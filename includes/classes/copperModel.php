<?php

class copperModel {
  public $db;
  public $fb;

  public function __construct($fb) {
    $this->fb = $fb;
    $this->fb instanceof Facebook;
    $this->db = copperConfig::get('copperDb');
    $this->db instanceof copperDb;
  }

}
