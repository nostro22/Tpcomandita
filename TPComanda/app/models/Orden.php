<?php
//eduardo

require_once './db/AccesoDatos.php';
require_once './models/Usuario.php';
require_once './models/Mesa.php';
require_once './models/UploadManager.php';

class Orden
{
    public $id;
    public $id_mesa;
    public $estado;
    public $nombre_cliente;
    public $imagen;
    public $costo;
    
    public function __construct(){
    }
    public function crearOrden()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ordenes (id_mesa, estado, nombre_cliente, imagen ,costo)
        VALUES (:id_mesa , :estado , :nombre_cliente , :imagen , :costo)");        
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':costo', $this->costo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function getid_mesa(){
        return $this->id_mesa;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ordenes");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Orden');
    }

    public static function obtenerOrden($id)
    {        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ordenes WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        $Orden =$consulta->fetchObject('Orden');
        if(is_null($Orden)){
            throw new Exception("Orden no econtrada");
        }
        return $Orden;
    }

    public static function modificarOrden($Orden)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ordenes 
        SET 
        estado = :estado,        
        costo = :costo
        WHERE id = :id",);
        $consulta->bindValue(':id', $Orden->id, PDO::PARAM_INT);        
        $consulta->bindValue(':estado', $Orden->estado, PDO::PARAM_STR);
        $consulta->bindValue(':costo', $Orden->costo, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarOrden($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ordenes SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "cancelada");
        $consulta->execute();
    }

    public static function updateImagen($orden){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('UPDATE ordenes SET imagen = :imagen WHERE id = :id');
        $query->bindValue(':id', $orden->id);
        $query->bindValue(':imagen', $orden->imagen);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function imprimirOrdens($lista,$estado){
        echo "<table border='2'>";
        echo '<caption> Datos</caption>';
        echo "<th>[ID]</th>
        <th>[ID MESA]</th>
        <th>[nombre_cliente]</th>
        <th>[ESTADO]</th>
        <th>[imagen]</th>
        <th>[costo]</th>";
        foreach($lista as $entity){
            if($entity->estado==$estado || $estado == "todos")
            {
                echo "<tr align='center'>";
                echo "<td>[".$entity->id."]</td>";
                echo "<td>[".$entity->id_mesa."]</td>";
                echo "<td>[".$entity->estado."]</td>";
                echo "<td>[".$entity->nombre_cliente."]</td>";
                echo "<td>[".$entity->imagen."]</td>";
                echo "<td>[".$entity->costo."]</td>";
                echo "</tr>";
            }
        }
            echo "</table>" ;
        }

        public static function getMaximaTardanzaOrden($id_orden, $codigo_mesa){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta(
                'SELECT 
                 timestampdiff (minutes,p.tiempo_inicial , p.tiempo_entrega) AS timer 
                FROM productos AS p
                LEFT JOIN ordenes as o
                ON p.id_orden_asociada = :id_orden
                LEFT JOIN mesas AS m
                ON o.id_mesa = m.id
                WHERE m.codigo:mesa = :codigo_mesa');
            $query->bindParam(':codigo_mesa', $codigo_mesa);
            $query->bindParam(':id_orden', $id_orden);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

     
}