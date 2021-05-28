<?php


class JSPackage
{


    static function DHTMLX()
    {
        echo '<script src="'.Boot::PACKAGEPATH.'packages/lib/dhtmlxSuite5/codebase/dhtmlx.js"></script>';
    }

    static function JQUERY()
    {
        echo '<script src="'.Boot::PACKAGEPATH.'packages/lib/jquery/jquery2.1.3.min.js"></script>
              <script src="'.Boot::PACKAGEPATH.'packages/lib/jquery/jquery-ui.min.js"></script>';
    }

    static function BOOTSTRAP()
    {
        echo '<script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/bootstrap/dist/js/bootstrap.min.js"></script>';
    }

    static function VIDEOJS()
    {
        echo '<script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/video-js/video.min.js"></script>';
    }

    static function FONTAWESOME() {
        echo '<script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/fontawesome/fontawesome.min.js"></script>
                <script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/fontawesome/solid.min.js"></script>';
    }
    static function TINYMCE()
    {
        echo '<script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/tinymce/tinymce.min.js"></script>';
    }

    static function NUEVO()
    {
        echo '<script type="text/javascript" src="'.Boot::PACKAGEPATH.'packages/lib/nuevo/nuevo.min.js"></script>';
    }
}