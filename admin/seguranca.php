<?php

session_start();
if (!isset($_SESSION['liberado']) || $_SESSION['liberado']!=true){
    \App\Controller::errorPermission();
}
