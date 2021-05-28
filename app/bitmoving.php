<?php
$file = $_GET['mfile'];
$sub_file = substr($file, 0, -3);

$subtitle = "http://83.98.243.184:1935/vod/".$sub_file."ttml";

$vid_url = "http://83.98.243.184:1935/vod/" . $file . "/manifest.mpd";
//$vid_url = "http://83.98.243.184:1935/vod/170216A_090_MotherandSon_1080p.mp4/manifest.mpd";
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
        <div id="player" style="width: 98%; height: 90%; margin-top: 0; margin-left: 1%"></div>

        <script type="text/javascript">
            var conf = {
                "key": "eec04fce-f553-488b-bfad-7a0c5626fe9e",
                "playback": {
                    autoplay: true
                },
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
                            url: "<?= $subtitle ?>",
                            kind: "subtitle"

                        };    
                        player.addSubtitle(enSubtitle);
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