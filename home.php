<!DOCTYPE html>
<html lang="en">

<?php
include('api/session/Commons.php');
include('includes.php');

if (!isset($_SESSION) || !isset($_SESSION['USER'])) {
    header("Location: /"); /* Redirect browser */
}

$current_session = unserialize($_SESSION["USER"]);
$eid = $current_session->getBOUser()->getTraineeId();
$identifier = $current_session->getBOUser()->getPassword();
$user_name = $current_session->getBOUser()->getFirstName() . " " . $current_session->getBOUser()->getLastName();
?>

<head>
    <title>Home | NTS Video</title>
    <link rel="stylesheet" type="text/css" href="view/css/gridcustome.css">
    <link rel="stylesheet" type="text/css" href="view/css/custome.css">
    <?php
    CSSPackage::DHTMLX();
    CSSPackage::BOOTSTRAP();
    CSSPackage::FONTAWESOME();
    CSSPackage::JQUERY();
    JSPackage::DHTMLX();
    JSPackage::BOOTSTRAP();
    JSPackage::JQUERY();
    ?>
</head>
<body>

<header>
    <nav class="nav justify-content-end" style="font-size: 15px">
        <a class="nav-link disabled">
            <span class="fa fa-user-tag"></span>
            <?= $user_name ?></a>
        <div class="nav-link signout video-nav-link" id="signout">Log out</div>
    </nav>
</header>
<div class="container-fluid" id="videoAppHomepage" style="width:99%;height:95%; margin-left: 0.5%"></div>


<script>

    const ID = "<?= $eid ?>";
    const identifier = "<?= $identifier ?>";
    const TRAINEE = {id: ID, identifier: identifier,}
    const signOutButton = document.getElementById('signout');
    signOutButton.addEventListener('click', function () {
        $.get('sign_out.php', {action: "signout"}, function (data, status) {
            window.location.replace("index.php");
        });
    }, false);


    const WWWROOT = "<?php echo WEBURL . \Boot::WWWROOT ?>";
    const baseURL = "<?php echo WEBURL . \Boot::WWWROOT  ?>";

</script>
<script src="view/js/layout.js"></script>
<script src="view/js/subtitleAudioLayout.js"></script>
<script src="view/js/mediaPlayerLayout.js"></script>
<script src="view/js/helpers/audioTranslator.js"></script>
<script src="view/js/uploader_form.js"></script>
<script src="view/js/upload_section.js"></script>
<script src="view/js/filmScript.js"></script>

<script src="view/js/HTTPfunctions.js"></script>

</body>

</html>

