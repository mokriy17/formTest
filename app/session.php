<?php

session_start();

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ../public/index.php");
        exit();
    }
}
