<?php

//Llamo al objeto Pldap
require 'Pldap.php';

//funcion procedural deprecated
function ldap_replace_password($userid,$password,$password_n){
   
   $ldaphost = "ip servidor";
   $ldapport = "puerto servidor";
   $ldap_base_dn = "dc=domain,dc=com";
   $ldaprdn = "uid=" . $userid . ",ou=People," . $ldap_base_dn;
   $ldappassw = "$password";
   $conn = ldap_connect($ldaphost,$ldapport) or die("sin conexion " . $ldaphost . ":" . $port . ".");

   ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);

   if ($conn) {
        $bind = ldap_bind($conn,$ldaprdn,$ldappassw) or die("error de conexión bind." . $php_errormsg);
        $filter="(uid=$userid)";
        $search = ldap_search($conn,$ldap_base_dn,$filter) or die($php_errormsg);
        if ($rec = ldap_get_entries($conn, $search)) {
           $dn=$rec[0]["dn"];
           $salt="";
           for ($i = 1; $i <= 10; $i++) {
              $salt = $salt . substr('012345678!9abcdefgh/ijklmnopqrs\tuvw(xyzABC)DEFGHIJKLMNOPQRSTUVWXYZ+-=', rand(0, 64), 1);
           }
           $hash = "{SSHA}" . base64_encode(pack("H*", sha1($password_n . $salt)) . $salt);
           $dn=$rec[0]["dn"];
           $modifica["userPassword"] = $hash;
           if ((ldap_modify($conn,$dn,$modifica) === true)) {
            echo "Salio todo ok la modificación";
           }else{
             echo "hubo un error serio";
           }
        }else{
          echo "Usuario invalido";
        }
         
      }
      ldap_close($conn);
}

function cambiar_Password($userid,$password,$password_n){

  //Se genera un objeto wrapper ldap
  $ldap=Ldap::getInstance();
  $ldap->set_Host("ipserver"); //ip del servidor ldap
  $ldap->set_Puerto("puerto"); //puerto del servidor ldap
  $ldap->set_Usuario($userid); //usuario ldap
  $ldap->set_Password($password); //password actual ldap
  $ldap->set_BaseDn("dc=domain,dc=com");//dominio ldap
  $conn=$ldap->conectar_Con_Servidor($ldap->get_Host(),$ldap->get_Puerto()); //genero una conectividad con el server

  if ($conn){ //si la conection existe
    $ldap->set_Protocol($conn); //setteo el protocolo hacia 3.0
    $ldaprdn = "uid=" . $ldap->get_Usuario() . ",ou=People," . $ldap->get_BaseDn(); //busco el usuario en el dominio
    $bind=$ldap->enlazar_ConDirectorio_Ldap($conn,$ldaprdn,$ldap->get_Password()); //enlazo con el directorio del dominio
    if ($bind){ //si pude enlazar entonces comiento a modificar
      $filter="(uid=$userid)"; //este es un filtro simple que trabaja sobre uid siendo nuestro uid los nombres de usuario ej gregoriom
      if ($rec=$ldap->buscar($conn,$ldap->get_BaseDn(),$filter)){ //busco a gregoriom y alamceno resulto en rec de record=> registro
        $dn=$rec[0]["dn"];//formando el dn
        $salt="";//generamos salt
        //Armando password nuevo
        for ($i = 1; $i <= 10; $i++) {
           $salt = $salt . substr('012345678!9abcdefgh/ijklmnopqrs\tuvw(xyzABC)DEFGHIJKLMNOPQRSTUVWXYZ+-=', rand(0, 64), 1);
        }
        //Armando hash para almacener en ldap basado en el standard SSHA
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($password_n . $salt)) . $salt);
        //Guardo información sobre el user en el dominio y su nueva password
        $dn=$rec[0]["dn"];
        $modifica["userPassword"] = $hash;
        
        //Llamo a modificar password desde el objeto ldap
        if (($ldap->modificar_Password_Ldap($conn,$dn,$modifica) === true)){
           echo "Su password fue modificado satisfactoriamente...";
        }else{
           echo "<p>Error no tiene permiso para modificar sobre el dominio...".$ldap->get_Error()."</p>";
        }
      } 
      }else{
           echo "<p>Error al buscar su registro en el dominio...".$ldap->get_Error()."</p>";
     }
   }else{
     echo "<p>Verificar que el servidor este online...".$ldap->get_Error()."</p>";
   }
  //Siempre cerrar la conectividad con el servidor.
  $ldap->cerrar_Ldap($conn);
}

?>
