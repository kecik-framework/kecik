<?php
use Kecik\Route;
use Kecik\Url;
?><!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html> <!--<![endif]-->  
    <head>
        <title>Example Kecik Framework</title>
        
        <meta charset="utf-8">
		<title>Simple Template</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		@css
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Kecik Framework</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <li <?php if ( Route::is('/') ) { ?> class="active" <?php } ?> ><a href="<?php Url::to('') ?>">Home</a>
              </li>
              <li <?php if ( Route::is('data') ) { ?> class="active" <?php } ?> ><a
                      href="<?php Url::to('data') ?>">Data</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        
        @response

    </div><!-- /.container -->
		

		@js
	</body>
</html>