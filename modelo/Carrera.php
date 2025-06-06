<?php
require_once 'Db.php';

class Carrera {

    private $id;
    private $planEstudio;
    private $alcance;
    private $cargaHoraria;
    private $nombre;
    private $descripcion;

    private $conection;

    public function getConection() {
        $dbObj= new DB();
        $this->conection = $dbObj->conection;
    }

    public function getCarreraAll() {
        $this->getConection();
        $sql="SELECT * FROM carrera";
        $st=$this->conection->prepare($sql); //prepara consulta sql para ejecutarla
        $st->execute(); //ejecucion de la sentencia
        return $st->fetchAll(PDO::FETCH_ASSOC); //el fetch es obtener   fetch trae 1 arreglo y fetchAll trae un arreglo de arreglos  
    }

    public function getCarreraUnica($id) {
        $this->getConection();
        $sql = "SELECT * FROM carrera WHERE idCarrera=?";
        $st = $this->conection->prepare($sql);
        $st->execute([$id]);
        return $st->fetch();
    }
}

$objCarrera = new Carrera();

var_dump ($objCarrera->getCarreraUnica(2));