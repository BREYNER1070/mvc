<?php
class User{
    public function validateUser($data){
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT * FROM users WHERE correo = '$data[correo]'";
        $conexion->query($sql);
        $result = $conexion->getresult();   
        $conexion->desconectar();
        if ($result->num_rows > 0) {
            return 1;
        }
        return 0;
    }
    public function registerUser($data){
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "INSERT INTO users (tipo_documento_id, documento_numero, nombre, correo, password) 
                VALUES ('$data[document_type_id]', '$data[document_number]', '$data[nombre]', '$data[correo]', '$data[password]')";
        $conexion->query($sql);
        return $conexion->getFilasAfectadas();
} 
    public function loginUser($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $correo = $data['correo'];
        $sql = "SELECT * FROM users WHERE correo = '$correo' LIMIT 1";
        $conexion->query($sql);
        $result = $conexion->getresult();
        $conexion->desconectar();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    
}
    
}