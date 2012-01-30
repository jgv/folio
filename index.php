<?php  
require_once('src/folio.php'); 
require_once('config.php');
$projects = new Folio();
?>
<!doctype html>
<html>
  <head>
    <title>Folio</title>
    <script src="assets/javascripts/json2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
    <script src="assets/javascripts/underscore.js"></script>
    <script src="assets/javascripts/backbone.js"></script>
    <script src="assets/javascripts/backbone-localstorage.js"></script>
    <script src="assets/javascripts/backbone-support/support.js"></script>
    <script src="assets/javascripts/backbone-support/composite_view.js"></script>
    <script src="assets/javascripts/backbone-support/swapping_router.js"></script>
    <script src="assets/javascripts/icanhaz.js"></script>
    <script src="assets/javascripts/folio.js"></script>
    <link href="assets/stylesheets/normalize.css" rel="stylesheet" type="text/css" />
    <link href="assets/stylesheets/folio.css" rel="stylesheet" type="text/css" />
  </head>
  <body>

    <header>
      <h1>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']; ?>"><?php echo HEADLINE ?></a>
      </h1>
      <p><?php echo BYLINE ?></p>
    </header>
    
    <div id="projects"></div>

    <footer>
      <h3>Created by <a href="http://jonathanvingiano.com">Jonathan Vingiano</a> at 319 Scholes' Art Hack Day.</h3>
      <p>Contributions to Folio are welcome on <a href="https://github.com/jgv/folio">Github</a>.</p>
    </footer>

    <script id="index" type="text/html">
      <div class="project" id="{{ id }}">
      <h1><a href="#/projects/{{ id }}">{{ title }}</a></h1>
      {{#images}}
        <img src="{{ src }}">
      {{/images }}
        </div>
    </script>

    <script id="show" type="text/html">
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
        Folio.init(data);
        });
    </script>
  </body>
</html>
