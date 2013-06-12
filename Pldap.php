<?php

class LdapException extends Exception { }


//Genera un singleton sobre Ldap.

class Ldap {
    private static $instance;
    private $server="";
    private $host="";
    private $puerto="";
    private $usuario="";
    private $password="";
    private $bind;
    private $basedn;
    private $description_error;

    private function __construct() {
    }

    public static function getInstance() {

           if(!self::$instance) {
             self::$instance = new self();
           }

           return self::$instance;
    }

   public function set_Error($error){
    $this->description_error=$error;
   }

   public function get_Error(){
     return $this->description_error;
   }

   public function set_Host($host){
     $this->host=$host;
   }
   public function get_Host(){
     return $this->host;
   }
   
   public function set_BaseDn($base){
     $this->basedn=$base;
   }

   public function get_BaseDn(){
     return $this->basedn;
   }
  
   public function get_Server(){
    return $this->server;
   }
   public function set_Server($server){
    $this->server=$server;
   }

   public function set_Bind($x){
      $this->bind=$x;
   }

   public function get_Bind(){
     return $this->bind;
   }

   public function get_Usuario(){
     return $this->usuario;
   }

   public function set_Usuario($user){
     $this->usuario=$user;
   }

   public function set_Password($pass){
    $this->password=$pass;
   }
  
   public function get_Password(){
    return $this->password;
   }

   public function set_Puerto($port){
    $this->puerto=$port;
   }

   public function get_Puerto(){
    $this->puerto;
   }
   //Metodos para describir errores 
   //LDAP-Errno: " . ldap_errno($ld)
   //"LDAP-Error: " . ldap_error($ld)

   public function conectar_Con_Servidor($ldaphost,$ldapport){
       try{
           try {
                $conn=ldap_connect($ldaphost,$ldapport) or function(){ throw new LdapException('Error en la conexión con el servidor LDAP!');};
                if ($conn){
                  return $conn;
                }else{
                   $error_descript="LDAP-Error codigo: ". ldap_errno($conn) . " LDAP-Error:" . ldap_error($conn);
                   $this->set_Error($error_descript);
                   return NULL;
                }
            } catch (LdapException $e) {
                echo $e->getMessage();
                die ('Error en la conexión con el servidor LDAP: ' . $e->getMessage());
                throw $e;
            }
       }catch (Exeption $e){
            var_dump($e->getMessage());
       }

    }
    
    public function enlazar_ConDirectorio_Ldap($conn,$ldaprdn,$ldappassw) {
       try{
            try {
                $bind=ldap_bind($conn,$ldaprdn,$ldappassw) or function(){throw new LdapException('Error en la conexión al enlazar de directorio LDAP!');};
                if ($bind){
                  return $bind;
                }else{
                  $error_descript="LDAP-Error codigo: ". ldap_errno($conn) . " LDAP-Error: " . ldap_error($conn);
                  $this->set_Error($error_descript);
                  return NULL;
                }
            } catch (LdapException $e) {
                echo $e->getMessage();
                die ('Error en la conexión al enlazar de directorio LDAP!: ' . $e->getMessage());
                //Vuelve a lanzar la exception para saver que puedo haber sido ademas de sacar nuestro mensaje
                throw $e;
            }
         }  catch (Exception $e) {
               var_dump($e->getMessage());
         }
    }

    public function buscar($conn,$ldap_base_dn,$filter){
       try{
            try {
              $search=ldap_search($conn,$ldap_base_dn,$filter) or function(){throw new LdapException('Error en la conexión al buscar en directorio LDAP!');};
              if ($rec = ldap_get_entries($conn, $search)){
                 return $rec;
              }else{
                 $error_descript="LDAP-Error codigo: ". ldap_errno($conn) . " LDAP-Error: " . ldap_error($conn);
                 $this->set_Error($error_descript);
                 die ('Error en la conexión al buscar en directorio LDAP: ' . $e->getMessage());
                 return NULL;
              }
            }catch (LdapException $e){
               echo $e->getMessage();
               //Vuelve a lanzar la exception para saver que puedo haber sido ademas de sacar nuestro mensaje
               throw $e; 
             }
          }catch (Exception $e) {
              var_dump($e->getMessage());
         }              
    }

    public function cerrar_Ldap($conn){
        try{
            try {
              ldap_close($conn) or function(){throw new LdapException('Error al cerrar LDAP!');};
            }catch (LdapException $e){
               echo $e->getMessage();
               $error_descript="LDAP-Error codigo: ". ldap_errno($conn) . " LDAP-Error: " . ldap_error($conn);
               $this->set_Error($error_descript);
               die ('Error al cerrar LDAP: ' . $e->getMessage());
               //Vuelve a lanzar la exception para saver que puedo haber sido ademas de sacar nuestro mensaje
               throw $e; 
             }
          }catch (Exception $e) {
              var_dump($e->getMessage());
         } 
    }

    public function modificar_Password_Ldap($conn,$dn,$modifica){
        try{
            try {
              $salida=ldap_modify($conn,$dn,$modifica) or function(){throw new LdapException('Error al modificar la password!');};
              if ($salida){
                 return TRUE;
              }else{
                 return FALSE;
              }
            }catch (LdapException $e){
               echo $e->getMessage();
               $error_descript="LDAP-Error codigo: ". ldap_errno($conn) . " LDAP-Error: " . ldap_error($conn);
               $this->set_Error($error_descript);
               die ('Error al cerrar LDAP: ' . $e->getMessage());
               //Vuelve a lanzar la exception para saver que puedo haber sido ademas de sacar nuestro mensaje
               throw $e;
             }
          }catch (Exception $e) {
              var_dump($e->getMessage());
         }
    }


    public function set_Protocol($conn){
      ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    }
}

?>
