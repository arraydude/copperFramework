<?php

/**
 * Twitter API
 *
 * Clase para manejo de la api search de twitter
 *
 * @package    twitterAPP
 * @author     Nahuel Rosso
 * @version    1.2
 */
class copperTwitter {

  protected $user = NULL;
  protected $hashtags = NULL;
  protected $format = NULL;
  protected $sinceId = NULL;

  /**
   *
   * @param string $format
   */
  public function __construct($format = "json") {
    $this->format = $format;
    $this->search_api = "http://search.twitter.com/search." . $this->format . "?q=";
    $this->api = "http://api.twitter.com/1/";
  }

  /**
   * show error messages
   * @param int $n
   */
  protected function error($n) {
    die("twitter searchAPI error #" . $n);
  }

  /**
   * Add one Twitter Account to the query
   * @param string $u
   */
  public function addUser($u) {
    if (is_null($this->user)) {
      $this->user = "@" . $u;
    } else {
      $this->error(1);
    }

    return $this;
  }

  /**
   * Add a hashtag to the query
   * @param string $h
   */
  public function addHashtag($h) {
    if (is_null($this->hashtags)) {
      $this->hashtags = urlencode($h);
    } else {
      $this->hashtags .= " " . urlencode($h);
    }

    return $this;
  }

  /**
   * Obtain the twitter account information
   * @return object
   */
  public function getUserInfo() {
    $query = $this->api . "users/show." . $this->format . "?screen_name=" . $this->user;
    $userInfo = json_decode(@file_get_contents($query));
    return $userInfo;
  }

  /**
   * Returns results with an ID greater than (that is, more recent than) the specified ID.
   * There are limits to the number of Tweets which can be accessed through the API.
   * If the limit of Tweets has occured since the since_id, the since_id will be forced to the oldest ID available.
   * @param string $tweetId
   */
  public function sinceId($tweetId = "") {
    $this->sinceId = "&since_id=" . $tweetId;

    if(isset($this->sinceId)){
      return $this;
    }else{
      return false;
    }

  }

  /**
   * Retrieve the tweets using one Username or Hashtags
   * @return object
   */
  public function search() {

    if (!is_null($this->user)) {
      $this->search_api .= "%20" . $this->user;
    }

    if (!is_null($this->hashtags)) {
      $this->search_api .= "%20" . $this->hashtags;
    }

    if (!is_null($this->sinceId)) {
      $this->search_api .= $this->sinceId;
    }

    $result = file_get_contents($this->search_api);
    
    $parsed = json_decode($result);

    return $parsed;
  }

  /**
   * Obtain the user Timeline
   * @return object
   */
  public function getTimeline() {
    $query = $this->api . "statuses/user_timeline." . $this->format . "?screen_name=" . $this->user;
    $timeline = json_decode(@file_get_contents($query));
    return $timeline;
  }

  /**
   * Linkify the hashtags and mentions
   * @param string $tweet
   * @return string
   */
  public function linkifyTweet($tweet) {
    $tweet = preg_replace('/(^|\s)@(\w+)/',
                    '\1@<a target="_blank" href="http://www.twitter.com/\2">\2</a>',
                    $tweet);
    return preg_replace('/(^|\s)#(\w+)/',
            '\1#<a target="_blank" href="http://search.twitter.com/search?q=%23\2">\2</a>',
            $tweet);
  }

}