<?php
//Pinta la cabecera HTML
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

//Pinta el pie de página HTML
function pie()
{
    echo "</body>
	</html>";
}

//Función que sustituye las vocales con tilde por la misma sin tildes
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

//Función que elimina los espacios sobrantes,
//al inicio de la cadena y más de uno en los caracteres intermedios
function sinEspacios($frase)
{
    $texto = trim(preg_replace('/ +/', ' ', $frase));
    return $texto;
}

//Función que sanitiza la información. Además si no existe el control lo pone a ""
function recoge($var)
{
    if (isset($_REQUEST[$var])&&(!is_array($_REQUEST[$var]))){
        $tmp=sinEspacios($_REQUEST[$var]);
        $tmp = strip_tags($tmp);
    }
    else
        $tmp = "";

    return $tmp;
}
/*
Función que permite validar cadenas de texto.
Le pasamos cadena, nombre de campo y array de errores y
de manera voluntaria mínimo y máximo de caracteres (si = sería campo no requerido) ,
si permitimos o no espacios en nuestra cadena y si la cadena es o no sensible a mayúsculas
*/

function cTexto(string $text, string $campo, array &$errores, int $max = 40,
int $min = 1, bool $espacios = TRUE, bool $case = TRUE)
{
$case=($case===TRUE)?"i":"";
$espacios=($espacios===TRUE)?" ":"";
if ((preg_match("/^[a-zñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
    return true;
}
$errores[$campo] = "Error en el campo $campo";
 return false;
}

/*
Función que valida una cadena que contiene sólo números.
Además valida si el campo es o no requerido y el valor máximo
*/
function cNum(string $num, string $campo, array &$errores, bool $requerido=FALSE, int $max=PHP_INT_MAX)
{   $cuantificador= ($requerido)?"+":"*";
        if ((preg_match("/^[0-9]".$cuantificador."$/", $num))&&($num<=$max) ) {

        return true;
    }
    $errores[$campo] = "Error en el campo $campo";
    return false;
}

/*
 Valida la subida de un archivo a un servidor.
*/
function cFile(string $nombre, array &$errores, array $extensiones_validas, string $directorio, int  $max_file_size): bool|string
{
if ((!isset($_FILES['imagen']))||($_FILES[$nombre]['error'] != 0)) {                   // se comprueban los errores del servidor
        $errores["$nombre"] = "Error al subir el archivo " . $nombre . ". Prueba de nuevo";
        return false;
    } else {

        $nombreArchivo_original = strip_tags($_FILES[$nombre]['name']);
        $filesize = $_FILES[$nombre]['size'];
        $directorioTemp = strip_tags($_FILES[$nombre]['tmp_name']);
        $arrayInfoArchivo = pathinfo($nombreArchivo_original);

        $extension = $arrayInfoArchivo['extension'];
        if (!in_array($extension, $extensiones_validas)) {
            $errores["$nombre"] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
        }
        // Comprobamos el tamaño del archivo
        if ($filesize > $max_file_size) {
            $errores["$nombre"] = "La imagen debe de tener un tamaño inferior a 50 kb";
        }

        // Almacenamos el archivo en ubicación definitiva si no hay errores ( al compartir array de errores TODOS LOS ARCHIVOS tienen que poder procesarse para que sea exitoso. Si cualquiera da error, NINGUNO  se procesa)

        if (empty($errores)) {
            $nombreArchivo = $arrayInfoArchivo['filename'] . uniqid();
            $nombreCompleto = $directorio . $nombreArchivo . "." . $extension;
            // Movemos el fichero a la ubicación definitiva
            if (move_uploaded_file($directorioTemp, $nombreCompleto)) {

                return  $nombreArchivo;
            } else {
                $errores["$nombre"] = "Error: No se puede mover el fichero a su destino";
                return false;
            }
        } else {
            return false;
        }
    }
}
?>