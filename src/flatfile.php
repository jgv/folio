<?php

class Flatfile {

  function __construct($style=''){
    // style will at one point dictate css &c. right now this does nothing,,

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
    foreach($this->project_titles() as $project){      
      $project_object[] = array("title" => $project,
                                "images" => $this->images($project));
    }
    return $project_object;
  }

  /*
   *
   * Get project titles
   *
   */
  public function project_titles(){

    $flatfile = array();
    $projects = array();
    $list = array();

    if ($handle = opendir('./projects')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          array_push($projects, $entry);            
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
    $dir = './projects/' . $dir;
    $root = scandir($dir); 
    foreach($root as $value) { 
      if($value === '.' || $value === '..') {continue;} 
      if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;} 
      foreach(find_all_files("$dir/$value") as $value) { 
        $result[]=urlencode($value);
      } 
    } 
    return array("src" => $result); 
  }

}