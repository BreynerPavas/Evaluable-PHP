<?php
    include ("../libs/bGeneral.php");
    cabecera("");
    $errores=[];
    $titulo;

    if(!isset($_POST["enviar"])){
        include("../templates/formAlta.php");
    }else{
        
        include("../templates/formAlta.php");
    }

?>