<?php

class Folio {

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
    $i = 1; // todo: clean this up
    foreach($this->project_titles() as $project){      
      $project_object[] = array("id" => json_encode($i),
                                "title" => $project,
                                "images" => $this->images($project),
                                "description" => json_encode($this->description($project)));
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
        $result[]="$dir/$value";
        continue;
      }

    }

    return array("src" => $result); 
  }
  
  public function description($dir=''){    
    $file = "projects/{$dir}/description.txt";
    if(file_exists($file)){
      $description = file_get_contents($file);
    }
    return $description;
  }

}