<?php
    session_start();
    require_once "config/config.php";
    require_once "models/conexion.php";
    require_once "controller/controllerBase.php";
    require_once "controller/controllerReserva.php";
    require_once "controller/controllerPdf.php";
    require_once "controller/controllerExcel.php";
    require_once "controller/controllerEmail.php";

    $controllerBase    = new ControllerBase();
    $controllerReserva = new ControllerReserva();
    $controllerPDF     = new ControllerPdf();
    $controllerExcel   = new ControllerExcel();
    $controllerEmail   = new ControllerEmail();

    $action = $_GET['action'] ?? '';

    switch ($action) {

        // ─── Auth ──────────────────────────────────────────────────────────────
        case 'getFormRegisterUser':
            $controllerBase->verPaginaInicio("view/html/auth/register.php"); break;
        case 'registerUser':
            $controllerBase->registerUser(); break;
        case 'getFormLoginUser':
            $controllerBase->verPaginaInicio("view/html/auth/login.php"); break;
        case 'loginUser':
            $controllerBase->loginUser(); break;
        case 'logoutUser':
            $controllerBase->logoutUser(); break;

        // ─── Páginas ───────────────────────────────────────────────────────────
        case 'home':
            $controllerBase->verPaginaInicio("view/html/home.php"); break;
        case 'nombre':
            $controllerBase->verPaginaInicio("view/html/nombre.php"); break;

        // ─── Vistas de reservas ────────────────────────────────────────────────
        case 'getFormReserva':
            $controllerReserva->getFormReserva(); break;
        case 'getMisReservas':
            $controllerReserva->getMisReservas(); break;

        // ─── AJAX: CRUD de reservas ────────────────────────────────────────────
        case 'getCategorias':
            $controllerReserva->getCategorias(); break;
        case 'getHabitacionesDisponibles':
            $controllerReserva->getHabitacionesDisponibles(); break;
        case 'crearReserva':
            $controllerReserva->crearReserva(); break;
        case 'getReserva':
            $controllerReserva->getReserva(); break;
        case 'actualizarReserva':
            $controllerReserva->actualizarReserva(); break;
        case 'cancelarReserva':
            $controllerReserva->cancelarReserva(); break;

        
        case 'descargarPDFReserva':
            $controllerPDF->descargarPDFReserva(); break;
        case 'descargarExcelReservas':
            $controllerExcel->descargarExcelReservas(); break;
        case 'enviarEmail':
            $controllerEmail->enviarEmail(); break;

        default:
            $controllerBase->verPaginaInicio("view/html/home.php"); break;
    }
?>
