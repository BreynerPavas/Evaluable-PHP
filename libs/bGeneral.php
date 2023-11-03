<?php
//html
function cabecera($titulo=NULL) // el archivo actual
{
    if (is_null($titulo)) {
        $titulo = basename(__FILE__);
    }
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>
				<?php
    echo $titulo;
    ?>
			
			</title>
<meta charset="utf-8" />
</head>
<body>
<?php
}

function pie()
{
    echo "</body>
	</html>";
}
//comprobaciones
function sinTildes($frase)
{
    $no_permitidas = array(
        "á",
        "é",
        "í",
        "ó",
        "ú",
        "Á",
        "É",
        "Í",
        "Ó",
        "Ú",
        "à",
        "è",
        "ì",
        "ò",
        "ù",
        "À",
        "È",
        "Ì",
        "Ò",
        "Ù"
    );
    $permitidas = array(
        "a",
        "e",
        "i",
        "o",
        "u",
        "A",
        "E",
        "I",
        "O",
        "U",
        "a",
        "e",
        "i",
        "o",
        "u",
        "A",
        "E",
        "I",
        "O",
        "U"
    );
    $texto = str_replace($no_permitidas, $permitidas, $frase);
    return $texto;
}

function sinEspacios($frase){
    $texto = trim(preg_replace('/ +/', ' ', $frase));
    return $texto;
}
function sinGuiones($frase){
    $texto = trim(preg_replace('/-+/', '', $frase));
    return $texto;
}

function recoge($var){
    if (isset($_REQUEST[$var])&&(!is_array($_REQUEST[$var]))){
        $tmp=sinEspacios($_REQUEST[$var]);
        $tmp = strip_tags($tmp);
    }
    else
        $tmp = "";

    return $tmp;
}
function recogeCheckbox($var){
    return (isset($_REQUEST[$var]));
}
function cCheckBox(string $text){
    if($text){
        return true;
    }else{
        return false;
    }
}
    
function cTexto(string $text, string $campo, array &$errores, int $max = 30, int $min = 1, bool $espacios = TRUE, bool $case = TRUE){
$case=($case===TRUE)?"i":"";
$espacios=($espacios===TRUE)?" ":"";
if ((preg_match("/^[a-zñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
    return true;
}
$errores[$campo] = "Error en el campo $campo";
 return false;
}
function cNumero(string $text, string $campo,array &$errores, int $max=150, int $min=0,bool $espacios=TRUE,bool $guiones=FALSE){
    $espacios= ($espacios===TRUE)?" ":"";
    $numAux=intval($text);
    if ((preg_match("/^[0-9$espacios]+$/",$text)) && $numAux>$min && $numAux<=$max) {
        return true;
    }else{
        $errores[$campo] = "Error en el campo $campo";
        return false;
    }
}
function cRadio(string $text,string $campo,array &$errores,array $valoresAceptados,bool $requerido=true){
    //if($requerido){
        if (in_array($text,$valoresAceptados)){
            return true;
        }else{
            $errores[$campo] = "Error en el campo $campo";
            return false;
        }
    //}
    //return true;
}
function pintaRadio(array $provincias,string $name){
    echo "<p>";
        for ($i=0; $i<count($provincias); $i++) {
            echo '<input type="radio" name="'.$name.'" value="'.$provincias[$i].'">'.$provincias[$i].'<br>';
        }
    echo "</p>";
}
function cFecha(string $fecha,string $campo, array &$errores){
    //todas las fechas me llegan -> aaaa/mm/dd
    $fecha=explode("-",$fecha);
    if(checkdate($fecha[1],$fecha[2],$fecha[0])){
        return ($fecha=mktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]));
    }else{
        $errores[$campo] = "Error en el campo $campo";
    }
}
function compareFecha($fecha1, $fecha2){

}
?>