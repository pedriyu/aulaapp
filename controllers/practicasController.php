<?php

class PracticasController extends MvcController {

    public function practicaNuevaController() { //profesor puede subir una nueva práctica
        $listadoAsignaturas = PracticasModel::listarAsignaturasModel($_SESSION["userId"]);

        //var_dump($listadoAsignaturas);
        echo '<!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES  añadir atributo multiple si quiero subir más de un archivo-->
            Enviar este fichero: <input name="fichero_usuario" type="file"/> ';
        echo 'Selecciona la asignatura';
        foreach ($listadoAsignaturas as $fila => $item) {
            echo '<input type="radio" name="idAsignatura" value="' . $item["id"] . '" checked>' . $item["nombre"] . '</input>';
        }
        echo '<input type="submit" value="Enviar fichero" />';

        if (isset($_FILES["fichero_usuario"])) {
            $datosController = $_FILES["fichero_usuario"];
            $idAsignatura = $_POST["idAsignatura"];

            $asignatura = str_pad($idAsignatura, 3, '0', STR_PAD_LEFT);
            $respuesta = PracticasModel::practicaNuevaModel($datosController, $asignatura);


            //var_dump($respuesta);
            if ($respuesta == "ok") {
                //header("location:index.php?action=ok");
                echo "Archivo de práctica cargado correctamente.";
            } else {
                //header("location:index.php");
                echo "maaaaaaal!";
            }
        }
    }

    #Lista los archivos que haya subido el profesor de esa asignatura, que se pasa como parámetro en la llamada a la función 

    /**
     * Lista los archivos que haya subido el profesor de esa asignatura, que se pasa como parámetro en la llamada a la función
     *
     * @return no retorna nada
     * @param int $idAsignatura id de la asignatura de los apuntes
     */
    public function listarPracticasController($idAsignatura) {
        //echo 'hola';
        $respuesta = PracticasModel::listarPracticasModel();
        //var_dump($respuesta);
        $valor = ' ';
        $asignatura = str_pad($idAsignatura, 3, '0', STR_PAD_LEFT);


        //echo $asignatura.'<br>';

        foreach ($respuesta as $fila => $item) {
            //var_dump($item);
            if ($item != '.' && $item != '..' ) {// (substr($item, 0, 2)==$asignatura) si los 2 número de caracteres desde el principio(0) coinciden con la asignatura, se muestran
                $valor = substr($item, 6);
                $resultado = utf8_encode($valor);
                $direccion = 'practicas/' . utf8_encode($item);
                echo '<div class="contenido-practica">';
                echo "<p><a href='$direccion'>Descargar $resultado </a></p>";
                echo '</div>';
            }
        }
    }

    public function listarPracticaBorrarController($idProfesor) {
        $respuesta = PracticassModel::listarPracticasModel();



        $valor = ' ';
        $profesor = str_pad($idProfesor, 3, '0', STR_PAD_LEFT);


        //echo $asignatura.'<br>';

        foreach ($respuesta as $fila => $item) {
            $direccion = ' ';
            if ($item != '.' && $item != '..' && $item != '.htaccess' && (substr($item, 0, 3) == $profesor)) {// (substr($item, 0, 2)==$asignatura) si los 2 número de caracteres desde el principio(0) coinciden con la asignatura, se muestran
                $valor = utf8_encode(substr($item, 6));
                $direccion = 'archivos/' . utf8_encode($item);


                echo '<div class="archivo-borrar">';
                echo "<p><a href='index.php?action=borrarArchivos&nbArchivo=$direccion'>Borrar archivo --> <strong>$valor</strong></a></p>";
                //echo $direccion;
                echo '</div>';
            }
        }
    }

    public function borrarPracticaController() {
        if (isset($_GET["nbArchivo"])) {

            $datosController = $_GET["nbArchivo"];
            $respuesta = PracticasModel::borrarPracticaModel($datosController);
            if ($respuesta == "ok") {
                header("location:index.php?action=borrarPractica");
            }
        }
    }

}
