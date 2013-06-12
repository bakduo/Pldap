<?php
   
  header("Content-Type: text/html;  charset=ISO-8859-1",true);

  session_start();


  function preventHijacking($normal, $id, $agente)
{
	if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['web']) || !isset($_SESSION['control_browser']) || !isset($_SESSION['id']))
		return false;

	if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
		return false;

        if ($_SESSION['id'] != $id)
                return false;

	if ($_SESSION['control_browser'] != $agente)
		return false;
   
        if ($_SESSION['web'] != $normal )
                return false;

	return true;
}

//Función para validar password
//Basado en la idea de los foros php master
//Modificado para alternar posibilidades de passwords

  function valid_pass($candidate) {
    $r1='/[A-Z]/';// Uppercase
    $r2='/[a-z]/';// lowercase
    $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
    $r4='/[0-9]/';// numbers
    $tiene_mayus=TRUE;
    $tiene_minus=TRUE;
    $tiene_numero=TRUE;
    $tiene_special=TRUE;

    if (preg_match_all($r1,$candidate, $o)<2) $tiene_mayus=FALSE;

    if (preg_match_all($r2,$candidate, $o)<2) $tiene_minus=FALSE;

    if (preg_match_all($r3,$candidate, $o)<2) $tiene_special=FALSE;

    if (preg_match_all($r4,$candidate, $o)<2) $tiene_numero=FALSE;
    
    if ($candidate=="?$8@Gt/-A90") return FALSE;
    if ($candidate=="LMs89llskA") return FALSE;
    if ($candidate=="L22k#$") return FALSE;

    if (strlen($candidate)<6) return FALSE;

    if (($tiene_mayus and $tiene_special and $tiene_numero) or ($tiene_minus and $tiene_special and $tiene_numero)) return TRUE;
    if (($tiene_minus and $tiene_special) or ($tiene_mayus and $tiene_special)) return TRUE;
    if (($tiene_minus and $tiene_numero) or ($tiene_mayus and $tiene_numero)) return TRUE;

    return FALSE;
  }
   
   function is_post_null(){
     if (!isset($_POST['captchastring']) || !isset($_POST['id']) || !isset($_POST['agente']) || !isset($_POST['normal']) || !isset($_POST['user']) || !isset($_POST['password_n']) || !isset($_POST['password_r'])){
       return true;
     }
   }
   

   $respuesta="";
   $error=0;

   //Para prevenir acceso al archivo de procesar sin ser enviado con ajax
   if (is_post_null()){
     $error=1;
     $respuesta="<p>Intentando acceder sin datos.</p>";
     session_destroy();
     session_regenerate_id($delete_old_session = true);
     session_write_close();
     exit();
   }

   //Datos enviadose del cliente viajan por post, lo recepcionamos y procesamos

   $capcha=$_POST['captchastring'];
   /***Datos se seguridad***/
   $idsession=$_POST['id'];
   $agente=$_POST['agente'];
   $sesion_iniciada=$_POST['normal'];
   /*************************/
   $usuario= $_POST['user'];
   $password=$_POST['password'];
   $password_n=$_POST['password_n'];
   $password_r=$_POST['password_r'];

   //Previniendo robo de session
   if (!preventHijacking($sesion_iniciada,$idsession,$agente)){
     $respuesta .= '<p>Su session no es correcta.</p>';
     $error=1;
   }

   if(($password_n=="") || ($password_r=="") || ($password=="") || ($capcha=="") || ($usuario=="") ){
       $respuesta .= "<p>No se permiten campos en blanco</p>";
       echo $respuesta;
       exit();
   }

   if ($_SESSION['CAPTCHAString'] != strtoupper($capcha)){
      $respuesta .= '<p>Error el codigo es diferente vuelva a ingresar el capcha.</p>';
      $error=1;
   }
  
   if (!valid_pass($password_n)) {
       $respuesta .= "<p> Error  su password al menos tiene que tener 6 caracteres y debe ser como el ejemplo: '?$8@Gt/-A9O o LMs89llskA o L22k#$, se permiten numeros,letras y caracteres especiales' </p>";
       $error=1;
   }

   if ($password_r != $password_n) {
      $respuesta .= '<p>Error password nuevo no esta correctamente en los campos nuevo y reintentar.</p>';
      $error=1;
   }
   
   //Si existe al menus un error entonces lo muestro y vuelvo a intentar
   if ($error==1){
    echo $respuesta;
    exit();
   } 

   //Llamo a cambiar password
   require 'ldap_conn.php';

   //ldap_replace_password($usuario,$password,$password_n);

   cambiar_password($usuario,$password,$password_n);
   session_destroy();
   session_regenerate_id($delete_old_session = true);
   session_write_close();

?>
