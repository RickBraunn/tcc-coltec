<?php

const VIEWPATH = __DIR__ . '/pages';
const ROOT = __DIR__;

const DB_NAME = 'tcc-ricardo';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';


function criptografa($senha){
    return sha1($senha);
}
