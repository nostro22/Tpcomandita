<?php
//eduardo
class Usuario
{
    public $id;
    public $nombre_usuario;
    public $clave;
    public $nombre;
    public $tipo;
    public $fecha_alta;
    public $fecha_baja;

    public function __construct(){
    }
    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre_usuario, clave, nombre, tipo)
        VALUES (:nombre_usuario, :clave , :nombre , :tipo)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre_usuario', $this->nombre_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function getClave(){
        return $this->clave;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($nombre_usuario)
    {        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $consulta->bindValue(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $consulta->execute();
        $usuario =$consulta->fetchObject('Usuario');
        if(is_null($usuario)){
            throw new Exception("Usuario no econtrado");
        }
        return $usuario;
    }

    public static function modificarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios 
        SET 
        nombre_usuario = :nombre_usuario,
        clave = :clave ,
        nombre = :nombre ,
        tipo = :tipo ,
        fecha_alta = :fecha_alta,
        fecha_baja = :fecha_baja
        WHERE id = :id",);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre_usuario', $usuario->nombre_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave',password_hash($usuario->clave, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $usuario->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', $usuario->fecha_alta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_baja', $usuario->tiempo_inicial, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function insertLogin($usuario){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO registros_login (id_usuario, nombre_usuario, fecha_login) 
        VALUES (:id_usuario, :nombre_usuario, :fecha_login)");
        $query->bindValue(':id_usuario', $usuario->id, PDO::PARAM_INT);
        $query->bindValue(':nombre_usuario', $usuario->nombre_usuario, PDO::PARAM_STR);
        $query->bindValue(':fecha_login', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }

    public function imprimirUsuarios($lista,$tipo){
        echo "<table border='2'>";
        echo '<caption> Datos</caption>';
        echo "<th>[ID]</th><th>[NOMBRE USUARIO]</th><th>[CLAVE]</th><th>[TIPO]</th><th>[ESTADO]</th><th>[FECHA DE CONTRATACION]</th>";
        foreach($lista as $entity){
            if($entity->tipo==$tipo || $tipo == "todos")
            {

                echo "<tr align='center'>";
                echo "<td>[".$entity->id."]</td>";
                echo "<td>[".$entity->nombre_usuario."]</td>";
                echo "<td>[".$entity->clave."]</td>";
                echo "<td>[".$entity->tipo."]</td>";
                if($entity->fecha_baja!=null)
                {
                    echo "<td>[Inactivo]</td>";
                }
                else{
                    echo "<td>[Activo]</td>";
                }
                echo "<td>[".$entity->fecha_alta."]</td>";
                echo "</tr>";
            }
        }
            echo "</table>" ;
        }

    /**
     * Prints the info of the query as a table.
     * @param array $entitiesList Array of the Users objects.
     */
    public static function imprimirUsuario($entitiesList){
        echo "<table border='2'>";
        echo '<caption>Users List</caption>';
        echo "<th>[ID]</th><th>[NOMBRE USUARIO]</th><th>[CLAVE]</th><th>[TIPO]</th><th>[ESTADO]</th><th>[FECHA DE CONTRATACION]</th>";
        foreach($entitiesList as $entity){
            echo "<tr align='center'>";
            echo "<td>[".$entity->getId()."]</td>";
            echo "<td>[".$entity->getUsername()."]</td>";
            echo "<td>[".$entity->getPassword()."]</td>";
            echo "<td>[".$entity->getIsAdmin()."]</td>";
            echo "<td>[".$entity->getUserType()."]</td>";
            echo "<td>[".$entity->getStatus()."]</td>";
            echo "<td>[".$entity->getDateInit()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }

}