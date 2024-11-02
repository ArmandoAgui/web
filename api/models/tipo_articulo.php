<?php
class TipoArticulo extends Validator
{
    /*Constructor*/
    /*aqui declaramos los campos dentro de la base de datos */
    private $id_tipo_articulo = null;
    private $tipo_articulo = null;
    private $estado = null;
    /*Metodos Set*/
    /*aqui mandamos a llamar esos campos*/
    public function setIdTipoArticulo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_articulo = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setTipoArticulo($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->tipo_articulo = $value;
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
    /*Metodos Get*/
    public function getId_tipo_articulo()
    {
        return $this->id_tipo_articulo;
    }
    public function gettipo_articulo()
    {
        return $this->tipo_articulo;
    }
    public function getestado()
    {
        return $this->estado;
    }

    /*Metodos SCRUD*/
    /*Función para buscar registros (Search)*/
    public function searchRows($value)
    {
        $sql = 'SELECT id_tipo_articulo, tipo_articulo, estado
                FROM tb_tipo_articulo
                WHERE tipo_articulo ILIKE ? OR tipo_articulo ILIKE ?';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    /* Función para leer todos los registros (Read) */
    public function readAll()
    {
        $sql = 'SELECT *
                FROM tb_tipo_articulo
                ORDER BY tipo_articulo';
        $params = null;
        return Database::getRows($sql, $params);
    }
    /*Función para obtener los datos de un registro*/
    public function readOne()
    {
        $sql = 'SELECT *
                FROM tb_tipo_articulo
                WHERE id_tipo_articulo = ?';
        $params = array($this->id_tipo_articulo);
        return Database::getRow($sql, $params);
    }
    /*Función para añadir un nuevo registro (Create)*/
    public function createRow()
    {
        $sql = 'INSERT INTO tb_tipo_articulo(
                tipo_articulo, estado)
                VALUES (?, ?)';
        $params = array($this->tipo_articulo, $this->estado);
        return Database::executeRow($sql, $params);
    }
    /*Función para actualizar un registro (Update)*/
    public function updateRow()
    {
        $sql = 'UPDATE tb_tipo_articulo
                SET tipo_articulo= ?
                WHERE id_tipo_articulo = ?';
        $params = array($this->tipo_articulo, $this->id_tipo_articulo);
        return Database::executeRow($sql, $params);
    }
    /*Método para eliminar un registro (Cambio de estado) (DELETE).*/
    public function deleteRow()
    {
        $sql = 'UPDATE tb_tipo_articulo
                SET estado = NOT estado
                WHERE id_tipo_articulo = ?';
        $params = array($this->id_tipo_articulo);
        return Database::executeRow($sql, $params);
    }
}
