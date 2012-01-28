<?php  
require_once('src/flatfile.php'); 
require_once('config.php');
$projects = new Flatfile();
?>
<!doctype html>
<html>
  <head>
    <title>Flatfile</title>
    <script src="assets/javascripts/json2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
    <script src="assets/javascripts/underscore.js"></script>
    <script src="assets/javascripts/backbone.js"></script>
    <script src="assets/javascripts/backbone-localstorage.js"></script>
    <script src="assets/javascripts/backbone-support/support.js"></script>
    <script src="assets/javascripts/backbone-support/composite_view.js"></script>
    <script src="assets/javascripts/backbone-support/swapping_router.js"></script>
    <script src="assets/javascripts/icanhaz.js"></script>
    <script src="assets/javascripts/flatfile.js"></script>
    <link href="assets/stylesheets/normalize.css" rel="stylesheet" type="text/css" />
    <link href="assets/stylesheets/flatfile.css" rel="stylesheet" type="text/css" />
  </head>
  <body>


    <header>
      <h1><?php echo HEADLINE ?></h1>
      <p><?php echo BYLINE ?></p>
    </header>
    
    <div id="projects"></div>

    <footer>
      <h3>Created by <a href="http://jonathanvingiano.com">Jonathan Vingiano</a> at 319 Scholes' Art Hack Day.</h3>
      <p>Contributions to Flatfile are welcome on <a href="https://github.com/jgv/flatfile">Github</a>.</p>
    </footer>

    <script id="project" type="text/html">
      <div class="project" id="{{ id }}">
      <h1>{{ title }}</h1>
      {{#images}}
        <img src="{{ src }}">
      {{/images }}
        </div>
    </script>

    <script type='text/json' id='bootstrap'>
      <?php echo $projects->to_json(); ?>
    </script>

    <script type='text/javascript'>
      $(function () {
        var json_div       = document.createElement('div');
        json_div.innerHTML = $('#bootstrap').text();
        var data           = JSON.parse(json_div.innerHTML);
        Flatfile.init(data);
        });
    </script>
  </body>
</html>