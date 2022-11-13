<?php
//eduardo
class Producto
{
    public $id;
    public $area;
    public $id_orden_asociada;
    public $estado;
    public $descripcion;
    public $tipo;
    public $precio;
    public $tiempo_inicial;
    public $tiempo_entrega;

    public function __construct(){
    }
    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (area, id_orden_asociada, estado, descripcion, tipo ,precio)
        VALUES (:area, :id_orden_asociada , :estado , :descripcion , :tipo , :precio)");        
        $consulta->bindValue(':area', $this->area, PDO::PARAM_STR);
        $consulta->bindValue(':id_orden_asociada', $this->id_orden_asociada, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function getid_orden_asociada(){
        return $this->id_orden_asociada;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id)
    {        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        $Producto =$consulta->fetchObject('Producto');
        if(is_null($Producto)){
            throw new Exception("Producto no econtrado");
        }
        return $Producto;
    }

    public static function modificarProducto($Producto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Productos 
        SET 
        estado = :estado ,        
        tiempo_entrega = :tiempo_entrega
        WHERE id = :id",);
        $consulta->bindValue(':id', $Producto->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $Producto->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_entrega', $Producto->tiempo_entrega, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "retirado");
        $consulta->execute();
    }

  

    public function imprimirProductos($lista){
        echo "<table border='2'>";
        echo '<caption> Datos</caption>';
        echo "<th>[ID]</th>
        <th>[AREA]</th>
        <th>[ID ORDEN ASOCIADA]</th>
        <th>[ESTADO]</th>
        <th>[DESCRIPCION]</th>
        <th>[TIPO]</th>
        <th>[PRECIO]</th>
        <th>[TIEMPO INICIAL]</th>
        <th>[TIEMPO ENTREGA]</th>";
        foreach($lista as $entity){
           
                echo "<tr align='center'>";
                echo "<td>[".$entity->id."]</td>";
                echo "<td>[".$entity->area."]</td>";
                echo "<td>[".$entity->id_orden_asociada."]</td>";
                echo "<td>[".$entity->estado."]</td>";
                echo "<td>[".$entity->descripcion."]</td>";
                echo "<td>[".$entity->tipo."]</td>";
                echo "<td>[".$entity->precio."]</td>";
                echo "<td>[".$entity->tiempo_inicial."]</td>";
                echo "<td>[".$entity->tiempo_entrega."]</td>";
                echo "</tr>";
            
        }
            echo "</table>" ;
        }


}