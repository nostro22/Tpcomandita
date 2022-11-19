<?php
//eduardo
class Mesa
{
    public $id;
    public $prefix;
    public $id_personal;
    public $estado;
    
    public function __construct(){
    }
    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, id_personal)
        VALUES (:estado , :id_personal )");        
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_personal', $this->id_personal, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function getprefix(){
        return $this->prefix;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        $Mesa =$consulta->fetchObject('Mesa');
        if(is_null($Mesa)){
            throw new Exception("Mesa no econtrada");
        }
        return $Mesa;
    }

    public static function modificarMesa($Mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas 
        SET 
        estado = :estado ,
        id_personal = :id_personal
        WHERE id = :id",);
        $consulta->bindValue(':id', $Mesa->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $Mesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_personal', $Mesa->id_personal, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "cerrada");
        $consulta->execute();
    }

  

    public function imprimirMesas($lista,$estado){
        echo "<table border='2'>";
        echo '<caption> Datos</caption>';
        echo "<th>[ID]</th>
        <th>[ID MOZO]</th>
        <th>[ESTADO]</th>";
        foreach($lista as $entity){
            if($entity->estado==$estado || $estado == "todos")
            {
                echo "<tr align='center'>";                
                $codigoMesa = $entity->prefix. sprintf("%03d", $entity->id);
                echo "<td>[".$codigoMesa."]</td>";
                echo "<td>[".$entity->id_personal."]</td>";
                echo "<td>[".$entity->estado."]</td>";
                echo "</tr>";
            }
        }
            echo "</table>" ;
        }


}