<?php


class DataUtil
{

    public function setXMLHeader() {
        ob_clean();
        header("Content-type:text/xml");
        echo "<?xml version='1.0'?>";
    }


}