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
            _editor.setTheme("ace/theme/monokai");
            _editor.session.setMode("ace/mode/javascript");
            var session = ace.createEditSession("text");

            setEmptyContent();


            function setEmptyContent() {
                var editor = _editor;

                editor.setValue("00:00:01 -> \n\
insert your overlay in this format \n\ \n\
00:00:10 -> \n\second line ");
            }


            function setContent(file)
            {
                var editor = _editor;

                editor.setValue("");
//                var oldSession = editor.session
//                oldSession.destroy();


                var rawFile = new XMLHttpRequest();
                rawFile.open("GET", file, false);

                rawFile.onreadystatechange = function()
                {
                    if (rawFile.readyState === 4)
                    {
                        if (rawFile.status === 200 || rawFile.status == 0)
                        {
                            var allText = rawFile.responseText;
                            editor.insert(allText);
//                            editor.setSession(session);
                            console.log(allText)
                            
                        } else {
                            setEmptyContent();
                        }
                        
                    }
                  
                }
                rawFile.send(null);
            }


            function getContent() {
                var editor = _editor;
                var content = editor.getValue();

                return content;

            }

        </script>
    </body>
</html>

