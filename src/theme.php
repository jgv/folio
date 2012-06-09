<?php

class Theme extends Folio {
  private $css_dir;
  private $js_dir;
  private $img_dir; 
  public $theme_name;

  function __construct($theme = ''){    
    $theme_path = $theme;
    
    $this->css_dir = "themes/" . $theme_path . "/stylesheets/";
    $this->js_dir = "themes/" . $theme_path . "/javascripts/";
    $this->img_dir = "themes/" . $theme_path . "/images/";
    $this->theme_name = $theme_path;
  }


  function get_css(){
    if (is_dir($this->css_dir)){
      $csses = scandir($this->css_dir);
      
      foreach($csses as $css){
        if($css === '.' || $css === '..') {
          continue;
        }
        echo "<link href='" . $this->css_dir . $css . "' rel='stylesheet' type='text/css'>\n";
      }
    }
  }
  
  function get_js(){
    if (is_dir($this->js_dir)){
      $jses = scandir($this->js_dir);
      
      foreach($jses as $js){
        if($js === '.' || $js === '..') {
          continue;
        }
        echo "<script src='" . $css . "' type='text/javascript'></script>";
      }
    }
  }
  
  function get_name(){
    // TO COME
    return;
  }
  
}