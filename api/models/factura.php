<?php

class Carrito extends Validator
{
    //Se crean los atributos de la clase
    //Tabla factura
    private $id_factura = null;
    private $id_usuario = null;
    private $fecha = null;
    private $id_estado_factura = null;
    private $id_lugar_entrega = null;
    private $total = null;

    //Se crean los atributos de la clase
    //Tabla detalle pedido
    private $id_detalle_factura = null;
    private $id_articulo = null;
    private $cantidad = null;
    private $precio_articulo = null;
    private $sub_total = null;

    //Metodos SET de factura
    public function setIdFactura($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_factura = $value;
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

    public function setIdEstadoFactura($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_estado_factura = $value;
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

    public function setIdLugarEntrega($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_lugar_entrega = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTotal($value)
    {
        if ($this->validateMoney($value)) {
            $this->total = $value;
            return true;
        } else {
            return false;
        }
    }

    // SER tabla detalle

    public function setIdDetalleFactura($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_detalle_factura = $value;
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

    public function setPrecioArticulo($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio_articulo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setSubTotal($value)
    {
        if ($this->validateMoney($value)) {
            $this->sub_total = $value;
            return true;
        } else {
            return false;
        }
    }


    //Metodos GET de factura
    public function getId_factura()
    {
        return $this->id_factura;
    }

    public function getId_estado_factura()
    {
        return $this->id_estado_factura;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getId_lugar_entrega()
    {
        return $this->id_lugar_entrega;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    //GET Tabla detalle

    public function getId_detalle_factura()
    {
        return $this->id_detalle_factura;
    }


    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function getPrecio_articulo()
    {
        return $this->precio_articulo;
    }

    public function getId_articulo()
    {
        return $this->id_articulo;
    }

    public function getSub_total()
    {
        return $this->sub_total;
    }


    /* Métodos sitio privado */

    // Método para obtener todos los pedidos.
    public function readAll()
    {
        $sql = 'SELECT tf.id_factura, tu.nombre_usuario, tf.fecha, tf.total, tl.lugar_entrega, te.estado_factura
                FROM tb_factura tf
                INNER JOIN tb_usuario AS tu USING(id_usuario)
                INNER JOIN tb_lugar_entrega AS tl USING(id_lugar_entrega)
                INNER JOIN tb_estado_factura AS te USING(id_estado_factura)
                ORDER BY tf.id_factura';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function searchRows($value)
    {
        $sql = 'SELECT tf.id_factura, tu.nombre_usuario, tf.fecha, tf.total, tl.lugar_entrega, te.estado_factura
                FROM tb_factura tf
                INNER JOIN tb_usuario AS tu USING(id_usuario)
                INNER JOIN tb_lugar_entrega AS tl USING(id_lugar_entrega)
                INNER JOIN tb_estado_factura AS te USING(id_estado_factura)
                WHERE tu.nombre_usuario ILIKE ? 
                ORDER BY tf.id_factura';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    /*
    *   Método para mostrar todos los datos de un registro.
    */
    public function readOne()
    {
        $sql = 'SELECT tf.total, tf.id_estado_factura, tl.lugar_entrega, tu.nombre_completo, 
                tu.num_telefono, tu.correo_electronico
                from tb_factura tf
                INNER JOIN tb_usuario AS tu USING(id_usuario)
                INNER JOIN tb_lugar_entrega AS tl USING(id_lugar_entrega)
                WHERE id_factura = ?';
        $params = array($this->id_factura);
        return Database::getRow($sql, $params);
    }

    // Método para obtener todos los estados de pedidos.
    public function readState()
    {
        $sql = 'SELECT *
                FROM tb_estado_factura';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_factura
                SET id_estado_factura = ?
                WHERE id_factura = ?';
        $params = array($this->id_estado_factura, $this->id_factura);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para generar gráficas.
    */
    public function ventasMes()
    {
        $sql = "SELECT SUM(total) AS total, to_char(fecha, 'TMMonth') as mes 
                FROM tb_factura
                WHERE to_char(fecha, 'yyyy') = to_char(now(), 'yyyy')
                GROUP BY mes 
                ORDER BY mes DESC";
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function ventasTopProductosMes()
    {
        $sql = "SELECT nombre_articulo, ROUND(SUM(cantidad) * 100/
                (SELECT SUM(cantidad) 
                FROM tb_detalle_factura
                INNER JOIN tb_factura USING(id_factura)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')), 2) cantidad
                FROM tb_detalle_factura
                INNER JOIN tb_articulo USING(id_articulo)
                INNER JOIN tb_factura USING(id_factura)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')
                GROUP BY nombre_articulo 
                ORDER BY cantidad DESC
                LIMIT 5";
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function ventasDepartamentoMes()
    {
        $sql = "SELECT departamento, ROUND(SUM(cantidad) * 100/
                (SELECT SUM(cantidad) 
                FROM tb_detalle_factura
                INNER JOIN tb_factura USING(id_factura)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')), 2) cantidad
                FROM tb_detalle_factura
                INNER JOIN tb_articulo USING(id_articulo)
                INNER JOIN tb_departamento USING(id_departamento)
                INNER JOIN tb_factura USING(id_factura)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')
                GROUP BY departamento 
                ORDER BY cantidad DESC";
        $params = null;
        return Database::getRows($sql, $params);
    }


    /* 
    *   Métodos para reportes    
    */
    //Reporte clientes frecuentes por cantidad de ventas
    public function clientesVentas()
    {
        $sql = "SELECT nombre_completo, correo_electronico, COUNT(id_factura) cantidad, estado
                FROM tb_factura
                INNER JOIN tb_usuario USING(id_usuario)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')
                GROUP BY nombre_completo, correo_electronico, estado
                ORDER BY cantidad DESC, nombre_completo
                LIMIT 10";
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function clientesMonto()
    {
        $sql = "SELECT nombre_completo, correo_electronico, SUM(total) total, estado
                FROM tb_factura
                INNER JOIN tb_usuario USING(id_usuario)
                WHERE to_char(fecha, 'mm') = to_char(now(), 'mm')
                GROUP BY nombre_completo, correo_electronico, estado
                ORDER BY total DESC, nombre_completo
                LIMIT 10";
        $params = null;
        return Database::getRows($sql, $params);
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    // Método para verificar si existe un pedido en proceso para seguir comprando, de lo contrario se crea uno.
    public function startOrder()
    {
        $this->id_estado = 1;
        $this->id_lugar_entrega=1;
        $this->total=0;
        // Se establece la zona horaria local para obtener la fecha del servidor.
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');

        $sql = 'SELECT id_factura FROM tb_factura WHERE id_usuario = ? AND id_estado_factura = ?';
        $params = array($_SESSION['id_usuario'], $this->id_estado);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_factura = $data['id_factura'];
            return true;
        } else {
            $sql = 'INSERT INTO tb_factura(
                id_usuario, fecha, id_estado_factura, id_lugar_entrega, total)
                VALUES (?, ?, ?, ?, ?)';
            $params = array($_SESSION['id_usuario'], $date, $this->id_estado, $this->id_lugar_entrega, $this->total);
            // Se obtiene el ultimo valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_factura = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finishOrder()
    {
        // Se establece la zona horaria local para obtener la fecha del servidor.
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $this->estado = 2;
        $sql = 'UPDATE tb_factura
                SET id_estado_factura = ?, fecha= ?, total= ?
                WHERE id_factura = ?';
        $params = array($this->estado, $date, $this->total, $_SESSION['id_factura']);
        return Database::executeRow($sql, $params);
    }


    // Método para agregar un producto al carrito de compras.
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO tb_detalle_factura(
                id_articulo, cantidad, precio_articulo, id_factura, sub_total)
                VALUES (?, ?, (SELECT precio FROM tb_articulo WHERE id_articulo = ?), ?, ?)';
        $params = array($this->id_articulo, $this->cantidad, $this->id_articulo, $this->id_factura, $this->sub_total);
        return Database::executeRow($sql, $params);
    }
    
    // Método para obtener los productos que se encuentran en el carrito de compras.
    public function readOrderDetail()
    {
        $sql = 'SELECT id_detalle_factura, ta.imagen, ta.nombre_articulo, tt.tipo_articulo, tc.categoria, cantidad, precio_articulo, sub_total
                FROM tb_detalle_factura
                INNER JOIN tb_articulo AS ta USING(id_articulo)
                INNER JOIN tb_tipo_articulo AS tt USING(id_tipo_articulo)
                INNER JOIN tb_categoria AS tc USING(id_categoria)
                INNER JOIN tb_factura AS tf USING(id_factura)
                WHERE tf.id_factura = ?';
        $params = array($this->id_factura);
        return Database::getRows($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        $sql = 'UPDATE tb_detalle_factura
        SET cantidad= ?, sub_total= ?
        WHERE id_detalle_factura = ?';
        $params = array($this->cantidad, $this->sub_total, $this->id_detalle_factura);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM tb_detalle_factura
        WHERE id_detalle_factura = ? AND id_factura = ?';
        $params = array($this->id_detalle_factura, $_SESSION['id_factura']);
        return Database::executeRow($sql, $params);
    }
}