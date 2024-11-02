<?php
/*
*	Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class Existencia extends Validator
{

 // Declaracion de atributos
 private $id_existencia = null;
 private $id_articulo = null;
 private $cantidad = null;
 private $fecha = null;


 //Metodos para validar y asignar valores de los atributos

    public function setId_existencia($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_existencia = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdArticulo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidad($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            return false;
        }
    }

//Metodos para obtener valores de los atributos 

    public function getId_existencia()
    {
        return $this->id_existencia;
    }

    public function getId_articulo()
    {
        return $this->id_articulo;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

/*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
*/

    public function searchRows($value)
    {
        $sql = 'SELECT te.*, ta.nombre_articulo FROM tb_existencia te, tb_articulo ta
                WHERE te.id_articulo = ta.id_articulo
                AND ta.nombre_articulo ILIKE ?';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }


    public function createRow()
    {
        $sql = 'INSERT into tb_existencia (id_articulo, cantidad, fecha)
                values (?, ?, ?)';
        $params = array($this->id_articulo,$this->cantidad, $this->fecha);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT te.*, ta.nombre_articulo FROM tb_existencia te, tb_articulo ta
                WHERE te.id_articulo = ta.id_articulo';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_categoria, categoria, estado
                FROM tb_categoria
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_categoria
        SET categoria=?, estado=?
        WHERE id_categoria=?';
        $params = array($this->categoria, $this->estado , $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_categoria
        WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}