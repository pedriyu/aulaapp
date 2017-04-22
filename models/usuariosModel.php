<?php

class UsuariosModel extends Datos {
    #registro de usuarios
    #------------------------------------

    public function registroUsuarioModel($datosModel, $tabla) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (user, password, nombre, apellido1, apellido2, email, frase_recuperacion, respuesta_frase_recuperacion) VALUES (:user, :password, :nombre, :apellido1, :apellido2, :email, :frase_recuperacion, :respuesta_frase_recuperacion)");

        $stmt->bindParam(":user", $datosModel["user"], PDO::PARAM_STR);
        $stmt->bindParam(":password", sha1($datosModel["password"]), PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datosModel["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido1", $datosModel["apellido1"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido2", $datosModel["apellido2"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datosModel["email"], PDO::PARAM_STR);
        $stmt->bindParam(":frase_recuperacion", $datosModel["pregunta"], PDO::PARAM_STR);
        $stmt->bindParam(":respuesta_frase_recuperacion", $datosModel["respuesta"], PDO::PARAM_STR);


        if ($stmt->execute()) {

            return "ok";
        } else {
            return "ko";
        }

        $stmt->close(); //cerramos la conexión cuando hemos terminado.
    }

#Login de usuarios
    #------------------------------------

    public function ingresoUsuarioModel($datosModel, $tabla) {
        //$stmt = Conexion::conectar()->prepare("SELECT id, user, password,rolID FROM $tabla WHERE user = :usuario");
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE user = :usuario");


        $stmt->bindParam(":usuario", $datosModel["user"], PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
        $stmt->close(); //cerramos la conexión cuando hemos terminado.
    }

    #Login de usuarios
    #------------------------------------

    public function vistaUsuariosModel($tabla) {

        $stmt = Conexion::conectar()->prepare("SELECT id,user,email FROM $tabla where autorizado=0");


        $stmt->execute();

        return $stmt->fetchAll(); //fetchAll porque obtiene todas las filas de un conjunto de resultados
        $stmt->close(); //cerramos la conexión cuando hemos terminado.
    }

    public function editarUsuarioModel($datosModel, $tabla) {
        $stmt = Conexion::conectar()->prepare("SELECT id,user, email FROM $tabla WHERE id = :id");

        $stmt->bindParam(":id", $datosModel, PDO::PARAM_INT); //aquí $datosModel como sólo da un resultado no hay que poner corchetes
        $stmt->execute();

        return $stmt->fetch(); //sólo una fila, la del usuario
        $stmt->close();
    }

    #Actualización  de usuarios
    #------------------------------------		

    public function actualizarUsuarioModel($datosModel, $tabla) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET user=:usuario,email=:email WHERE id=:id");

        $stmt->bindParam(":id", $datosModel["id"], PDO::PARAM_INT);
        $stmt->bindParam(":usuario", $datosModel["user"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datosModel["email"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "ko";
        }


        $stmt->close();
    }

    public function autorizarUsuarioModel($datosModel, $tabla) {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET autorizado=1 , rolID=3 WHERE id=$datosModel");

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "ko";
        }

        $stmt->close();
    }

    #BORRAR USUARIO
    #-----------------------------------------------

    public function borrarUsuarioModel($datosModel, $tabla) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id=$datosModel");

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "ko";
        }
        $stmt->close();
    }

}

//Fin clase UsuariosModel
?>