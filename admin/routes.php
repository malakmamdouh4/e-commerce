<?php

    $route = isset($_GET['route']) ? $_GET['route'] : 'Manage' ;

    if ($route == 'Manage')
    {
        echo 'Welcome to manage page' ;
    }
    elseif ($route == 'Add')
    {
        echo 'Welcome to add page' ;
    }
    else
    {
        echo 'error, you entered to wrong page' ;
    }