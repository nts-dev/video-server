

<html>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <head>
        <script>

            function updateProgress(unit, indicatorText) {
                $("#indicator").html(unit);
                $('.progress-bar').css('width', unit);
                $("#indicatorText").html(indicatorText);
                
            }
        </script>
    </head>
    <style>

        .progress-outer{
            background: #fff;
            border-radius: 50px;
            padding: 25px;
            margin: 10px 0;
            box-shadow: 0 0  10px rgba(209, 219, 231,0.7);
        }
        .progress{
            height: 27px;
            margin: 0;
            overflow: visible;
            border-radius: 50px;
            background: #eaedf3;
            box-shadow: inset 0 10px  10px rgba(244, 245, 250,0.9);
        }
        .progress .progress-bar{
            border-radius: 50px;
        }
        .progress .progress-value{
            position: relative;
            left: -45px;
            top: 4px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 2px;
        }
        .progress-bar.active{
            animation: reverse progress-bar-stripes 0.40s linear infinite, animate-positive 2s;
        }
        @-webkit-keyframes animate-positive{
            0% { width: 0%; }
        }
        @keyframes animate-positive {
            0% { width: 0%; }
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="progress-outer">
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" style="width:0%;"></div>
                        <div class="progress-value" id="indicator"></div>
                    </div>
                </div>
                <div id="indicatorText"></div>
            </div>
        </div>
    </div>

</html>

