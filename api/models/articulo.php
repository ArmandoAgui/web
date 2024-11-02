<?php
/*
*	Clase para manejar la tabla tb_articulo de la base de datos.
*   Es clase hija de Validator.
*/
class Articulo extends Validator
{
    //Declaracion de atributos
    private $id_articulo = null;
    private $nombre_articulo = null;
    private $descripcion = null;
    private $precio = null;
    private $id_departamento = null;
    private $id_tipo_articulo = null;
    private $id_estado_articulo = null;
    private $id_categoria = null;
    private $imagen = null;
    private $id_marca = null;
    private $ruta = '../images/products/';


    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setIdArticulo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre_articulo($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setdescripcion($value)
    {
        if ($this->validateString($value, 1, 1000)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPrecio($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_departamento($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_departamento = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdTipoArticulo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_estado_articulo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_estado_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_categoria($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 5000, 5000)) {
            $this->imagen = $this->getFileName();
            return true;
        } else {
            return false;
        }
    }

    public function setId_marca($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_marca = $value;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para obtener valores de los atributos
    */

    public function getId_articulo()
    {
        return $this->id_articulo;
    }

    public function getNombre_articulo()
    {
        return $this->nombre_articulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getId_departamento()
    {
        return $this->id_departamento;
    }

    public function getId_tipo_articulo()
    {
        return $this->id_tipo_articulo;
    }

    public function getId_estado_articulo()
    {
        return $this->id_estado_articulo;
    }

    public function getId_categoria()
    {
        return $this->id_categoria;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getId_marca()
    {
        return $this->id_marca;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    /*-----------------------------------------------------------------------------*/


    //Metodo para mostrar todos los registros de una tabla (READ).
    public function readAll()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, descripcion, precio, tb_departamento.departamento, tb_tipo_articulo.tipo_articulo, tb_estado_articulo.estado_articulo, tb_marca.marca, tb_categoria.categoria, imagen
                FROM tb_articulo
                INNER JOIN tb_departamento
                ON tb_departamento.id_departamento = tb_articulo.id_departamento
                INNER JOIN tb_tipo_articulo
                ON tb_tipo_articulo.id_tipo_articulo = tb_articulo.id_tipo_articulo
                INNER JOIN tb_estado_articulo
                ON tb_estado_articulo.id_estado_articulo = tb_articulo.id_estado_articulo
                INNER JOIN tb_marca
                ON tb_marca.id_marca = tb_articulo.id_marca
                INNER JOIN tb_categoria
                ON tb_categoria.id_categoria = tb_articulo.id_categoria
                ORDER BY nombre_articulo';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para mostrar todos los registros de una tabla segun su id (READ).
    public function readOne()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, descripcion, precio, tb_departamento.departamento, tb_tipo_articulo.tipo_articulo, tb_estado_articulo.estado_articulo, tb_marca.marca, tb_categoria.categoria, stock, imagen
                FROM tb_articulo
                INNER JOIN tb_departamento
                ON tb_departamento.id_departamento = tb_articulo.id_departamento
                INNER JOIN tb_tipo_articulo
                ON tb_tipo_articulo.id_tipo_articulo = tb_articulo.id_tipo_articulo
                INNER JOIN tb_estado_articulo
                ON tb_estado_articulo.id_estado_articulo = tb_articulo.id_estado_articulo
                INNER JOIN tb_marca
                ON tb_marca.id_marca = tb_articulo.id_marca
                INNER JOIN tb_categoria
                ON tb_categoria.id_categoria = tb_articulo.id_categoria
                WHERE id_articulo = ?';
        $params = array($this->id_articulo);
        return Database::getRow($sql, $params);
    }

    //Metodo para buscar (SEARCH)
    public function searchRows($value)
    {
        $sql = 'SELECT id_articulo, nombre_articulo, precio, tb_departamento.departamento, tb_tipo_articulo.tipo_articulo, tb_estado_articulo.estado_articulo, imagen
                FROM tb_articulo
                INNER JOIN tb_departamento
                ON tb_departamento.id_departamento = tb_articulo.id_departamento
                INNER JOIN tb_tipo_articulo
                ON tb_tipo_articulo.id_tipo_articulo = tb_articulo.id_tipo_articulo
                INNER JOIN tb_estado_articulo
                ON tb_estado_articulo.id_estado_articulo = tb_articulo.id_estado_articulo
                WHERE nombre_articulo ILIKE ?
                OR tb_tipo_articulo.tipo_articulo ILIKE ?
                OR tb_estado_articulo.estado_articulo ILIKE ?
                ORDER BY nombre_articulo';
        $params = array("%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Método para mostrar todos los datos la tabla tipo empleado para llenar select
    public function readType()
    {
        $sql = 'SELECT * FROM tb_departamento';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readType1()
    {
        $sql = 'SELECT * FROM tb_estado_articulo';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para agregar un articulo (CREATE).
    public function createRow()
    {
        $sql = 'INSERT INTO public.tb_articulo(
            nombre_articulo, descripcion, precio, id_departamento, id_tipo_articulo, id_estado_articulo, id_marca, id_categoria, imagen)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_articulo, $this->descripcion, $this->precio, $this->id_departamento, $this->id_tipo_articulo, $this->id_estado_articulo, $this->id_marca, $this->id_categoria, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    //Metodo para actualizar un articulo(UPDATE)
    public function updateRow($current_image)
    {
        // Verificar base de datos tabla articulo, en mi base no aparecé el campo imagen
        ($this->imagen) ? $this->deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;

        $sql = 'UPDATE tb_articulo
        SET nombre_articulo= ?, descripcion= ?, precio= ?, id_departamento= ?, id_tipo_articulo= ?, id_estado_articulo=?, imagen= ?, id_marca= ?, id_categoria=?
        WHERE id_articulo = ?';
        $params = array($this->nombre_articulo, $this->descripcion, $this->precio, $this->id_departamento, $this->id_tipo_articulo, $this->id_estado_articulo, $this->imagen, $this->id_marca, $this->id_categoria, $this->id_articulo);
        return Database::executeRow($sql, $params);
    }

    //Método para gráfico de productos con menor stock
    public function stockArticulo()
    {
        $sql = 'SELECT nombre_articulo, stock
                FROM tb_articulo
                ORDER BY stock ASC
                LIMIT 5';
        $params = null;
        return Database::getRows($sql, $params);
    }

    /*Reportes------------------------------------------------*/

    //Metodo para obtener todos los departamentos de la tienda.
    public function readAllDeps()
    {
        $sql = 'SELECT *
                FROM tb_departamento';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para reportes de stock de productos por departamento
    public function stockArticulos()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, tipo_articulo, stock
                FROM tb_articulo
                INNER JOIN tb_tipo_articulo USING(id_tipo_articulo)
                WHERE id_departamento = ?
                ORDER BY nombre_articulo';
        $params = array($this->id_departamento);
        return Database::getRows($sql, $params);
    }

    //Metodo para obtener todos los estados de la tienda.
    public function readAllEstados()
    {
        $sql = 'SELECT *
                FROM tb_estado_articulo';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Metodo para generar reporte(Articulos organizados por estado)
    public function articulosEstado()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, tipo_articulo , precio
        FROM tb_articulo 
        INNER JOIN tb_tipo_articulo USING (id_tipo_articulo)
        WHERE id_estado_articulo = ?
        ORDER BY nombre_articulo';
        $params = array($this->id_estado_articulo);
        return Database::getRows($sql, $params);
    }
    
    //Método para reportes de stock de productos por marca
    public function stockMar()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, tipo_articulo, precio
                FROM tb_articulo
                INNER JOIN tb_tipo_articulo USING(id_tipo_articulo)
                WHERE id_marca = ?
                ORDER BY nombre_articulo';
        $params = array($this->id_marca);
        return Database::getRows($sql, $params);
    }

    /*--------------------------------------------------------*/

    //Metodo para mostrar los datos por departamentos de los articulos
    public function departmentArticle()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, precio, tb_departamento.departamento, tb_tipo_articulo.tipo_articulo, tb_estado_articulo.estado_articulo, imagen, tb_categoria.categoria
                FROM tb_articulo
                INNER JOIN tb_departamento
                ON tb_departamento.id_departamento = tb_articulo.id_departamento
                INNER JOIN tb_tipo_articulo
                ON tb_tipo_articulo.id_tipo_articulo = tb_articulo.id_tipo_articulo
                INNER JOIN tb_categoria
                ON tb_categoria.id_categoria = tb_articulo.id_categoria
				INNER JOIN tb_estado_articulo
				ON tb_estado_articulo.id_estado_articulo = tb_articulo.id_estado_articulo
                WHERE tb_articulo.id_departamento = ?
                ORDER BY nombre_articulo';
        $params = array($this->id_departamento);
        return Database::getRows($sql, $params);
    }

    //Metodo para filtrar los articulos por departamento, categoria y tipo articulo
    public function filterArticle()
    {
        $sql = 'SELECT id_articulo, nombre_articulo, precio, tb_departamento.departamento, tb_tipo_articulo.tipo_articulo, tb_estado_articulo.estado_articulo, imagen, tb_categoria.categoria
                FROM tb_articulo
                INNER JOIN tb_departamento
                ON tb_departamento.id_departamento = tb_articulo.id_departamento
                INNER JOIN tb_tipo_articulo
                ON tb_tipo_articulo.id_tipo_articulo = tb_articulo.id_tipo_articulo
                INNER JOIN tb_categoria
                ON tb_categoria.id_categoria = tb_articulo.id_categoria
				INNER JOIN tb_estado_articulo
				ON tb_estado_articulo.id_estado_articulo = tb_articulo.id_estado_articulo
                WHERE tb_articulo.id_departamento = ?
                AND tb_articulo.id_categoria = ?
                AND tb_articulo.id_tipo_articulo = ?
                ORDER BY nombre_articulo';
        $params = array($this->id_departamento, $this->id_categoria, $this->id_tipo_articulo);
        return Database::getRows($sql, $params);
    }
}
