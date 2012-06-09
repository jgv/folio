<?php  
error_reporting(E_ALL ^ E_NOTICE);
require_once('src/folio.php');
$folio = new Folio();
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Folio</title>
    <?php if ($folio->env == 'production') {?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script src="assets/javascripts/production.js"></script>
    <?php } else {?>     
    <script src="assets/javascripts/json2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
    <script src="assets/javascripts/underscore.js"></script>
    <script src="assets/javascripts/backbone.js"></script>
    <script src="assets/javascripts/backbone-localstorage.js"></script>
    <script src="assets/javascripts/backbone-support/support.js"></script>
    <script src="assets/javascripts/backbone-support/composite_view.js"></script>
    <script src="assets/javascripts/backbone-support/swapping_router.js"></script>
    <script src="assets/javascripts/icanhaz.js"></script>
    <script src="assets/javascripts/jquery.cycle.lite.js"></script>
    <script src="assets/javascripts/folio.js"></script>
    <?php } ?>
    <link href="assets/stylesheets/normalize.css" rel="stylesheet" type="text/css" />
    <link href="assets/stylesheets/folio.css" rel="stylesheet" type="text/css" />
    <?php if ($folio->themed) {       
      $folio->theme->get_css();
      $folio->theme->get_js();
    } ?>

  </head>
  <body>
  
    <header>
      <h1>
        <a href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"><?php echo HEADLINE ?></a>
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
        <a href="#/projects/{{ id }}"><img src="{{ image  }}"></a>
      </div>
    </script>

    <script id="show" type="text/html">
      <div class="project" id="{{ id }}">
        <h1>{{ title }}</h1>
        <div id="gallery">
          {{#images}}
            <img src="{{ src }}">
          {{/images}}
        </div>
        <p>{{ description }}</p>
      </div>
    </script>

    <script type='text/json' id='bootstrap'>
      <?php echo $folio->to_json(); ?>
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
