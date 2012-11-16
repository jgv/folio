<?php
include_once(dirname(__FILE__) . "/theme.php");

class Folio {
  private $env;
  public $theme;
  protected $theme_dir;
  protected $src_dir;
  protected $app_path;
  private $host;
  protected $document;
  public $themed;
  private $dir;
  private $assets;

  function __construct($style=''){

    $this->src_dir = dirname(__FILE__);
    $this->dir = dirname($this->src_dir);
    $this->theme_dir = $this->dir . "/themes";
    $this->assets = $this->dir . "/assets";
    $this->app_path = $this->dir . "/assets/javascripts";
    $this->host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    /*
     * Determine our environemnt: production or development?
     * We serve a different set of assets depending on the env.
     *
     */

    split(":", $_SERVER['HTTP_HOST'])[0] == 'localhost' ? $this->env = 'development' : $this->env = 'production';

    /*
     * No script access.
     */

    if (preg_match("/folio\.php$/", $_SERVER['PHP_SELF']) && $this->env == 'production'){
      exit('No direct script access allowed');
    }

  }

  /*
   * Load the JavaScript assets that make up the application.
   *
   */

  public function load_app(){

    $document = new DOMDocument();
    $document->loadHTMLFile($this->dir . "/folio.html");
    $this->document = $document; // save doc to memory

    if ($this->themed != true) {
      $this->check_for_theme();
    }

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

    $this->configure_headline();
    $this->configure_byline();
    $this->finalize();
  }

  /*
   * Do we have custom theme?
   *
   */

  private function check_for_theme() {
    $themes = scandir("themes");
    foreach ($themes as $theme){
      if (preg_match("/^\*/", $theme)) {
        $this->theme = new Theme($theme);
        $this->themed = true;
        continue; // only get the first one. TODO: find a better solution
      }
    }
    return $this->theme || null;
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

  /*
   *
   * Return some JSON based on our directory structure
   *
   */
  private function to_json(){
    return json_encode(array("projects" => $this->projects()));
  }

  /*
   *
   * Get projects
   *
   */
  private function projects(){
    $i = 1; // todo: clean this up
    foreach($this->project_titles() as $project){
      $project_object[] = array("id" => json_encode($i),
                                "title" => $project,
                                "images" => $this->images($project),
                                "description" => $this->description($project));
      $i++;
    }
    return $project_object;
  }

  /*
   *
   * Get project titles
   *
   */
  private function project_titles(){

    if ($handle = opendir('./projects')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          $projects[] = $entry;
        }
      }
      closedir($handle);
      return $projects;
    }
  }

  /*
   *
   * Get images from a directory or directories
   *
   */

  private function images($dir=''){
    $dir = 'projects/' . $dir;
    $root = scandir($dir);

    foreach($root as $value) {
      if($value === '.' || $value === '..') {
        continue;
      }

      if(is_file("$dir/$value") && preg_match("/.jpg|.jpeg|.gif|.png/i",$value)){
        $result[]= array("src" => "$dir/$value");
        continue;
      }

    }

    return $result;
  }

  /*
   *
   * Get the description from a file called description.txt in a specficied dir
   *
   */

  private function description($dir=''){
    $file = "projects/{$dir}/description.txt";
    if(file_exists($file)){
      $description = file_get_contents($file);
    }
    return stripcslashes($description);
  }

  private function load_json(){
    $json = $this->to_json();
    $body = $this->document->getElementsByTagName("body")->item(0);
    $script = $this->document->createElement("script");
    $script->setAttribute("src", $asset);
    $script->setAttribute("type", "text/json");
    $script->setAttribute("id", "bootstrap");
    $script->appendChild($this->document->createTextNode($json));
    $body->appendChild($script);
  }

  private function finalize(){
    $this->load_json();
    echo $this->document->saveHTML();
  }

}