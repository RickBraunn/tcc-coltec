<?php

const VIEWPATH = __DIR__ . '/pages';
const ROOT = __DIR__;

const DB_NAME = 'tcc';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const DIR_SOLICITACAO = __DIR__ . '/../files/solicitacoes/';

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

function criptografa($senha){
    return sha1($senha);
}
