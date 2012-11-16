<?php
ini_set('display_errors', 'On')
error_reporting(E_ALL);
include_once(dirname(__FILE__) . "/theme.php");

class Folio {
  private $env;
  private $theme;
  protected $theme_dir;
  protected $src_dir;
  protected $app_path;
  private $host;
  public $themed;
  protected $dir;
  private $assets;

  function __construct($style=''){
    $this->src_dir = dirname(__FILE__);
    $this->dir = dirname($this->src_dir);
    $this->theme_dir = $this->dir . "/themes";
    $this->assets = $this->dir . "/assets";
    $this->app_path = $this->dir . "/assets/javascripts";
    $this->host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    split(":", $_SERVER['HTTP_HOST'])[0] == 'localhost' ? $this->env = 'development' : $this->env = 'production';
    if (preg_match("/folio\.php$/", $_SERVER['PHP_SELF']) && $this->env == 'production'){
      exit('No direct script access allowed');
    }
  }

  public function load_app(){
    $this->init_theme();
  }

  private function init_theme() {
    $themes = scandir("themes");
    foreach ($themes as $theme){
      if (preg_match("/^\*/", $theme)) {
        $this->theme = new Theme($theme);
        $this->theme->load_theme();
        $this->themed = true;
        continue; // only get the first one. TODO: find a better solution
      }
    }
    return $this->theme || null;
  }

  protected function to_json(){
    return json_encode(array("projects" => $this->projects()));
  }

  protected function projects(){
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

  protected function project_titles(){
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

  private function description($dir=''){
    $file = "projects/{$dir}/description.txt";
    if(file_exists($file)){
      $description = file_get_contents($file);
    }
    return stripcslashes($description);
  }


}

$folio = new Folio();
$folio->load_app();
