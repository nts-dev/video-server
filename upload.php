<!doctype html>
<html lang="en">
<head>
    <title>Upload</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


    <style>

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            /*background: linear-gradient(to right, #8e24aa, #b06ab3);*/
            /*background: linear-gradient(to right, #a0a2a5, #6f6b6f);*/
            /*color: #D7D7EF;*/
            color: #69696a;
            font-family: 'Lato', sans-serif
        }

        h2 {
            margin: 50px 0
        }

        .file-drop-area {
            position: relative;
            display: flex;
            align-items: center;
            width: 450px;
            max-width: 100%;
            padding: 25px;
            /*border: 1px dashed rgba(255, 255, 255, 0.4);*/
            border: 1px dashed rgba(15, 12, 12, 0.4);
            border-radius: 3px;
            transition: 0.2s
        }

        .choose-file-button {
            flex-shrink: 0;
            background-color: rgba(153, 149, 149, 0.04);
            border: 1px solid rgba(9, 8, 8, 0.1);
            /*background-color: rgba(255, 255, 255, 0.04);*/
            /*border: 1px solid rgba(255, 255, 255, 0.1);*/
            border-radius: 3px;
            padding: 8px 15px;
            margin-right: 10px;
            font-size: 12px;
            text-transform: uppercase
        }

        .file-message {
            font-size: small;
            font-weight: 300;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0
        }

        .mt-100 {
            margin-top: 15px
        }

        .mt-3, .my-3 {
            margin-top: 4rem !important;
        }

        .upload-button .choose-file-button { float:right }
    </style>

</head>
<body>
<div class="container d-flex justify-content-center mt-100">
    <div class="row">
        <div class="col-md-12">
            <div class="file-drop-area" id="browseFile"><span class="choose-file-button">Choose files</span> <span
                        class="file-message">or drag and drop files here</span>
                <input class="file-input" type="file" id="dropZone">
            </div>
            <div class="upload-button" style="margin-top: 15px"><span class="choose-file-button" id="uploadFile">Upload files</span></div>
            <div style="display: none" class="progress mt-3" style="height: 25px;margin-top: 15px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                     aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        let resumable = new Resumable({
            target: 'https://video.nts.nl:9090/api/videos/store',
            // query: {_token: '{{ csrf_token() }}'}, // CSRF token
            // fileType: ['mp4'],
            chunkSize: 10 * 1024 * 1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
            headers: {
                'Accept': 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });

        resumable.assignBrowse(document.getElementById('browseFile'));
        resumable.assignDrop(document.getElementById('browseFile'));

        resumable.on('fileAdded', function (file) { // trigger when file picked

            let textbox = document.getElementById('dropZone').previousElementSibling;
            textbox.textContent = file.fileName;

            // $('#uploadFile').show();
        });

        resumable.on('fileProgress', function (file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });

        resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
            response = JSON.parse(response)
            // $('#videoPreview').attr('src', response.path);
            // $('.card-footer').show();
            parent.dhtmlx.alert('successfully uploaded.');
            progress.hide();
            document.getElementById('dropZone').previousElementSibling.textContent = "or drag and drop files here"
        });

        resumable.on('fileError', function (file, response) { // trigger when there is any error
            alert('file uploading error.')
        });

        $("#uploadFile").click(function () {
            showProgress();
            resumable.upload(); // to actually start uploading.
        });


        let progress = $('.progress');

        function showProgress() {
            progress.find('.progress-bar').css('width', '0%');
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            progress.show();
        }

        function updateProgress(value) {
            progress.find('.progress-bar').css('width', `${value}%`)
            progress.find('.progress-bar').html(`${value}%`)
        }

        function hideProgress() {
            progress.hide();
        }
    });
</script>

</body>
</html>

