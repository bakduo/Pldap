<?php

require('smarty/libs/Smarty.class.php');

//inicializa la session
$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$smarty->assign("css","css/clasico.css",true);
$smarty->assign("js","js/app.js",true);

//session_destroy();

session_start();

$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
//$_SESSION['userAgent'] = sha1($_SERVER['HTTP_USER_AGENT']);

//if (!isset($_SESSION['web']))
//{
    //session_destroy();
    //session_regenerate_id(true);
    $_SESSION['web'] = true;
    $_SESSION['id'] = session_id();
    $smarty->assign("normal",$_SESSION['web']);
    $smarty->assign("id",$_SESSION['id']);
//}

//if (isset($_SESSION['control_browser']))
//{//
//    if ($_SESSION['control_browser'] != sha1($_SERVER['HTTP_USER_AGENT']))
//    {
        /* Alguna medida de seguridad */
//        exit();
//    }
//}
//else
//{
    //$_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT']);
    $_SESSION['control_browser'] =  sha1($_SERVER['HTTP_USER_AGENT']);
    $smarty->assign("agente",$_SESSION['control_browser']);
//}

//Utilizado para debug
//if (isset($_SESSION['respuesta'])){
//  $smarty->assign("respuesta",$_SESSION['respuesta']);
//}else{
//     $smarty->assign("respuesta","");
//     }
//Mostramos la vista index
$smarty->display('index.tpl');

?>
