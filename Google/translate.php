<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <Title>Text To Speech</title>
    </head>
    <body>

        <input type="text" name="Text">
        <a href="#" class="say">Say It</a>

        <audio src="" class="speech" hidden></audio>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


        <script>
            $(function() {
                $('a.say').on('click', function(e) {
                    e.preventDefault();
                    var text = "Please do make me yours";
                    text = encodeURIComponent(text);
                    console.log(text);
                    var url = "http://translate.google.com/translate_tts?tl=en&q=" + text + "&client=tw-ob";
                    $('audio').attr('src', url).get(0).play();
                });
            });
        </script>
    </body>
</html>
