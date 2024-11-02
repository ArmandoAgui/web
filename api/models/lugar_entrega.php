<?php
/*
*	Clase para manejar la tabla lugar entrega de la base de datos.
*   Es clase hija de Validator.
*/

class LugarEntrega extends Validator
{

    //Declaracion de atributos
    private $id = null;
    private $lugar = null;
    private $direccion = null;
    private $estado = null;

    //Metodos para validar y asignar valores de los atributos

    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setLugar($value)
    {
        if ($this->validateString($value, 1, 50)) {
            $this->lugar = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDireccion($value)
    {
        if ($this->validateString($value, 1, 200)) {
            $this->direccion = $value;
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

    public function getLugar()
    {
        return $this->lugar;
    }

    public function getDireccion()
    {
        return $this->direccion;
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
        $sql = 'SELECT id_lugar_entrega, lugar_entrega, direccion, estado
        FROM tb_lugar_entrega
        WHERE lugar_entrega ILIKE ? 
        ORDER BY lugar_entrega';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }


    public function createRow()
    {
        $sql = 'INSERT INTO tb_lugar_entrega(
        lugar_entrega, direccion, estado)
        VALUES (?, ?, ?)';
        $params = array($this->lugar, $this->direccion,  $this->estado);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT *
        FROM tb_lugar_entrega
        ORDER BY lugar_entrega';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_lugar_entrega, lugar_entrega, direccion, estado
        FROM tb_lugar_entrega
        WHERE id_lugar_entrega = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_lugar_entrega
        SET lugar_entrega=?, direccion=?, estado=?
        WHERE id_lugar_entrega=?';
        $params = array($this->lugar, $this->direccion, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'UPDATE tb_lugar_entrega
        SET estado = false
        WHERE id_lugar_entrega= ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
