<?php
    class ControllerBase {

        public function validateData($data) {
            $errores = [];

            if (!isset($data['document_type_id']) || $data['document_type_id'] == "") {
                $errores['document_type_id'] = "El tipo de documento es obligatorio";
            }

            if (empty(trim($data['document_number']))) {
                $errores['document_number'] = "El número de documento es obligatorio";
            } elseif (!preg_match("/^[0-9]+$/", trim($data['document_number']))) {
                $errores['document_number'] = "El número de documento solo puede contener números";
            }

            if (empty(trim($data['nombre']))) {
                $errores['nombre'] = "El nombre es obligatorio";
            }

            if (empty(trim($data['correo']))) {
                $errores['correo'] = "El correo es obligatorio";
            } elseif (!filter_var(trim($data['correo']), FILTER_VALIDATE_EMAIL)) {
                $errores['correo'] = "El correo no es válido";
            }

            if (empty(trim($data['password']))) {
                $errores['password'] = "La contraseña es obligatoria";
            } elseif (strlen(trim($data['password'])) < 6) {
                $errores['password'] = "La contraseña debe tener al menos 6 caracteres";
            }

            if (empty(trim($data['confirmar_password']))) {
                $errores['confirmar_password'] = "Confirme su contraseña";
            } elseif ($data['password'] !== $data['confirmar_password']) {
                $errores['confirmar_password'] = "Las contraseñas no coinciden";
            }

            return $errores;
        }

        public function verPaginaInicio($pagina) {
            include_once $pagina;
        }

        public function registerUser() {
            unset($_SESSION['errors']);
            unset($_SESSION['success']);
            unset($_SESSION['old']);

            $datos = [
                'document_type_id'    => $_POST['document_type_id']    ?? '',
                'document_number'     => $_POST['document_number']     ?? '',
                'nombre'              => $_POST['nombre']              ?? '',
                'correo'              => $_POST['correo']              ?? '',
                'password'            => $_POST['password']            ?? '',
                'confirmar_password'  => $_POST['confirmar_password']  ?? '',
            ];

            $errores = $this->validateData($datos);

            if (count($errores) > 0) {
                $_SESSION['errors'] = $errores;
                $_SESSION['old']    = $datos;
                header("Location: " . SITE_URL . "index.php?action=getFormRegisterUser");
                exit;
            }

            require_once "models/user.php";
            $user   = new User();
            $existe = $user->validateUser($datos);

            if ($existe > 0) {
                $_SESSION['errors'] = ["general" => "El correo ya está registrado"];
                $_SESSION['old']    = $datos;
                header("Location: " . SITE_URL . "index.php?action=getFormRegisterUser");
                exit;
            }

            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            $resultado = $user->registerUser($datos);

            if ($resultado > 0) {
                require_once "controller/ControllerEmail.php";
                $controllerEmail = new ControllerEmail();
                $controllerEmail->enviarEmail($datos['nombre'], $datos['correo']);
                $_SESSION['success'] = "Usuario registrado correctamente";
                header("Location: " . SITE_URL . "index.php?action=getFormLoginUser");
                exit;
            } else {
                $_SESSION['errors'] = ["general" => "Error al registrar el usuario"];
                $_SESSION['old']    = $datos;
                header("Location: " . SITE_URL . "index.php?action=getFormRegisterUser");
                exit;
            }
        }
        public function loginUser() {           
            unset($_SESSION['errors']);

            $datos = [
                'correo'   => $_POST['correo']   ?? '',
                'password' => $_POST['password'] ?? '',
            ];

            // Validación básica
            $errores = [];
            if (empty(trim($datos['correo']))) {
                $errores['correo'] = "El correo es obligatorio";
            }
            if (empty(trim($datos['password']))) {
                $errores['password'] = "La contraseña es obligatoria";
            }

            if (count($errores) > 0) {
                $_SESSION['errors'] = $errores;
                $_SESSION['old']    = $datos;
                header("Location: " . SITE_URL . "index.php?action=getFormLoginUser");
                exit;
            }

            require_once "models/user.php";
            $user     = new User();
            $usuario  = $user->loginUser($datos);

            if ($usuario === null || !password_verify($datos['password'], $usuario['password'])) {
                $_SESSION['errors'] = ["general" => "Correo o contraseña incorrectos"];
                $_SESSION['old']    = $datos;
                header("Location: " . SITE_URL . "index.php?action=getFormLoginUser");
                exit;
            }

            // Guardar en sesión
            $_SESSION['usuario'] = [
                
                'id'     => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
            ];

            header("Location: " . SITE_URL . "index.php?action=home");
            exit;
}

        public function logoutUser() {
            session_destroy();
            header("Location: " . SITE_URL . "index.php?action=home");
            exit;
    }
        
    }
?>
