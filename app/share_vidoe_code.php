<?php ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE in Action</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.3.3/ace.js?v=1" type="text/javascript" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/ext-beautify.js?v=1" type="text/javascript" charset="utf-8"></script> 
        <style type="text/css" media="screen">
            #editor { 
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }

        </style>
    </head>
    <body>

        <div id="editor"></div>



<!--<script src="/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>-->
        <script>

            var _editor = ace.edit("editor");
            _editor.setTheme("ace/theme/agolawhite");
            _editor.session.setMode("ace/mode/javascript");
            var session = ace.createEditSession("text");

            setEmptyContent();


            function setEmptyContent() {
                var editor = _editor;
                editor.setValue();

            }


            function setContent(file)
            {
                var editor = _editor;


                editor.setValue(file);

            }


            function getContent() {
                var editor = _editor;
                var content = editor.getValue();

                return content;

            }

        </script>
    </body>
</html>

