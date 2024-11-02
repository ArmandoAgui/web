<?php
/*
*	Clase para manejar la tabla tb_empleado de la base de datos.
*   Es clase hija de Validator.
*/
class Empleado extends Validator
{

    // Declaración de atributos (propiedades).
    private $id_empleado = null;
    private $nombre_empleado = null;
    private $usuario_empleado = null;
    private $contrasena = null;
    private $dui = null;
    private $id_tipo_empleado = null;
    private $estado = null;
    private $correo_electronico = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_empleado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setNombre_empleado($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario_empleado($value)
    {
        if ($this->validateAlphabetic($value, 1, 30)) {
            $this->usuario_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContrasena($value)
    {
        if ($this->validatePassword($value)) {
            $this->contrasena = password_hash($value, PASSWORD_DEFAULT);;
            return true;
        } else {
            return false;
        }
    }

    public function setDUI($value)
    {
        if ($this->validateDUI($value, 1, 10)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdTipoEmpleado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_empleado = $value;
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

    public function setCorreo_electronico($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo_electronico = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_empleado()
    {
        return $this->id_empleado;
    }
    public function getNombre_empleado()
    {
        return $this->nombre_empleado;
    }
    public function getUsuario_empleado()
    {
        return $this->usuario_empleado;
    }
    public function getContrasena()
    {
        return $this->contrasena;
    }
    public function getDUI()
    {
        return $this->dui;
    }
    public function getId_tipo_empleado()
    {
        return $this->id_tipo_empleado;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCorreo_electronico()
    {
        return $this->correco_electronico;
    }

    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    public function checkUser($usuario_empleado)
    {
        $sql = 'SELECT id_empleado FROM tb_empleado WHERE usuario_empleado = ?';
        $params = array($usuario_empleado);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_empleado = $data['id_empleado'];
            $this->usuario_empleado = $usuario_empleado;
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT contrasena FROM tb_empleado WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    /*
    *   Método para buscar ciertos registros (SEARCH).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT te.*, tt.tipo_empleado
                FROM tb_empleado te, tb_tipo_empleado tt
                WHERE te.id_tipo_empleado = tt.id_tipo_empleado
                AND te.usuario_empleado ILIKE ?';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }
    /*
    *   Método para agregar registros (CREATE).
    */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_empleado(nombre_empleado, usuario_empleado, contrasena, dui, id_tipo_empleado, estado, correo_electronico)
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->nombre_empleado, $this->usuario_empleado, $this->contrasena, $this->dui,
            $this->id_tipo_empleado, $this->estado, $this->correo_electronico
        );
        return Database::executeRow($sql, $params);
    }
    /*
    *   Método para mostrar todos los registros de una tabla (READ).
    */
    public function readAll()
    {
        $sql = 'SELECT te.*, tt.tipo_empleado
                FROM tb_empleado te, tb_tipo_empleado tt
                WHERE te.id_tipo_empleado = tt.id_tipo_empleado';
        $params = null;
        return Database::getRows($sql, $params);
    }
    /*
    *   Método para mostrar todos los datos la tabla tipo empleado para llenar select
    */
    public function readType()
    {
        $sql = 'SELECT * FROM tb_tipo_empleado';
        $params = null;
        return Database::getRows($sql, $params);
    }
    /*
    *   Método para mostrar todos los registros de una tabla (READ).
    */
    public function readOne()
    {
        $sql = 'SELECT *
        FROM tb_empleado
        WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::getRow($sql, $params);
    }

    /*
    *   Método para actualizar un registro (UPDATE).
    */
    public function updateRow()
    {
        $sql = 'UPDATE tb_empleado
                SET id_tipo_empleado= ?
                WHERE id_empleado = ?';
        $params = array($this->id_tipo_empleado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Método para eliminar un registro (DELETE).
    */
    public function deleteRow()
    {
        $sql = 'DELETE FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
