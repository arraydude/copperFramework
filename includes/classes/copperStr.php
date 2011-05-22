<?php

class copperStr {

  /**
   * trims text to a space then adds ellipses if desired
   * @param string $input text to trim
   * @param int $length in characters to trim to
   * @param bool $ellipses if ellipses (...) are to be added
   * @param bool $strip_html if html tags are to be stripped
   * @return string
   */
  public static function trimText($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
      $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
      return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
      $trimmed_text .= '...';
    }

    return $trimmed_text;
  }

  public static function toLower($str) {
    return mb_convert_case($str, MB_CASE_LOWER, 'UTF-8');
  }

  public static function toUpper($str) {
    return mb_convert_case($str, MB_CASE_UPPER, 'UTF-8');
  }

  public static function Ucasefirst($str) {
    $ignore = '¿¡';
    $i = 0;
    while ($i < mb_strlen($str) && (mb_strpos($ignore, $str[$i]) !== FALSE)) {
      $i++;
    }
    $str[$i] = mb_strtoupper($str[$i]);
    return $str;
  }

  public static function parseTwitterDate($str) {
    //$parsedDate = date_parse_from_format("D, d M Y H:m:s +0000", $tweet->created_at);
    return strtotime($str);
  }

  public static function parseFacebookDate($str) {
    $exploded = explode("/", $str);

    if (count($exploded) !== 2) {
      return false;
    }

    $timestamp = mktime(0, 0, 0, $exploded[0], $exploded[1], $exploded[2]);

    return $timestamp;
  }

  public static function camelize($str, $capitalise_first_char = false) {
    if ($capitalise_first_char) {
      $str[0] = strtoupper($str[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $str);
  }

  public static function sec2hms ($sec, $hideHours = false, $padHours = false) {

    // start with a blank string
    $hms = "";

    // do the hours first: there are 3600 seconds in an hour, so if we divide
    // the total number of seconds by 3600 and throw away the remainder, we're
    // left with the number of hours in those seconds
    $hours = intval(intval($sec) / 3600);

    // add hours to $hms (with a leading 0 if asked for)
    if(!$hideHours) {
    $hms .= ($padHours)
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
          : $hours. ":";
    }

    // dividing the total seconds by 60 will give us the number of minutes
    // in total, but we're interested in *minutes past the hour* and to get
    // this, we have to divide by 60 again and then use the remainder
    $minutes = intval(($sec / 60) % 60);

    // add minutes to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

    // seconds past the minute are found by dividing the total number of seconds
    // by 60 and using the remainder
    $seconds = intval($sec % 60);

    // add seconds to $hms (with a leading 0 if needed)
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;

  }

}
