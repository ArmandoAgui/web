<?php
/*
*	Clase para manejar la tabla marca de la base de datos.
*   Es clase hija de Validator.
*/

class Marca extends Validator
{

    //Declaracion de atributos
    private $id = null;
    private $marca = null;
    private $estado = null;


    //Metodos para valorar y asignar valores de los atributos
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setMarca($value)
    {
        if ($this->validateString($value, 1, 100)) {
            $this->marca = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if ($this->validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    //Metodos para obtener valores de los atributos 

    public function getId()
    {
        return $this->id;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getEstado()
    {
        return $this->estado;
    }
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    public function searchRows($value)
    {
        $sql = 'SELECT id_marca, marca, estado
            FROM tb_marca
            WHERE marca ILIKE ? 
            ORDER BY marca';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }


    public function createRow()
    {
        $sql = 'INSERT INTO tb_marca(
                marca, estado)
                VALUES (?, ?)';
        $params = array($this->marca, $this->estado);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT *
                FROM tb_marca
                ORDER BY marca';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_marca, marca, estado
                FROM tb_marca
                WHERE id_marca = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_marca
                SET marca=?, estado=?
                WHERE id_marca = ?';
        $params = array($this->marca, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'UPDATE tb_marca
            SET estado = false
            WHERE id_marca= ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAllMar()
    {
        $sql = 'SELECT *
                FROM tb_marca';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
