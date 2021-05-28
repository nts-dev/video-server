<?php


class UploadError{
    private $type;
    private $error;


    public function setErrors($type, $string){
        $this->error = $string;
        $this->type = $type;
    }

    public function getType(){
        return $this->type;
    }

    public function getError(){
        return $this->type .$this->error;
    }
}
