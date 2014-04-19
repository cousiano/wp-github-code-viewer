<?php
/*
Plugin Name: WP_Github_Code_Viewer
Version: 1.1
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
    $rawUrlP1=str_replace("blob/","",$url);
    $rawUrl=str_replace("github.com","raw.githubusercontent.com",$rawUrlP1);
    $source = curl_file_get_contents($rawUrl);
    
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

function curl_file_get_contents($url) {   
  // init session:
  $ch = curl_init();
  
  // configure options:        
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);    
  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
  $header = array(
      'Connection: keep-alive',
      'User-Agent: Mozilla/5.0',
  );
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  
  // execute session:
  $content = curl_exec($ch);
  $error = curl_error($ch);
  
  // close resource:
  curl_close($ch);    

  // check execution return information:
  if (!$content) {
      // error occured.
      $content = "Sorry, do not manage to get the following file: $error\n";
  }
  
  // now return final content:
  return $content;
}

WP_Github_Code_Viewer::init();
