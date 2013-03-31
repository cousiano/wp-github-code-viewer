<?php
/*
Plugin Name: WP_Github_Code_Viewer
Version: 1.0
Plugin URI: https://github.com/cousiano/wp-github-code-viewer
Description: Display raw file from github and display it using geshi syntax highliter. Usage [gcv url='myGithubUrl' lang='geshiLang']
Author: Marc Rabahi <marc.rabahi@gmail.com>
*/

//
// Include the GeSHi library
//
include_once( 'geshi/geshi.php' );

class WP_Github_Code_Viewer {

  // init plugin.
  function init() {
    add_shortcode('gcv', array(__CLASS__, '_add_shortcode'));
  }

  // add_shortcode
  public function _add_shortcode($atts, $content = null) {
    if (array_key_exists('url', $atts)) {
      $url = $atts['url'];
    } else {
      return self::getErrorFullMessage('did not find paramater <i>url</i>.');
    }
    
    if (array_key_exists('lang', $atts)) {
      $language = $atts['lang'];
    } else {
      return self::getErrorFullMessage('did not find paramater <i>lang</i>.');
    }
    
    // get raw file from github
    $source = file_get_contents($url . '?raw=true');
    
    // instanciate geshi
    $geshi = new GeSHi($source, $language);
    
    // parameter geshi
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
    $geshi->set_line_style('background: #fcfcfc;', 'background: #f0f0f0;');
    $geshi->set_code_style('font-size: 12px;');
    
    // parse code
    return $geshi->parse_code();    
  }
  
  // Return usage
  private function usage()
  {
    $usage = "<b>Usage</b>: [gcv url='myGithubUrl' lang='geshiLang']";
    $usage .="<br>";
    $usage .="Example: [gcv url='https://github.com/cousiano/wp-github-code-viewer/blob/master/wp-github-code-viewer.php' lang='php']";
    return $usage;
  }
  
  // Return error string
  private function getErrorFullMessage($errorMessage)
  {
    return "<b><font color='red'>$errorMessage</font></b><br><br>".self::usage();
  }
}

WP_Github_Code_Viewer::init();
