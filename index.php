<?php

/*
** HR Web Application: Router
**
** Whenever a user visits a directory (including index)
** they will be redirected here. Based on where they
** were redirected from, they will be routed to where
** they need to be.
*/

session_start();
$request = $_SERVER['REDIRECT_URL'];

switch ($request) {
    case '/' :
        $_SESSION['page'] = "index";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '' :
        $_SESSION['page'] = "index";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/listing' :
        $_SESSION['page'] = "listing";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/listing/' :
        $_SESSION['page'] = "listing";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/profile' :
        $_SESSION['page'] = "profile";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/profile/' :
        $_SESSION['page'] = "profile";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/hr' :
        $_SESSION['page'] = "hr";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/hr/' :
        $_SESSION['page'] = "hr";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/addjob' :
        $_SESSION['page'] = "addjob";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/addjob/' :
        $_SESSION['page'] = "addjob";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/updateworkers' :
        $_SESSION['page'] = "updateworkers";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/updateworkers/' :
        $_SESSION['page'] = "updateworkers";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/logout' :
        $_SESSION['page'] = "logout";
        require __DIR__ . '/assets/template/template.php';
        break;
    case '/logout/' :
        $_SESSION['page'] = "logout";
        require __DIR__ . '/assets/template/template.php';
        break;
    default:
        $_SESSION['page'] = "404";
        require __DIR__ . '/assets/template/template.php';
        break;
}

?>
