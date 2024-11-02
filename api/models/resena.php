<?php
/*
*	Clase para manejar la tabla tb_resena de la base de datos.
*   Es clase hija de Validator.
*/
class Resena extends Validator
{

    // Declaracion de atributos
    private $id_resena = null;
    private $id_articulo = null;
    private $titulo = null;
    private $descripcion = null;
    private $estrellas = null;
    private $id_detalle_factura = null;
    private $estado = null;



    /*
    *   Métodos para validar y asignar valores de los atributos.
    */

    public function setIdResena($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_resena = $value;
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

    public function setTitulo($value)
    {
        if ($this->validateAlphanumeric($value, 1, 100)) {
            $this->titulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion($value)
    {
        if ($this->validateString($value, 1, 500)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstrellas($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estrellas = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdDetalleFactura($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_detalle_factura = $value;
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

    /*
    *   Métodos para obtener valores de los atributos
    */

    public function getIdResena()
    {
        return $this->id_resena;
    }

    public function getIdArticulo()
    {
        return $this->id_articulo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getEstrellas()
    {
        return $this->estrellas;
    }

    public function getIdDetalleFactura()
    {
        return $this->id_detalle_factura;
    }

    public function getEstado()
    {
        return $this->estado;
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    /*-----------------------------------------------------------------------------*/

    //Método para buscar reseñas por el id del articulo(sitio privado)
    public function searchRows($value)
    {
        $sql = 'SELECT tr.*, tu.nombre_completo, td.id_articulo 
                FROM tb_resena tr, tb_detalle_factura td, tb_factura tf, tb_usuario tu
                WHERE tr.id_detalle_factura = td.id_detalle_factura 
                AND td.id_factura = tf.id_factura 
                AND tf.id_usuario = tu.id_usuario
                AND td.id_articulo = ?
                ORDER BY id_resena';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    //Método actualizar el estado de una reseña
    public function updateState()
    {
        $sql = 'UPDATE tb_resena 
                SET estado = NOT estado 
                WHERE id_resena = ?';
        $params = array($this->id_resena);
        return Database::executeRow($sql, $params);
    }



    /*-----------------------------------------------------------------------------*/


    //Método para agregar una reseña (CREATE).
    public function createRow()
    {
        $sql = 'INSERT INTO tb_resena(titulo, descripcion, estrellas, id_detalle_factura, estado)
        VALUES (?, ?, ?, (select max (id_detalle_factura) from tb_detalle_factura td , tb_factura tf, tb_usuario tu 
               where td.id_factura = tf.id_factura
               and tf.id_usuario = tu.id_usuario
               and id_articulo = ?
               and tu.id_usuario = ?),true)';
        $params = array($this->titulo, $this->descripcion, $this->estrellas, $this->id_articulo, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }



    // Método para mostrar todos los registros de una tabla (READ).
    public function readAll()
    {
        $sql = 'SELECT tr.*, tu.nombre_completo 
                FROM tb_resena tr, tb_detalle_factura td, tb_factura tf, tb_usuario tu
                WHERE tr.id_detalle_factura = td.id_detalle_factura 
                AND td.id_factura = tf.id_factura 
                AND tf.id_usuario = tu.id_usuario
                ORDER BY id_resena';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para mostrar todos los registros de una tabla segun su id (READ).
    public function readOne()
    {
        $sql = 'SELECT id_categoria, categoria, estado
                FROM tb_categoria
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    //Método para actualizar un registro (UPDATE).
    public function updateRow()
    {
        $sql = 'UPDATE tb_categoria
            SET categoria=?, estado=?
            WHERE id_categoria=?';
        $params = array($this->categoria, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    //Método para eliminar un registro (Cambio de estado) (DELETE).
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_resena
            WHERE id_resena= ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Metodo para mostrar las reseñas de cada articulo(sitio publico)
    public function readReviewState($value)
    {
        $sql = 'SELECT tr.*, tu.nombre_completo, td.id_articulo 
                FROM tb_resena tr, tb_detalle_factura td, tb_factura tf, tb_usuario tu
                WHERE tr.id_detalle_factura = td.id_detalle_factura 
                AND td.id_factura = tf.id_factura 
                AND tf.id_usuario = tu.id_usuario
                AND td.id_articulo = ?
                AND tr.estado = true 
                ORDER BY id_resena';
        $params = array($value);
        return Database::getRows($sql, $params);
    }


    /*-----------------------------------------------------------------------------*/

    //Métodos para generar gráficas.

    public function mejorCalificacionProductos()
    {
        $sql = "SELECT ROUND(AVG(estrellas)) as promedio, nombre_articulo from tb_resena
                inner join tb_detalle_factura USING(id_detalle_factura)
                inner join tb_articulo USING (id_articulo)
                group by nombre_articulo order by promedio desc
                Limit 5";
        $params = null;
        return Database::getRows($sql, $params);
    }
}
