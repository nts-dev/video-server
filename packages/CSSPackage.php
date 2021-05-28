<?php


class CSSPackage
{

    static function DHTMLX()
    {
        $string = '
                    <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/codebase/dhtmlx.css">
                    <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/skins/material/dhtmlx.css">
                    <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/skins/web/dhtmlx.css">
                    <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/skins/terrace/dhtmlx.css">';
        echo $string;
    }

    static function JQUERY()
    {
       echo ' <link  rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/jquery/jquery.css"/>';
    }

    static function BOOTSTRAP()
    {
        echo '<link href="'.Boot::PACKAGEPATH.'packages/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">';
    }

    static function VIDEOJS()
    {
        echo '<link href="'.Boot::PACKAGEPATH.'packages/lib/video-js/video-js.css" rel="stylesheet">';
    }

    static function FONTAWESOME(){
        echo '<link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/codebase/fonts/font_roboto/roboto.css"/>
             <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/fontawesome/css/fontawesome.min.css"/>
             <link rel="stylesheet" type="text/css" href="'.Boot::PACKAGEPATH.'packages/lib/fontawesome/css/solid.min.css"/>';
    }

    static function TINYMCE()
    {
        echo '';
    }
}