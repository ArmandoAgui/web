<?php
// Clase para manejar la tabla usuario de la base de datos.
// Es clase hija de Validator.

class Usuario extends Validator
{
    // Declaracion de atributos
    private $id_usuario = null;
    private $nombre_usuario = null;
    private $contrasena = null;
    private $num_telefono = null;
    private $correo_electronico = null;
    private $nombre_completo = null;
    private $imagen = null;
    private $estado = null;
    private $ruta = '../images/profile_photos/';
    private $id_factura = null;

    //Metodos para validar y asignar valores de los atributos
    public function setIdUsuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdFactura($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_factura = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 20)) {
            $this->nombre_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContrasena($value)
    {
        if ($this->validatePassword($value)) {
            $this->contrasena = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setNumTelefono($value)
    {
        if ($this->validatePhone($value)) {
            $this->num_telefono = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreoElectronico($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo_electronico = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreCompleto($value)
    {
        if ($this->validateAlphabetic($value, 1, 100)) {
            $this->nombre_completo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 1000, 1000)) {
            $this->imagen = $this->getFileName();
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

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function getNumTelefono()
    {
        return $this->num_telefono;
    }

    public function getCorreoElectronico()
    {
        return $this->correo_electronico;
    }

    public function getNombreCompleto()
    {
        return $this->nombre_completo;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

  
    //Método para buscar registros
    public function searchRows($value)
    {
        $sql = 'SELECT id_usuario, nombre_usuario, contrasena, num_telefono, correo_electronico, nombre_completo, imagen, estado
                FROM tb_usuario
                WHERE nombre_usuario ILIKE ? OR nombre_completo ILIKE ?';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Método para mostrar todos los registros
    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, contrasena, num_telefono, correo_electronico, nombre_completo, imagen, estado
                FROM tb_usuario
                ORDER BY nombre_usuario';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para mostrar los datos de un registro
    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, contrasena, num_telefono, correo_electronico, nombre_completo, imagen, estado
                FROM tb_usuario
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }

    //Método para cambiar el estado de un usuario
    public function deleteRow()
    {
        $sql = 'UPDATE tb_usuario
                SET estado = NOT estado
                WHERE id_usuario= ?';
        $params = array($this->id_usuario);
        return Database::executeRow($sql, $params);
    }


    /*
    *   Métodos para reportes
    */
    //Método para reporte de clientes por departamento
    public function clientsDepartment()
    {
        $sql = 'SELECT depto, COUNT(id_usuario) clientes
            FROM tb_direccion
            INNER JOIN tb_municipio USING(id_municipio)
            INNER JOIN tb_depto USING(id_depto)
            INNER JOIN tb_usuario USING(id_usuario)
            WHERE id_tipo_direccion = 1
            AND estado = true
            GROUP BY depto';
        $params = null;
        return Database::getRows($sql, $params);
    }

    

    /*----------------------------------------------------------------*/

    /*Método para cargar el combo box de departamento
    public function readDepartment()
    {
        $sql = 'SELECT * FROM tb_depto';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Método para cargar el combo box de municipios
    public function readTown($value)
    {
        $sql = 'SELECT * FROM tb_municipio WHERE id_depto = ?';
        $params = array("$value");
        return Database::getRows($sql, $params);
    }*/

    //Método para agregar registros
    public function createRow()
    {
        $sql = 'INSERT INTO tb_usuario(nombre_usuario, contrasena, num_telefono, correo_electronico, nombre_completo, imagen, estado)
                VALUES (?, ?, ?, ?, ?, ?, true)';
        $params = array($this->nombre_usuario, $this->contrasena, $this->num_telefono, $this->correo_electronico, $this->nombre_completo, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    public function checkUser($usuario)
    {
        $sql = 'SELECT id_usuario FROM tb_usuario WHERE nombre_usuario = ? AND estado = true';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_usuario = $data['id_usuario'];
            $this->nombre_usuario = $usuario;
            return true;
        } else {
            return false;
        }
    }
    //Método para verificar contraseña de usuario
    public function checkPassword($password)
    {
        $sql = 'SELECT contrasena FROM tb_usuario WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    }

    //Método para obtener los datos de un usuario
    public function readProfile()
    {
        $sql = 'SELECT *
                FROM tb_usuario
                WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    //Método para obtener todas las ordenes de un usuario
    public function readAllOrders()
    {
        $sql = 'SELECT * FROM tb_factura WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRows($sql, $params);
    }

    //Método para obtener el detalle de una orden
    public function readOrderDetail()
    {
        $sql = 'SELECT * FROM tb_detalle_factura, tb_articulo 
                WHERE tb_detalle_factura.id_articulo = tb_articulo.id_articulo
                AND id_factura = ?';
        $params = array($this->id_factura);
        return Database::getRows($sql, $params);
    }
}