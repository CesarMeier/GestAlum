<?php
require_once 'Db.php';

class Persona {

    private $id;
    private $dni;
    private $nombre;
    private $apellido;
    private $email;
    private $telefono;
    private $cuil;

    private $conection;

    public function getConection() {
        $dbObj=new DB();
        $this->conection = $dbObj->conection;
    }

    public function getPersona() { 
        $this->getConection();
        $sql="SELECT * FROM persona";
        $stmt=$this->conection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonaById($id) {
        $this->getConection();
        $sql="SELECT * FROM persona WHERE idPersona=?";
        $stmt=$this->conection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function save($param) {
        $this->getConection();
        $exists = false;

        if (!empty($param['idPersona'])) {
            $actualInstancia = $this->getPersonaById($param['idPersona']);
            if (!empty($actualInstancia) && isset($actualInstancia['idPersona'])) {
                $exists = true;
                $this->id = $actualInstancia['idPersona'];
            }
        }

        // Asignar valores con validación básica
        $this->dni = $param['dni'] ?? $this->dni;
        $this->nombre = $param['nombre'] ?? $this->nombre;
        $this->apellido = $param['apellido'] ?? $this->apellido;
        $this->email = (isset($param['email']) && filter_var($param['email'], FILTER_VALIDATE_EMAIL)) ? $param['email'] : $this->email;
        $this->telefono = $param['telefono'] ?? $this->telefono;
        $this->cuil = $param['cuil'] ?? $this->cuil;

        try {
            if ($exists) {
                $sql = "UPDATE persona SET dni=?, nombre=?, apellido=?, email=?, telefono=?, cuil=? WHERE idPersona=?";
                $stmt = $this->conection->prepare($sql);
                $stmt->execute([$this->dni, $this->nombre, $this->apellido, $this->email, $this->telefono, $this->cuil, $this->id]);
            } else {
                $sql = "INSERT INTO persona (dni, nombre, apellido, email, telefono, cuil) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conection->prepare($sql);
                $stmt->execute([$this->dni, $this->nombre, $this->apellido, $this->email, $this->telefono, $this->cuil]);
                $this->id = $this->conection->lastInsertId();
            }
        } catch (PDOException $e) {
            // Aquí puedes loguear el error
            return false;
        }

        return $this->id;
    }
    
    public function existePorDNI($dni) {
        $this->getConection();
        $sql = "SELECT COUNT(*) FROM persona WHERE dni = ?";
        $stmt=$this->conection->prepare($sql);
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public function existePorEmail($email) {
        $this->getConection();
        $sql = "SELECT COUNT(*) FROM persona WHERE email = ?";
        $stmt=$this->conection->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function existePorCUIL($cuil) {
        $this->getConection();
        $sql = "SELECT COUNT(*) FROM persona WHERE cuil = ?";
        $stmt=$this->conection->prepare($sql);
        $stmt->execute([$cuil]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id){
        $this->getConection();
        $sql = "DELETE FROM persona WHERE idPersona= ?";
        $stmt=$this->conection->prepare($sql);
        return $stmt->execute([$id]);
    }

}

//$objPersona = new Persona();
//var_dump($objPersona->getPersona());
//$objPersona->delete();
//var_dump ($objPersona->delete(21));
//$objPersona->save(['idPersona' => '21', 'dni' => '44444444','nombre' => 'cesar', 'apellido'=>'meier', 'email'=>'cesarmeier@gmail.com', 'telefono'=>'3857435262', 'cuil'=>'20-44135797-5']);