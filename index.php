<?php
$titulo = "Mi primer sitio modular con PHP";
$contenido = "elementos/contenido.php";
$p = $_GET['p'] ?? 'inicio';

include "layout.php";
