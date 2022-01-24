
<?php

include 'connect.php';

$tpls = 'includes/templates/' ; // templates pages
$lang = 'includes/languages/' ; // english language file
$func = 'includes/functions/' ; // templates pages
$css = 'layouts/css/' ; // css folder
$js = 'layouts/js/' ; // js folder

include $func . 'functions.php';
include $lang . 'english.php' ;
include $tpls . 'header.php' ;

if (!isset($noNavbar))
{
    include $tpls . 'navbar.php' ;
}

