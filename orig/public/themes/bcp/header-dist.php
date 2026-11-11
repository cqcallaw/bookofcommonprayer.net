<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dynamically driven Online Book of Common Prayer;
interactive Daily Offices with choice of Bible version.  Do the prayers online.">
    <meta name="author" content="Michael Seiler; http://michaelseiler.net">
    <link rel="shortcut icon" href="favicon.ico">

    <title>Book Of Common Prayer</title>

    <!-- Vendor StyleSheets -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,800,300'
    rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
    <!-- Local Styles -->
    <link href="<?php echo SERVER;?>public/css/textSizeSlider.css" rel="stylesheet" >
    <link href="<?php $this->getPath("theme-blue-2.css", "css");?>" rel="stylesheet" type="text/css">
    <link href="<?php echo SERVER;?>public/css/switch-bootstrap.css" rel="stylesheet">
    <link href="<?php echo SERVER;?>public/css/switch.css" rel="stylesheet">
    <!-- Vendor CDN Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular-cookies.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-date/1.0.1/date.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular-resource.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular-animate.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular-sanitize.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.18/angular-ui-router.js"></script>
    <script src="<?php echo SERVER;?>public/js/vendor/jquery.cookie.js"></script>
    <!-- Custom Angular Script for BCP App -->
    <script src="<?php echo SERVER;?>public/js/dist/doApp.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-88R4NLBFNL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-88R4NLBFNL');
</script>
  </head>
<!-- NAVBAR
================================================== -->
  <body class="body-boxed" ng-app="bcpApp">
    <div class="wrapper-body">

 <!-- NAVBAR -->
    <!--===============================================================-->
    <div id="header"><a id="theTop"></a>
      <nav id="nav" class="navbar navbar-inverse">

        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          <!-- Trailing Slash in Angular breaks URL State, so removing here... -->
            <a class="navbar-brand" href="<?php echo substr(SERVER, 0, -1);?>">
                <img class="img-responsive" src="<?php $this->getPath("bcp_logo.png", "img");?>"
                alt="theme-img"></a>
          </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown">Daily Offices</a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">The Offices</li>
                <li><a href="<?php echo SERVER;?>#/daily_office/today">Today's Office</a></li>
                <li><a href="<?php echo SERVER;?>#/daily_office/today/readings">Today's Office: Readings Only</a></li>
                <li><a href="<?php echo SERVER;?>#/daily_office/list">List of Today's Offices</a>
                <li><a href="<?php echo SERVER;?>#/daily_office/archive">Daily Office by Date</a></li>
                <li class="dropdown-header">Psalter</li>
                <li><a href="<?php echo SERVER;?>#/daily_office/psalter">The Daily Psalms</a></li>
                <li class="dropdown-header">Holy Days</li>
                <li><a href="<?php echo SERVER;?>#/daily_office/holy_day_list">Holy Day Readings</a></li>
              </ul>
            </li>
            <li class="dropdown">
            	<a class="dropdown-toggle" data-toggle="dropdown">Lectionary</a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">Lectionary</li>
                <li><a href="<?php echo SERVER;?>#/lectionary/upcoming">Upcoming Lectionary</a></li>
                <li><a href="<?php echo SERVER;?>#/lectionary/date">Lectionary by Week</a></li>
                <li class="dropdown-header">Holy Days</li>
                <li><a href="<?php echo SERVER;?>#/lectionary/holy_day_list">Holy Day Lectionary</a></li>
              </ul>
            </li>
            <li><a href="<?php echo SERVER;?>contents">BCP Contents</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="faq" title="Frequently Asked Questions">
                    <em class="fa fa-question-circle fontawesome-question-circle-lg blockless"
                    aria-hidden="true"></em></a>
            </li>
            <li>
                <a href="./#/preferences" title="Site Preferences">
                    <em class="fa fa-cog fontawesome-cog-lg blockless" aria-hidden="true"></em></a></li>
          </ul>
        </div><!--/.nav-collapse -->
        </div>
        </nav>
      </div>
    </div>
    <!-- NAVBAR END -->
      <!-- <div id='imageContainer' class="content-image">
        <img class="full-width img-responsive"
        src="public/themes/bcp/images/bcp.png" alt="Book Of Common Prayer" title="Book of Common Prayer">
      </div> -->
<div class="wrapper-body">
  <div class="container" id="maincontainer">
