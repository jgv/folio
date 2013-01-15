<?php

include_once(dirname(__FILE__) . "/theme.php");

class Folio {
  public $env;
  public $theme;
  public $host;
  private $themed;
  private $dir;
  private $assets;
  private $app_path;

  function __construct($style=''){

    $this->dir = dirname(__FILE__);
    $this->assets = $this->dir . "/assets";
    $this->app_path = $this->dir . "/assets/javascripts";
    $this->host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    /*
     * Determine our environemnt: production or development?
     * We serve a different set of assets depending on the env.
     *
     */

    $server_path = split(":", $_SERVER['HTTP_HOST']);

    if ($server_path[0] == 'localhost'){
      error_reporting(E_ALL ^ E_NOTICE);
      $this->env = 'development';
    } else {
      $this->env = 'production';    
    }

    /*
     * No script access.
     */
       
    if (preg_match("/folio\.php$/", $_SERVER['PHP_SELF']) && $this->env == 'production'){
      exit('No direct script access allowed');
    }
   
    /*
     * Do we have custom theme?
     *
     */

    $themes = scandir("themes");
    foreach ($themes as $theme){
      if (preg_match("/^\*/", $theme)) {
        $this->theme = new Theme($theme);
        $this->themed = true;
        continue; // only get the first one. TODO: find a better solution
      }
    }

    /*
     * Configure headline/byline
     *
     */

    if(file_exists('homepage-details/headline.txt')){
      $headline = file_get_contents('homepage-details/headline.txt');
      define('HEADLINE', $headline);
    }
   
    if(file_exists('homepage-details/byline.txt')){
      $byline = file_get_contents('homepage-details/byline.txt');
      define('BYLINE', $byline);      
    }
  }

  /*
   * Load the JavaScript assets that make up the application.
   *
   */

  public function load_app(){
    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>";
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
        echo "<script src='$asset'></script>";
      }

    } else {
      // production environment app
      echo "<script src='$app_path/production.js'></script>";
    }
  }

  /*
   *
   * Return some JSON based on our directory structure
   *
   */
  public function to_json(){   
    return json_encode(array("projects" => $this->projects()));    
  }  

  /*
   *
   * Get projects
   *
   */
  public function projects(){
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
  public function project_titles(){

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

  public function images($dir=''){
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
  
  public function description($dir=''){    
    $file = "projects/{$dir}/description.txt";
    if(file_exists($file)){
      $description = file_get_contents($file);
    }
    return stripcslashes($description);
  }

}