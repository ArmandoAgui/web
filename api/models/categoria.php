<?php
/*
*	Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class Categoria extends Validator
{

    // Declaracion de atributos
    private $id_categoria = null;
    private $categoria = null;
    private $estado = null;


    //Metodos para validar y asignar valores de los atributos

    public function setIdCategoria($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCategoria($value)
    {
        if ($value) {
            if ($this->validateString($value, 1, 250)) {
                $this->categoria = $value;
                return true;
            } else {
                return false;
            }
        } else {
            $this->categoria = null;
            return true;
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

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function getCategoria()
    {
        return $this->categoria;
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
        $sql = 'SELECT id_categoria, categoria, estado
        FROM tb_categoria
        WHERE categoria ILIKE ? 
        ORDER BY categoria';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    /*
    *   Método para agregar registros (CREATE).
    */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_categoria(
            categoria, estado)
            VALUES (?, ?)';
        $params = array($this->categoria, $this->estado);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Método para mostrar todos los registros de una tabla (READ).
    */
    public function readAll()
    {
        $sql = 'SELECT *
                FROM tb_categoria
                ORDER BY categoria';
        $params = null;
        return Database::getRows($sql, $params);
    }

    /*
    *   Método para mostrar todos los datos de un registro.
    */
    public function readOne()
    {
        $sql = 'SELECT id_categoria, categoria, estado
                FROM tb_categoria
                WHERE id_categoria = ?';
        $params = array($this->id_categoria);
        return Database::getRow($sql, $params);
    }

    /*
    *   Método para actualizar un registro (UPDATE).
    */
    public function updateRow()
    {
        $sql = 'UPDATE tb_categoria
                SET categoria = ?
                WHERE id_categoria = ?';
        $params = array($this->categoria, $this->id_categoria);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Método para eliminar un registro (Cambio de estado) (DELETE).
    */
    public function deleteRow()
    {
        $sql = 'UPDATE tb_categoria
        SET estado = NOT estado
        WHERE id_categoria = ?';
        $params = array($this->id_categoria);
        return Database::executeRow($sql, $params);
    }
}
