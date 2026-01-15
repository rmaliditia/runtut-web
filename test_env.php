<?php

$env = parse_ini_file(__DIR__ . '/.env');
// Coba ambil satu data
$db_host = $env['DB_HOST'];
var_dump($db_host);