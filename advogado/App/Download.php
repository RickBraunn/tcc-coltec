<?php

namespace App;

use App\Controller;

class Download
{
    private $arquivo;
    private $nomeOriginal;


    public function setNomeOriginal($nome)
    {
        $this->nomeOriginal = $nome;
    }

    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function solicitacao($solicitacao, $arquivo)
    {
        $this->nomeOriginal = $arquivo;
        $this->arquivo = DIR_SOLICITACAO . $solicitacao . '/' . $arquivo;
    }

    public function download(){

        //First, see if the file exists
        if (!is_file($this->arquivo)) {
            Controller::errorNotFound();
        }

        //Gather relevent info about file
        $len = filesize($this->arquivo);
        $filename = basename($this->arquivo);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));

        if ($this->nomeOriginal!='') $filename = $this->nomeOriginal;

        //This will set the Content-Type to the appropriate setting for the file
        switch( $file_extension ) {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            case "mp3": $ctype="audio/mpeg"; break;
            case "wav": $ctype="audio/x-wav"; break;
            case "mpeg":
            case "mpg":
            case "mpe": $ctype="video/mpeg"; break;
            case "mov": $ctype="video/quicktime"; break;
            case "avi": $ctype="video/x-msvideo"; break;

            default: $ctype="application/force-download";
        }

        //Begin writing headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");

        //Use the switch-generated Content-Type
        header("Content-Type: $ctype");

        //Force the download
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len);
        @readfile($this->arquivo);
        return true;
    }

}
