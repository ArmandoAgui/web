<?php
/*
*	Clase para manejar la tabla empleados de la base de datos.
*   Es clase hija de Validator.
*/
class Sugerencia extends Validator{

    // Declaración de atributos (propiedades).
    private $id_sugerencia = null;
    private $descripcion = null;
    private $id_tipo_sugerencia = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_sugerencia($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_sugerencia = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setDescripcion($value)
    {
        if ($this->validateAlphabetic($value, 1, 500)) {
            $this-> descripcion = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setId_tipo_sugerencia($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this-> id_tipo_sugerencia = $value;
            return true;
        } else {
            return false;
        }
    }
    

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_sugerencia()
    {
        return $this->id_sugerencia;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getId_tipo_sugerencia()
    {
        return $this->id_tipo_sugerencia;
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    /*
    *   Método para buscar ciertos registros (SEARCH).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT ts.id_sugerencia, ts.descripcion, tt.tipo_sugerencia 
                FROM tb_sugerencia ts, tb_tipo_sugerencia tt 
                WHERE ts.id_tipo_sugerencia = tt.id_tipo_sugerencia
                AND tt.tipo_sugerencia ILIKE ?';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }
    /*
    *   Método para agregar registros (CREATE).
    */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_sugerencia(descripcion, id_tipo_sugerencia)
                VALUES (?, ?)';
        $params = array($this->nombre_empleado, $this->usuario_empleado, $this->contrasena, $this->dui, 
                        $this->id_tipo_empleado, $this->estado, $this->correo_electronico);
        return Database::executeRow($sql, $params);
    }
    /*
    *   Método para mostrar todos los registros de una tabla (READ).
    */
    public function readAll()
    {
        $sql = 'SELECT ts.id_sugerencia, ts.descripcion, tt.tipo_sugerencia 
                FROM tb_sugerencia ts, tb_tipo_sugerencia tt 
                WHERE ts.id_tipo_sugerencia = tt.id_tipo_sugerencia';
        $params = null;
        return Database::getRows($sql, $params);
    }
    /*
    *   Método para buscar un solo registro.
    */
    public function readOne()
    {
        $sql = 'SELECT *
                FROM tb_sugerencia
                WHERE id_sugerencia = ?';
        $params = array($this->id_sugerencia);
        return Database::getRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_sugerencia
                WHERE id_sugerencia = ?';
        $params = array($this->id_sugerencia);
        return Database::executeRow($sql, $params);
    }
}