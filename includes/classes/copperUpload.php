<?php

/**
 * copperUpload
 *
 * Upload helper
 *
 * @package    copperFramework
 * @version    1.0
 */
class copperUpload {
  public $fileExt;

  
  public function __construct($fileNameForm) {
    $this->uploadDir = copperConfig::get('uploadPath');
    $this->fileNameForm = $fileNameForm;
    $this->extractPartNames();
    $this->getNewName();
  }

  private function extractPartNames() {
    $uploadFile = $this->uploadDir . basename($_FILES[$this->fileNameForm]['name']);
    $this->fileExt = strrchr($uploadFile, '.');
    $this->fileBasename = substr($uploadFile, 0, strripos($uploadFile, '.'));
  }
 
  private function getNewName() {
    $uploadNro = 0;
    while(file_exists($this->fileBasename . '_' . $uploadNro . $this->fileExt)) {
      $uploadNro++;
    }
    $this->uploadFile = $this->fileBasename . '_' . $uploadNro . $this->fileExt;
    return $this->uploadFile;
  }

  public function isValid() {
    if(strpos( $_FILES[$this->fileNameForm]['type'],'image/') === false) { 
      return false;
    }
    return true;
  }

  public function doUpload() {
    if (move_uploaded_file($_FILES[$this->fileNameForm]['tmp_name'], $this->uploadFile)) {
      return true;
    }
    return false;
  }

  public function getUploadName() {
    return $this->uploadFile;
  }

  public function getPublic() {
    return  copperConfig::pubUpload(basename($this->uploadFile));
  }
}
