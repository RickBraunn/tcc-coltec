<?php

session_start();
if (!isset($_SESSION['liberado']) || $_SESSION['liberado']!=1){
    \App\Controller::errorPermission();
}
