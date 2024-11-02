<?php

class Direccion extends Validator
{
    private $id_direccion = null;
    private $id_usuario = null;
    private $id_municipio = null;
    private $calle = null;
    private $id_tipo_direccion = null;

    public function setId_direccion($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_direccion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdUsuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_municipio($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_municipio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCalle($value)
    {
        if ($this->validateAlphanumeric($value, 1, 30)) {
            $this->calle = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_tipo_direccion($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_direccion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getId_direccion()
    {
        return $this->id_direccion;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function getId_municipio()
    {
        return $this->id_municipio;
    }

    public function getCalle()
    {
        return $this->calle;
    }

    public function getId_tipo_direccion()
    {
        return $this->id_tipo_direccion;
    }

    public function readAll()
    {
        $sql = 'SELECT id_direccion, tb_usuario.nombre_usuario, tb_municipio.municipio, calle, tb_tipo_direccion.tipo_direccion
                FROM tb_direccion
                INNER JOIN tb_usuario
                ON tb_usuario.id_usuario = tb_direccion.id_usuario
                INNER JOIN tb_municipio
                ON tb_municipio.id_municipio = tb_direccion.id_municipio
                INNER JOIN tb_tipo_direccion
                ON tb_tipo_direccion.id_tipo_direccion = tb_direccion.id_tipo_direccion';
        $params = null;
        return Database::getRow($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_direccion, tb_usuario.nombre_usuario, tb_municipio.municipio, calle, tb_tipo_direccion.tipo_direccion
                FROM tb_direccion
                INNER JOIN tb_usuario
                ON tb_usuario.id_usuario = tb_direccion.id_usuario
                INNER JOIN tb_municipio
                ON tb_municipio.id_municipio = tb_direccion.id_municipio
                INNER JOIN tb_tipo_direccion
                ON tb_tipo_direccion.id_tipo_direccion = tb_direccion.id_tipo_direccion
                WHERE id_direccion = ?';
        $params = array($this->id_direccion);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_direccion(id_usuario, id_municipio, calle, id_tipo_direccion)
                VALUES (?, ?, ?, ?)';
        $params = array($this->id_usuario, $this->id_municipio, $this->calle, $this->id_tipo_direccion);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_direccion
                SET id_usuario= ?, id_municipio= ?, calle= ?, id_tipo_direccion= ?
                WHERE id_direccion = ?';
        $params = array($this->id_usuario, $this->id_municipio, $this->calle, $this->id_tipo_direccion, $this->id_direccion);
        return Database::executeRow($sql, $params);
    }
}