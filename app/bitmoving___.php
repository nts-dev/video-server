<?php
$file = $_GET['mfile'];

$subtitle = "http://77.61.38.201:1935/vod/2730P.srt";
$subtitle2 = "http://77.61.38.201:1935/vod/2730K.srt";
$vid_url = "http://77.61.38.201:1935/vod/" . $file . "/manifest.mpd";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Training Player</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
        <!-- Bitmovin Player -->
        <script type="text/javascript" src="https://bitmovin-a.akamaihd.net/bitmovin-player/stable/7.6/bitmovinplayer.js"></script>
        <!--<script type="text/javascript" src="../lib/bitmovin.js"></script>-->
        <style>
            .bmpui-ui-skin-modern .bmpui-ui-watermark {
                background-image: none !important;
            }
        </style>
    </head>
    <body>
        <div id="player" style="width: 98%; height: 96%; margin-top: 1%; margin-left: 1%"></div>

        <script type="text/javascript">
            var conf = {
                "key": "eec04fce-f553-488b-bfad-7a0c5626fe9e",
                "source": {
                    dash: '<?= $vid_url ?>'
                },
                cast: {
                    enable: true
                }

            };
            var player = bitmovin.player('player');

            player.setup(conf).then(
                    function(value) {
                        var enSubtitle = {
                            id: "sub1",
                            lang: "English",
                            label: "English",
                            url: "<?= $subtitle ?>",
                            kind: "subtitle"

                        };

                        var swaSubtitle = {
                            id: "subs",
                            lang: "kis",
                            label: "Swahili",
                            url: "<?= $subtitle2 ?>",
                            kind: "subtitle"

                        };

                        player.addSubtitle(enSubtitle);
//                        player.addSubtitle(swaSubtitle);
                        // Success
                        console.log('Successfully created bitmovin player instance');
                    },
                    function(reason) {
                        // Error!
                        console.log('Error while creating bitmovin player instance');
                    }
            );

        </script>
    </body>
</html>