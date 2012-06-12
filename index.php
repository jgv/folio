<?php  
require_once(dirname(__FILE__) . "/src/folio.php");
$folio = new Folio();
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Folio</title>
    <?php
      $folio->load_app();
      $folio->theme->get_css();
      $folio->theme->get_js();
    ?>
  </head>
  <body>
  
    <header>
      <h1>
        <a href="http://<?php echo $folio->host ?>"><?php echo HEADLINE ?></a>
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
      <?php print $folio->to_json(); ?>
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
