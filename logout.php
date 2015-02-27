<?php
require_once('db.php');

session_start();
session_destroy();

session_start();

$_SESSION['messages'] = array(
    array('status' => 'green', 'text' => 'Du är utloggad'),
);

header('Location: form.php');
