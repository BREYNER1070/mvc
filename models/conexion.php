<?php
class Conexion {
    private $mySQLI;
    private $sql;
    private $result;
    private $filasAfectadas;

    public function conectar() {
        $host     = "localhost";
        $db       = "hoteleria";
        $user     = "root";
        $password = "";
        $this->mySQLI = new mysqli($host, $user, $password, $db);
        if (mysqli_connect_error()) {
            throw new Exception("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
        $this->mySQLI->set_charset("utf8mb4");
    }

    public function desconectar() {
        $this->mySQLI->close();
    }

    public function query($sql) {
        $this->sql    = $sql;
        $this->result = $this->mySQLI->query($sql);
        $this->filasAfectadas = $this->mySQLI->affected_rows;
    }

    public function getresult() {
        return $this->result;
    }

    public function getFilasAfectadas() {
        return $this->filasAfectadas;
    }

    public function escapar($valor) {
        return $this->mySQLI->real_escape_string($valor);
    }
}