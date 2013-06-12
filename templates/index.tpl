{config_load file="file.conf" section="setup"}
{include file="header.tpl" title="App ldap"}

<PRE>

Fecha: {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}

{/strip}

</PRE>

<body id="main">

<div id="stylized" class="datos">

               <form id="formulario" action="" method="post">
                           <input type="hidden" name="id" id="id" value='{$id}' />
                           <input type="hidden" name="normal" id="normal" value='{$normal}' />
                           <input type="hidden" name="agente" id="agente" value='{$agente}' />
                           <div id="imagen">
                             <p id="capchastyle0"><img id="img" src="./captcha.php" alt="" /></p>
                           </div>
                           <label for="capcha">C&oacute;digo de Seguridad</label>
                           <input id="capcha" type="text" name="captchastring" id="captchastring" />
                           <label for="usuario">Usuario</label>
                           <input id="user" type="text" name="user" id="usuario"/>
                           <label for="password">Pass actual</label>
                           <input id="password" type="password" name="password" id="password"/>
                           <label for="password_n">Pass nuevo</label>
                           <input id="password_n" type="password" name="password_n" id="password_n"/>
                           <label for="password_r">Nuevamente</label>
                           <input id="password_r" type="password" name="password_r" id="password_r"/>
                             <!--<input type="submit" value="Procesar" />-->
                            
                           <p><input id="boton" type="button" value="Enviar" onclick="procesar_formulario()" /></p>
                           <p><input id="boton" type="button" value="Reset" onclick="reset_valores()" /></p>
               </form>

                <div id="resultado">
                </div>
</div>

{include file="footer.tpl"}
