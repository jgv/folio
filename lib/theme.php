<?php

class Theme extends Folio {
  private $theme_name;
  private $current_theme_dir;
  private $css_dir;
  private $js_dir;
  private $img_dir;
  private $css_files;
  private $js_files;
  private $document;

  function __construct($theme){
    parent::__construct();
    $this->theme_name = $theme;
    $this->current_theme_dir = "themes/" . $this->theme_name;
    $this->css_dir =  $this->current_theme_dir . "/stylesheets";
    $this->js_dir = $this->current_theme_dir . "/javascripts";
    $this->img_dir = $this->current_theme_dir . "/images";
    $this->css_files = $this->get_css();
    $this->js_files = $this->get_js();
    $document = new DOMDocument();
    $document->loadHTMLFile($this->dir . "/folio.html");
    $this->document = $document; // save doc to memory
  }

  public function load_theme(){
    $this->load_asset("https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");

    if ($this->env != 'production') {

      // the order of these scripts loading matters
      $app_assets = array("assets/javascripts/json2.js",
                          "assets/javascripts/underscore.js",
                          "assets/javascripts/backbone.js",
                          "assets/javascripts/backbone-localstorage.js",
                          "assets/javascripts/backbone-support/support.js",
                          "assets/javascripts/backbone-support/composite_view.js",
                          "assets/javascripts/backbone-support/swapping_router.js",
                          "assets/javascripts/icanhaz.js",
                          "assets/javascripts/jquery.cycle.lite.js",
                          "assets/javascripts/folio.js");

      foreach($app_assets as $asset){
        $this->load_asset($asset);
      }
    } else {
      // production environment app
      echo "<script src='$app_path/production.js'></script>";
    }

    $this->insert_css();
    //$this->insert_js();
    $this->load_json();
    $this->finalize();
  }

  private function load_json(){
    $json = Folio::to_json();
    $body = $this->document->getElementsByTagName("body")->item(0);
    $script = $this->document->createElement("script");
    $script->setAttribute("src", $asset);
    $script->setAttribute("type", "text/json");
    $script->setAttribute("id", "bootstrap");
    $script->appendChild($this->document->createTextNode($json));
    $body->appendChild($script);
  }


  /*
   * Configure headline
   *
   */

  private function configure_headline() {
    if(file_exists('homepage-details/headline.txt')){
      $headline = file_get_contents('homepage-details/headline.txt');
      define('HEADLINE', $headline);
    }
  }

  /*
   * Configure byline
   *
   */
  private function configure_byline() {
    if(file_exists('homepage-details/byline.txt')){
      $byline = file_get_contents('homepage-details/byline.txt');
      define('BYLINE', $byline);
    }
  }


  private function load_asset($asset) {
    $head = $this->document->getElementsByTagName("head")->item(0);
    $script = $this->document->createElement("script");
    $script->setAttribute("src", $asset);
    $script->setAttribute("type", "text/javascript");
    $head->appendChild($script);
  }


  private function insert_css(){
    if ($this->css_files) {
      foreach($this->css_files as $css_file) {
        $head = $this->document->getElementsByTagName("head")->item(0);
        $link = $this->document->createElement("link");
        $link->setAttribute("href", $this->css_dir . "/" . $css_file);
        $link->setAttribute("type", "text/css");
        $link->setAttribute("rel", "stylesheet");
        $head->appendChild($link);
        $this->document->saveHTML();
      }
    }
  }

  private function load_js() {
    if ($this->js_files){
      foreach($this->js_files as $js_file){
        $head = $this->document->getElementsByTagName("head")->item(0);
        $script = $this->document->createElement("script");
        $script->setAttribute("src", $this->js_dir . "/" . $js_file);
        $script->setAttribute("type", "text/javascript");
        $head->appendChild($script);
      }
    }
  }

  private function get_css(){
    if (is_dir($this->css_dir)){
      $csses = scandir($this->css_dir);
      foreach($csses as $css){
        if($css === '.' || $css === '..' || $css === '.DS_Store') {
          continue;
        }
        $css_arr[] = $css;
      }
      return $css_arr;
    }
  }
  private function get_js(){
    if (is_dir($this->js_dir)){
      $jses = scandir($this->js_dir);

      foreach($jses as $js){
        if($js === '.' || $js === '..' || $js === '.DS_Store') {
          continue;
        }
        $js_arr[] = $js;
      }
      return $js_arr;
    }
  }

  function get_name(){
    // TO COME
    return;
  }

  public function finalize(){
    echo $this->document->saveHTML();
  }

}