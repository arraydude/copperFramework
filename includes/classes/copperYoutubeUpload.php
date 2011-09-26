<?php
/**
 * Youtube upload helper
 *
 * @author Emilio Astarita
 * @package copperFramework
 * @version 1.0
 */
class copperYoutubeUpload {

  /*
   * @var string Id de youtube populado luego de un upload exitoso a youtube
   */
  public $videoId;
  /**
   *
   * @var string La url al video populada luego de un upload exitoso a youtube.
   */
  public $videoUrl;

  public function __construct($fileNameForm) {
    $this->developerKey = copperConfig::get('youtubeDeveloperKey');
    $this->username = copperConfig::get('youtubeUsername');
    $this->password = copperConfig::get('youtubePassword');
    $this->createdBy = '';
    $this->pathYoutube1 = copperConfig::get('lib') . '/youtube/library/';
    $this->pathYoutube2 = copperConfig::get('lib') . '/youtube/library/Zend/Gdata/';
    ini_set("include_path", ini_get('include_path'). ':'. $this->pathYoutube1 . ':'. $this->pathYoutube2);
    include("YouTube.php");
    $this->authenticationURL = 'https://www.google.com/accounts/ClientLogin';
    $this->upload = new copperUpload($fileNameForm);
  }

  public function doUpload($options) {
    if(!$this->doLocalUpload()) {
      throw new Exception('Imposible hacer el upload local revisar permisos del directorio upload');
    }
    // no chequeamos errores porque dejamos que la excepción suba
    $this->doYoutubeUpload($options);
  }


  public function doLocalUpload() {
    return $this->upload->doUpload();
  }

  public function parseAdditionalMetadata() {
    try {
      $feedURL = "http://gdata.youtube.com/feeds/api/videos/". $this->videoId;
      $sxml = simplexml_load_file($feedURL);
      $media = $sxml->children('http://search.yahoo.com/mrss/');
      //// get <yt:duration> node for video length
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $this->duration = $this->length =  $length = $attrs['seconds'];
    } catch (Exception $e) {
      copperConfig::doError('Error al intentar obtener metadata adicional desde URL: ' . $feedURL . ' Excepcion: ' . $e->getMessage());
      return false;
    }
    return true;
  }

  public function doYoutubeUpload($options = array(
      'title' => '',
      'titleAlias' => '',
      'introText' => '',
      'source' => '',
      'tags' => '',
      'description' => ''
      )) {
    
    $this->fileVideoName = $this->upload->getUploadName();
    $title = $options['title'];
    $titleAlias = $options['titleAlias'];
    $introText = $options['introText'];
    $uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
    $fileName = $this->fileVideoName;
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    $httpclient = Zend_Gdata_ClientLogin::getHttpClient(
                    $this->username,
                    $this->password,
                    $service = 'youtube',
                    $client = null,
                    $source = $options['source'],
                    $loginToken = null,
                    $loginCaptcha = null,
                    $this->authenticationURL
                 );

    Zend_Loader::loadClass('Zend_Gdata_YouTube');

    $yt = new Zend_Gdata_YouTube($httpclient,$options['name'],$options['name'],$this->developerKey);
    $videoEntry = new Zend_Gdata_YouTube_VideoEntry();
    $filesource = $yt->newMediaFileSource($fileName);
    $filesource->setContentType('video/' . $this->upload->fileExt);
    $filesource->setSlug($fileName);
    $videoEntry->setMediaSource($filesource);
    $videoEntry->setVideoTitle($title);
    $videoEntry->setVideoDescription($options['description']);
    $videoEntry->setVideoCategory($options['category']);
    $videoEntry->SetVideoTags($options['tags']);

    try {
      $videoEntry = $yt->insertEntry($videoEntry,$uploadUrl,'Zend_Gdata_YouTube_VideoEntry');
      $state = $videoEntry->getVideoState();
      if ($state) {
        $youtubeId = $videoEntry->getVideoId();
        $this->videoId = $youtubeId;
        $this->videoUrl = "http://youtu.be/{$youtubeId}";
        $this->thumbSrc = "http://img.youtube.com/vi/{$youtubeId}/default.jpg";
        $this->duration = $this->length = 0;
        $this->parseAdditionalMetadata();
      } else {
        throw new Exception("Not able to retrieve the video status information yet. " . "Please try again later.\n");
      }
    } catch (Zend_Gdata_App_HttpException $httpException) {
        throw new Exception($httpException->getRawResponseBody());
    } catch (Zend_Gdata_App_Exception $e) {
        throw new Exception($e->getMessage());
    }
  }


  

}

