<?php

include('../api/session/Commons.php');

$bootstrap = App::getInstance();


$session = $bootstrap::startSessionIfNotAvailable(
    20196,
    "1kenan"
);
$subjectService = new \session\project\ProjectService();
$mediaService = new MediaService($session);

$projectResult = $subjectService->findAll();
$videoResult = $mediaService->findAll();


$MEDIA_ARRAY = array();

if (sizeof($videoResult) > 0) {
    foreach ($videoResult as $row) {
        $project_id = $row->subject_id;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_ID"] = $row->id;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_TITLE"] = $row->title;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_DESC"] = $row->description;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_THUMB"] = $row->thumbnailLink;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_RAW_LINK"] = $row->videoLink_raw;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_WEBM"] = $row->webm;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_VIEWS"] = $row->total_views;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_MODULE_ID"] = $row->module_id;
        $MEDIA_ARRAY[$project_id][$row->id]["VID_CREATED"] = $row->created_at;
    }
}


$PROJECT_ARRAY = array();

if (sizeof($projectResult) > 0) {
    foreach ($projectResult as $project) {
        $PROJECT_ARRAY[$project['id']]['PRO_ID'] = $project['id'];
        $PROJECT_ARRAY[$project['id']]['PRO_TITLE'] = $project['title'];
        $PROJECT_ARRAY[$project['id']]['PRO_VIDEOS'] = $MEDIA_ARRAY[$project['id']] ?? array();
    }
}

// echo "<pre>";
// print_r($PROJECT_ARRAY);
//
// exit();
//
?>

<!DOCTYPE html>
<!-- Open HTML -->
<html lang="en-US">
<!-- Open Head -->

<!-- Mirrored from demo.harutheme.com/vidio/home-5/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 10 May 2021 11:48:48 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <script>document.documentElement.className = document.documentElement.className + ' yes-js js_active js'</script>
    <title>Site | NTS Video</title>



    <style type="text/css">
        img.wp-smiley,
        img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            margin: 0 .07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>
    <link rel='stylesheet' id='fontawesome-all-css'
          href='wp-content/plugins/haru-vidi/assets/libraries/fontawesome/css/all.min7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='fontawesome-shims-css'
          href='wp-content/plugins/haru-vidi/assets/libraries/fontawesome/css/v4-shims.min7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='animate-css'
          href='wp-content/plugins/haru-vidi/assets/libraries/animate/animate.min7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='mediaelement-css'
          href='wp-content/plugins/haru-vidi/assets/libraries/mediaelement/mediaelementplayer7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='magnific-popup-css'
          href='wp-content/plugins/haru-vidi/assets/libraries/magnificPopup/magnific-popup7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='haru-vidi-css' href='wp-content/plugins/haru-vidi/assets/css/style7e15.css?ver=5.5.4'
          type='text/css' media='all'/>


    <link rel='stylesheet' id='yith-wcwl-font-awesome-css'
          href='wp-content/plugins/yith-woocommerce-wishlist/assets/css/font-awesome1849.css?ver=4.7.0' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='yith-wcwl-main-css'
          href='wp-content/plugins/yith-woocommerce-wishlist/assets/css/styled9b6.css?ver=3.0.18' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='contact-form-7-css'
          href='wp-content/plugins/contact-form-7/includes/css/styles9dff.css?ver=5.3.2' type='text/css' media='all'/>
    <link rel='stylesheet' id='pmpro_frontend-css'
          href='wp-content/plugins/paid-memberships-pro/css/frontend21bb.css?ver=2.5.4' type='text/css' media='screen'/>
    <link rel='stylesheet' id='pmpro_print-css'
          href='wp-content/plugins/paid-memberships-pro/css/print21bb.css?ver=2.5.4' type='text/css' media='print'/>
    <link rel='stylesheet' id='dashicons-css' href='wp-includes/css/dashicons.min7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <style id='dashicons-inline-css' type='text/css'>
        [data-font="Dashicons"]:before {
            font-family: 'Dashicons' !important;
            content: attr(data-icon) !important;
            speak: none !important;
            font-weight: normal !important;
            font-variant: normal !important;
            text-transform: none !important;
            line-height: 1 !important;
            font-style: normal !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
        }
    </style>
    <link rel='stylesheet' id='post-views-counter-frontend-css'
          href='wp-content/plugins/post-views-counter/css/frontend3ba1.css?ver=1.3.3' type='text/css' media='all'/>
    <link rel='stylesheet' id='woocommerce-layout-css'
          href='wp-content/plugins/woocommerce/assets/css/woocommerce-layoutbb93.css?ver=5.0.0' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='woocommerce-smallscreen-css'
          href='wp-content/plugins/woocommerce/assets/css/woocommerce-smallscreenbb93.css?ver=5.0.0' type='text/css'
          media='only screen and (max-width: 768px)'/>
    <link rel='stylesheet' id='woocommerce-general-css'
          href='wp-content/plugins/woocommerce/assets/css/woocommercebb93.css?ver=5.0.0' type='text/css' media='all'/>
    <style id='woocommerce-inline-inline-css' type='text/css'>
        .woocommerce form .form-row .required {
            visibility: visible;
        }
    </style>
    <link rel='stylesheet' id='yz-opensans-css'
          href='https://fonts.googleapis.com/css?family=Open+Sans%3A400%2C600&amp;ver=2.6.2' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='youzer-css'
          href='wp-content/plugins/youzer/includes/public/assets/css/youzer.min61da.css?ver=2.6.2' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='yz-headers-css'
          href='wp-content/plugins/youzer/includes/public/assets/css/yz-headers.min61da.css?ver=2.6.2' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='yz-scheme-css'
          href='wp-content/plugins/youzer/includes/public/assets/css/schemes/yz-blue-scheme.min61da.css?ver=2.6.2'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='yz-social-css'
          href='wp-content/plugins/youzer/includes/public/assets/css/yz-social.min61da.css?ver=2.6.2' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='yz-icons-css'
          href='wp-content/plugins/youzer/includes/admin/assets/css/all.min61da.css?ver=2.6.2' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='yz-mycred-css'
          href='wp-content/plugins/youzer/includes/public/assets/css/yz-mycred.min61da.css?ver=2.6.2' type='text/css'
          media='all'/>

    <link rel='stylesheet' id='woocommerce_prettyPhoto_css-css'
          href='wp-content/plugins/woocommerce/assets/css/prettyPhoto7e15.css?ver=5.5.4' type='text/css' media='all'/>
    <link rel='stylesheet' id='bootstrap-css'
          href='wp-content/themes/vidio/assets/libraries/bootstrap/css/bootstrap.min7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='font-awesome-all-css'
          href='wp-content/themes/vidio/assets/libraries/font-awesome/css/all.min7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='font-awesome-shims-css'
          href='wp-content/themes/vidio/assets/libraries/font-awesome/css/v4-shims.min7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='font-awesome-animation-css'
          href='wp-content/themes/vidio/assets/libraries/font-awesome/css/font-awesome-animation.min7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='jplayer-css'
          href='wp-content/themes/vidio/assets/libraries/jPlayer/skin/haru/skin7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='owl-carousel-css'
          href='wp-content/themes/vidio/assets/libraries/owl-carousel/assets/owl.carousel.min7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='slick-css' href='wp-content/themes/vidio/assets/libraries/slick/slick7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='prettyPhoto-css'
          href='wp-content/themes/vidio/assets/libraries/prettyPhoto/css/prettyPhoto.min7e15.css?ver=5.5.4'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='haru-animate-css'
          href='wp-content/themes/vidio/framework/core/megamenu/assets/css/animate7e15.css?ver=5.5.4' type='text/css'
          media='all'/>
    <link rel='stylesheet' id='haru-vc-customize-css'
          href='wp-content/themes/vidio/assets/css/vc-customize7e15.css?ver=5.5.4' type='text/css' media='all'/>
    <link rel='stylesheet' id='haru-theme-style-css' href='wp-content/themes/vidio/style7e15.css?ver=5.5.4'
          type='text/css' media='all'/>

    <link rel='stylesheet' id='logy-customStyle-css'
          href='wp-content/plugins/youzer/includes/admin/assets/css/custom-script7e15.css?ver=5.5.4' type='text/css'
          media='all'/>






    <link rel="canonical" href="index.php"/>

    <meta name="framework" content="Redux 4.1.24"/>


    <style type="text/css" data-type="vc_shortcodes-custom-css">.vc_custom_1588836754893 {
            padding-top: 1% !important;
            padding-bottom: 4% !important;
        }

        .vc_custom_1588665615105 {
            margin-bottom: 25px !important;
        }

        .vc_custom_1594781417977 {
            margin-top: 15px !important;
        }

        .vc_custom_1594781436214 {
            margin-top: 15px !important;
        }

        .vc_custom_1588665744376 {
            margin-bottom: 20px !important;
        }

        .vc_custom_1588665738573 {
            margin-bottom: 20px !important;
        }

        .vc_custom_1588665615105 {
            margin-bottom: 25px !important;
        }

        .vc_custom_1604162288188 {
            margin-bottom: 25px !important;
        }

        .vc_custom_1592186015098 {
            margin-top: -5px !important;
        }</style>
    <noscript>
        <style>.woocommerce-product-gallery {
                opacity: 1 !important;
            }</style>
    </noscript>
    <meta name="generator" content="Powered by WPBakery Page Builder - drag and drop page builder for WordPress."/>
    <style id="haru_vidio_options-dynamic-css" title="dynamic-css" class="redux-options-output">body {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center center;
            background-size: cover;
        }

        body {
            font-family: Lato;
            font-weight: 400;
            font-style: normal;
            font-size: 14px;
            font-display: swap;
        }

        h1 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 36px;
            font-display: swap;
        }

        h2 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 28px;
            font-display: swap;
        }

        h3 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 24px;
            font-display: swap;
        }

        h4 {
            font-family: Montserrat;
            font-weight: 700;
            font-style: normal;
            font-size: 21px;
            font-display: swap;
        }

        h5 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 18px;
            font-display: swap;
        }

        h6 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 14px;
            font-display: swap;
        }

        .navbar .navbar-nav a {
            font-family: Montserrat;
            font-weight: 500;
            font-size: 14px;
            font-display: swap;
        }

        .page-title-inner h1 {
            font-family: Montserrat;
            font-weight: 500;
            font-style: normal;
            font-size: 36px;
            font-display: swap;
        }

        .page-title-inner .page-sub-title {
            font-family: Montserrat;
            font-weight: 400;
            font-style: italic;
            font-size: 14px;
            font-display: swap;
        }</style>
    <style type="text/css" data-type="vc_shortcodes-custom-css">.vc_custom_1588836605500 {
            margin-top: 15px !important;
        }

        .vc_custom_1588836639785 {
            margin-top: 15px !important;
        }

        .vc_custom_1588836674059 {
            margin-top: 10px !important;
        }

        .vc_custom_1586701164274 {
            margin-bottom: 40px !important;
        }

        .vc_custom_1588836702079 {
            margin-bottom: 20px !important;
        }</style>
    <noscript>
        <style> .wpb_animate_when_almost_visible {
                opacity: 1;
            }</style>
    </noscript>
</head>
<!-- Close Head -->
<body class="bp-legacy page-template-default page page-id-806 theme-vidio pmpro-body-has-access woocommerce-no-js yz-blue-scheme not-logged-in layout-wide disable-transition wpb-js-composer js-comp-ver-6.5.0 vc_responsive no-js">
<!-- Display newsletter popup -->
<!-- Open haru main -->
<div id="haru-main">
    <header id="haru-mobile-header" class="haru-mobile-header header-mobile-3 header-mobile-sticky">
        <div class="haru-mobile-header-wrap menu-mobile-fly">
            <div class="container haru-mobile-header-container">
                <div class="haru-mobile-header-inner">
                    <div class="toggle-icon-wrap toggle-mobile-menu" data-ref="haru-nav-mobile-menu"
                         data-drop-type="fly">
                        <div class="toggle-icon"><span></span></div>
                    </div>

                    <!-- Header mobile customize -->
                    <div class="header-elements" style="font-size: inherit;">
                        <div class="header-elements-item">
                            <div class="video-search-shortcode style-button ">
                                <a href="javascript:;" class="video-search-button" data-effect="search-zoom-in"><i
                                            class="haru-icon haru-search"></i></a>
                                <div id="video-search-popup-0"
                                     class="video-search-popup white-popup mfp-hide mfp-with-anim">
                                    <form role="search" method="get" class="haru-video-search search-form"
                                          action="https://demo.harutheme.com/vidio/">
                                        <label for="haru-video-search-field-0">
                                            <span class="screen-reader-text">Search for:</span>
                                            <input type="search" id="haru-video-search-field-0" class="search-field"
                                                   placeholder="Search videos&hellip;" value="" name="s"/>
                                        </label>
                                        <button type="submit" class="search-submit" value="Search">
                                            <span>Search</span>
                                        </button>
                                        <input type="hidden" name="post_type" value="haru_video"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="header-elements-item">
                            <div class="user-menu-shortcode default ">
                                <div class="user-account-wrap">
                                    <div class="user-account-content logged-out">
                                        <a href="login/index.html" class="user-menu-login"><i
                                                    class="haru-icon haru-user"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-elements-item">
                            <div class="watch-later-element">
                                <a href="javascript:;" class="watch-later-page-link">
        <span class="watch-later-icon">
            <i class="haru-icon haru-clock-o"></i>
            <span class="watch-later-status "></span>
        </span>
                                </a>
                                <ul class="haru-watch-later-list">
                                    <li class="haru-watch-later-videos empty-video"></li>
                                    <li class="haru-watch-later-empty-video">
                                        <i class="haru-icon haru-file-video"></i>
                                        <div class="watch-later-empty-message">
                                            An empty videos list!
                                        </div>
                                    </li>
                                    <li class="haru-watch-later-view-all">
                                        <a href="watch-later/index.html" class="haru-button haru-button-default">View
                                            all videos</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Header mobile customize -->
                    <div class="header-logo-mobile">
                        <h3>NTS Video</h3>
                    </div>
                </div>
                <div id="haru-nav-mobile-menu" class="haru-mobile-header-nav menu-mobile-fly">
                    <div class="mobile-menu-header">Menu<span class="mobile-menu-close"></span></div>

                    <div class="mobile-header-social">
                        <ul class="header-elements-item header-social-network-wrap">
                            <li><a href="#" target="_blank"><i class="header-icon fa fa-twitter"></i></a></li>
                            <li><a href="#" target="_blank"><i class="header-icon fa fa-facebook"></i></a></li>
                            <li><a href="#" target="_blank"><i class="header-icon fa fa-youtube"></i></a></li>
                            <li><a href="#" target="_blank"><i class="header-icon fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="haru-mobile-menu-overlay"></div>
            </div>
        </div>
    </header>
    <header id="haru-header" class="haru-main-header header-4 header-sticky sticky_light">
        <div class="haru-header-nav-wrap">
            <div class="row header-nav-above d-flex justify-content-between">
                <div class="col-md-4 header-left header-elements align-self-center">
                    <div class="d-flex justify-content-start align-items-center">
                        <a href="javascript:;" id="popup-menu-button" data-effect="menu-popup-bg mfp-zoom-in"
                           data-delay="500"></a>
                        <div class="header-elements header-elements-left">
                            <h3>NTS Video</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 header-right header-elements align-self-center">
                    <div class="header-elements header-elements-right">
                        <div class="header-elements-item">
                            <div class="video-search-shortcode style-button ">
                                <a href="javascript:;" class="video-search-button" data-effect="search-zoom-in"><i
                                            class="haru-icon haru-search"></i></a>
                                <div id="video-search-popup-1"
                                     class="video-search-popup white-popup mfp-hide mfp-with-anim">
                                    <form role="search" method="get" class="haru-video-search search-form"
                                          action="https://demo.harutheme.com/vidio/">
                                        <label for="haru-video-search-field-1">
                                            <span class="screen-reader-text">Search for:</span>
                                            <input type="search" id="haru-video-search-field-1" class="search-field"
                                                   placeholder="Search videos&hellip;" value="" name="s"/>
                                        </label>
                                        <button type="submit" class="search-submit" value="Search">
                                            <span>Search</span>
                                        </button>
                                        <input type="hidden" name="post_type" value="haru_video"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="header-elements-item">
                            <div class="user-menu-shortcode default ">
                                <div class="user-account-wrap">
                                    <div class="user-account-content logged-out">
                                        <a href="login/index.html" class="user-menu-login"><i
                                                    class="haru-icon haru-user"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-elements-item">
                            <div class="watch-later-element">
                                <a href="javascript:;" class="watch-later-page-link">
        <span class="watch-later-icon">
            <i class="haru-icon haru-clock-o"></i>
            <span class="watch-later-status "></span>
        </span>
                                </a>
                                <ul class="haru-watch-later-list">
                                    <li class="haru-watch-later-videos empty-video"></li>
                                    <li class="haru-watch-later-empty-video">
                                        <i class="haru-icon haru-file-video"></i>
                                        <div class="watch-later-empty-message">
                                            An empty videos list!
                                        </div>
                                    </li>
                                    <li class="haru-watch-later-view-all">
                                        <a href="watch-later/index.html" class="haru-button haru-button-default">View
                                            all videos</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>                <!-- Open HARU Content Main -->
    <div id="haru-content-main" class="clearfix">

        <main class="haru-page">
            <div class="container clearfix">
                <div class="row clearfix">
                    <div class="page-content col-md-12  col-sm-12 col-xs-12">
                        <div class="page-wrapper">
                            <div class="page-inner clearfix">

                                <div id="post-806"
                                     class="post-806 page type-page status-publish hentry pmpro-has-access clearfix">
                                    < class="entry-content">


                                    <div class="vc_row-full-width vc_clearfix"></div>

                                    <div class="vc_row wpb_row vc_row-fluid vc_custom_1588836674059">
                                        <div class="wpb_column vc_column_container vc_col-sm-12">
                                            <div class="vc_column-inner">
                                                <div class="wpb_wrapper">
                                                    <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                        <div class="wpb_column vc_column_container vc_col-sm-8">
                                                            <div class="vc_column-inner">
                                                                <div class="wpb_wrapper">
                                                                    <div class="wpb_text_column wpb_content_element  vc_custom_1588836702079">
                                                                        <div class="wpb_wrapper">
                                                                            <div class="video-category-single-shortcode default "
                                                                                 data-atts="{&quot;layout&quot;:&quot;default&quot;,&quot;columns&quot;:1,&quot;categories&quot;:&quot;technology&quot;,&quot;video_style&quot;:&quot;default&quot;,&quot;posts_per_page&quot;:&quot;3&quot;,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;DESC&quot;,&quot;view_more&quot;:&quot;show&quot;}"
                                                                                 id="video-category-single-shortcode1873461260">

                                                                                <ul class="video-filter">
                                                                                    <li>
                                                                                        <h6 class="tab-item-heading">
                                                                                            <a class="filter-item active"
                                                                                               href="javascript:;"
                                                                                               data-category="technology"
                                                                                            >Technology</a>
                                                                                        </h6>
                                                                                    </li>
                                                                                </ul>

                                                                                <div class="video-ajax-content">
                                                                                    <div class="ajax-loading-icon">
                                                                                        <div class="loading-bar"></div>
                                                                                    </div>
                                                                                    <div class="video-category-single-content">
                                                                                        <div class="video-list grid-columns grid-columns__1 animated fadeIn haru-clear">
                                                                                            <article
                                                                                                    class="grid-item video-item video-495 sports technology  video-style-2">
                                                                                                <div class="video-item__thumbnail">
                                                                                                    <a href="video/best-funny-moments-in-tennis/index.html">

                                                                                                        <div class="video-thumbnail image default"
                                                                                                             data-speed="1000">
                                                                                                            <img src="wp-content/uploads/2020/01/travel-11.jpg"
                                                                                                                 alt="Best funny moments in tennis">
                                                                                                        </div>
                                                                                                    </a>
                                                                                                    <div class="video-item__icon">
                                                                                                        <a href="video/best-funny-moments-in-tennis/index.html"
                                                                                                           class="video-item-player-direct">
                                                                                                            <i class="haru-icon haru-play"></i>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                    <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                         data-id="495"
                                                                                                         data-title="Best funny moments in tennis"
                                                                                                         data-permalink="video/best-funny-moments-in-tennis/index.html"
                                                                                                         data-thumb="wp-content/uploads/2020/01/travel-11.jpg"
                                                                                                    >
                                                                                                        <i class="haru-icon haru-clock"></i>
                                                                                                        Watch Later
                                                                                                    </div>

                                                                                                    <div class="video-item__duration">
                                                                                                        9:15
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__content">
                                                                                                    <div class="video-item__category">
                                                                                                        <a href="video-category/sports/index.html"
                                                                                                           rel="tag">Sports</a>,
                                                                                                        <a href="video-category/technology/index.html"
                                                                                                           rel="tag">Technology</a>
                                                                                                    </div>
                                                                                                    <h6 class="video-item__title">
                                                                                                        <a href="video/best-funny-moments-in-tennis/index.html">Best
                                                                                                            funny
                                                                                                            moments
                                                                                                            in
                                                                                                            tennis</a>
                                                                                                    </h6>
                                                                                                    <div class="video-item__meta">
                                                                                                        <div class="video-item__author">
                                                                                                            <i class="fa fa-user"></i>
                                                                                                            <a href="members/admin/index.html">admin</a>
                                                                                                        </div>
                                                                                                        <div class="video-item__date">
                                                                                                            <i class="fa fa-calendar"></i>January
                                                                                                            14, 2020
                                                                                                        </div>
                                                                                                        <div class="video-item__like">
                                                                                                            <div class="post-like">
                                                                                                                <span class="post-vote-label">like</span>
                                                                                                                <i class="haru-icon haru-like"></i>
                                                                                                                <span class="post-like-count">2.150K</span>
                                                                                                                <span class="post-like-unit"> likes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__dislike">
                                                                                                            <div class="post-dislike">
                                                                                                                <span class="post-vote-label">dislike</span>
                                                                                                                <i class="haru-icon haru-dislike"></i>
                                                                                                                <span class="post-dislike-count">151</span>
                                                                                                                <span class="post-dislike-unit"> dislikes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__view">
                                                                                                            <div class="post-views-count">
                                                                                                                <span class="post-views-label">views</span>
                                                                                                                <i class="fa fa-eye"></i>
                                                                                                                <span class="post-view-count">1.369M</span>
                                                                                                                <span class="post-view-unit"> views</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__desc">
                                                                                                        Nullam
                                                                                                        imperdiet,
                                                                                                        sem at
                                                                                                        fringilla
                                                                                                        lobortis,
                                                                                                        sem nibh
                                                                                                        fringilla
                                                                                                        nibh, id
                                                                                                        gravidrus
                                                                                                        sit amet
                                                                                                        erat. Aenean
                                                                                                        nec nisi
                                                                                                        quis nisi...
                                                                                                    </div>
                                                                                                </div>
                                                                                            </article>
                                                                                            <article
                                                                                                    class="grid-item video-item video-494 sports technology  default">
                                                                                                <div class="video-item__thumbnail">
                                                                                                    <a href="video/roger-federer-best-tricks-skills/index.html">

                                                                                                        <div class="video-thumbnail image default"
                                                                                                             data-speed="1000">
                                                                                                            <img src="wp-content/uploads/2020/01/travel-14.jpg"
                                                                                                                 alt="Roger Federer &#8211; Best Tricks &#038; Skills">
                                                                                                        </div>
                                                                                                    </a>
                                                                                                    <div class="video-item__icon">
                                                                                                        <a href="video/roger-federer-best-tricks-skills/index.html"
                                                                                                           class="video-item-player-direct">
                                                                                                            <i class="haru-icon haru-play"></i>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                    <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                         data-id="494"
                                                                                                         data-title="Roger Federer &#8211; Best Tricks &#038; Skills"
                                                                                                         data-permalink="video/roger-federer-best-tricks-skills/index.html"
                                                                                                         data-thumb="wp-content/uploads/2020/01/travel-14.jpg"
                                                                                                    >
                                                                                                        <i class="haru-icon haru-clock"></i>
                                                                                                        Watch Later
                                                                                                    </div>

                                                                                                    <div class="video-item__duration">
                                                                                                        9:15
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__content">
                                                                                                    <div class="video-item__category">
                                                                                                        <a href="video-category/sports/index.html"
                                                                                                           rel="tag">Sports</a>,
                                                                                                        <a href="video-category/technology/index.html"
                                                                                                           rel="tag">Technology</a>
                                                                                                    </div>
                                                                                                    <h6 class="video-item__title">
                                                                                                        <a href="video/roger-federer-best-tricks-skills/index.html">Roger
                                                                                                            Federer
                                                                                                            &#8211;
                                                                                                            Best
                                                                                                            Tricks
                                                                                                            &#038;
                                                                                                            Skills</a>
                                                                                                    </h6>
                                                                                                    <div class="video-item__meta">
                                                                                                        <div class="video-item__author">
                                                                                                            <i class="fa fa-user"></i>
                                                                                                            <a href="members/admin/index.html">admin</a>
                                                                                                        </div>
                                                                                                        <div class="video-item__date">
                                                                                                            <i class="fa fa-calendar"></i>January
                                                                                                            14, 2020
                                                                                                        </div>
                                                                                                        <div class="video-item__like">
                                                                                                            <div class="post-like">
                                                                                                                <span class="post-vote-label">like</span>
                                                                                                                <i class="haru-icon haru-like"></i>
                                                                                                                <span class="post-like-count">2.150K</span>
                                                                                                                <span class="post-like-unit"> likes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__dislike">
                                                                                                            <div class="post-dislike">
                                                                                                                <span class="post-vote-label">dislike</span>
                                                                                                                <i class="haru-icon haru-dislike"></i>
                                                                                                                <span class="post-dislike-count">151</span>
                                                                                                                <span class="post-dislike-unit"> dislikes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__view">
                                                                                                            <div class="post-views-count">
                                                                                                                <span class="post-views-label">views</span>
                                                                                                                <i class="fa fa-eye"></i>
                                                                                                                <span class="post-view-count">1.369M</span>
                                                                                                                <span class="post-view-unit"> views</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__desc">
                                                                                                        Nullam
                                                                                                        imperdiet,
                                                                                                        sem at
                                                                                                        fringilla
                                                                                                        lobortis,
                                                                                                        sem nibh
                                                                                                        fringilla
                                                                                                        nibh, id
                                                                                                        gravidrus
                                                                                                        sit amet
                                                                                                        erat. Aenean
                                                                                                        nec nisi
                                                                                                        quis nisi...
                                                                                                    </div>
                                                                                                </div>
                                                                                            </article>
                                                                                            <article
                                                                                                    class="grid-item video-item video-484 technology  default">
                                                                                                <div class="video-item__thumbnail">
                                                                                                    <a href="video/insane-electric-cars-that-actually-exist/index.html">

                                                                                                        <div class="video-thumbnail image default"
                                                                                                             data-speed="1000">
                                                                                                            <img src="wp-content/uploads/2020/01/technology-19.jpg"
                                                                                                                 alt="Insane electric cars that actually exist">
                                                                                                        </div>
                                                                                                    </a>
                                                                                                    <div class="video-item__icon">
                                                                                                        <a href="video/insane-electric-cars-that-actually-exist/index.html"
                                                                                                           class="video-item-player-direct">
                                                                                                            <i class="haru-icon haru-play"></i>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                    <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                         data-id="484"
                                                                                                         data-title="Insane electric cars that actually exist"
                                                                                                         data-permalink="video/insane-electric-cars-that-actually-exist/index.html"
                                                                                                         data-thumb="wp-content/uploads/2020/01/technology-19.jpg"
                                                                                                    >
                                                                                                        <i class="haru-icon haru-clock"></i>
                                                                                                        Watch Later
                                                                                                    </div>

                                                                                                    <div class="video-item__duration">
                                                                                                        9:15
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__content">
                                                                                                    <div class="video-item__category">
                                                                                                        <a href="video-category/technology/index.html"
                                                                                                           rel="tag">Technology</a>
                                                                                                    </div>
                                                                                                    <h6 class="video-item__title">
                                                                                                        <a href="video/insane-electric-cars-that-actually-exist/index.html">Insane
                                                                                                            electric
                                                                                                            cars
                                                                                                            that
                                                                                                            actually
                                                                                                            exist</a>
                                                                                                    </h6>
                                                                                                    <div class="video-item__meta">
                                                                                                        <div class="video-item__author">
                                                                                                            <i class="fa fa-user"></i>
                                                                                                            <a href="members/admin/index.html">admin</a>
                                                                                                        </div>
                                                                                                        <div class="video-item__date">
                                                                                                            <i class="fa fa-calendar"></i>January
                                                                                                            14, 2020
                                                                                                        </div>
                                                                                                        <div class="video-item__like">
                                                                                                            <div class="post-like">
                                                                                                                <span class="post-vote-label">like</span>
                                                                                                                <i class="haru-icon haru-like"></i>
                                                                                                                <span class="post-like-count">2.150K</span>
                                                                                                                <span class="post-like-unit"> likes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__dislike">
                                                                                                            <div class="post-dislike">
                                                                                                                <span class="post-vote-label">dislike</span>
                                                                                                                <i class="haru-icon haru-dislike"></i>
                                                                                                                <span class="post-dislike-count">151</span>
                                                                                                                <span class="post-dislike-unit"> dislikes</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="video-item__view">
                                                                                                            <div class="post-views-count">
                                                                                                                <span class="post-views-label">views</span>
                                                                                                                <i class="fa fa-eye"></i>
                                                                                                                <span class="post-view-count">1.369M</span>
                                                                                                                <span class="post-view-unit"> views</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__desc">
                                                                                                        Nullam
                                                                                                        imperdiet,
                                                                                                        sem at
                                                                                                        fringilla
                                                                                                        lobortis,
                                                                                                        sem nibh
                                                                                                        fringilla
                                                                                                        nibh, id
                                                                                                        gravidrus
                                                                                                        sit amet
                                                                                                        erat. Aenean
                                                                                                        nec nisi
                                                                                                        quis nisi...
                                                                                                    </div>
                                                                                                </div>
                                                                                            </article>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="video-control hide">
                                                                                    <div class="video-control-item disable"
                                                                                         data-max_pages="4"
                                                                                         data-current_page="1"
                                                                                         data-category="technology"
                                                                                         data-action="prev"
                                                                                    >
                                                                                        <i class="haru-icon haru-arrow-left"></i>
                                                                                    </div>
                                                                                    <div class="video-control-item"
                                                                                         data-max_pages="4"
                                                                                         data-current_page="1"
                                                                                         data-category="technology"
                                                                                         data-action="next"
                                                                                    >
                                                                                        <i class="haru-icon haru-arrow-right"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="wpb_column vc_column_container vc_col-sm-4">
                                                            <div class="vc_column-inner">
                                                                <div class="wpb_wrapper">
                                                                    <div class="wpb_text_column wpb_content_element ">
                                                                        <div class="wpb_wrapper">
                                                                            <div class="video-top-shortcode  default style-sidebar"
                                                                                 id="video-top-shortcode122852830"
                                                                                 data-atts="{&quot;layout&quot;:&quot;default&quot;,&quot;title&quot;:&quot;Top Trending&quot;,&quot;categories&quot;:&quot;fashion,game,harutheme,sports,technology,travel,vidio&quot;,&quot;order_by&quot;:&quot;date&quot;,&quot;order&quot;:&quot;desc&quot;,&quot;video_style&quot;:&quot;default&quot;,&quot;posts_per_page&quot;:&quot;6&quot;}"
                                                                                 data-video_order_by="date"
                                                                            >
                                                                                <h6 class="video-top-title haru-shortcode-title">
                                                                                    <span>Top Trending</span></h6>

                                                                                <div class="video-ajax-content default">
                                                                                    <div class="ajax-loading-icon">
                                                                                        <div class="loading-bar"></div>
                                                                                    </div>
                                                                                    <div class="video-top-content">


                                                                                        <article
                                                                                                class="grid-item video-item video-1019 vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/video-created-by-vidio/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/01/technology-17.jpg"
                                                                                                             alt="Video Created by Vidio">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/video-created-by-vidio/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="1019"
                                                                                                     data-title="Video Created by Vidio"
                                                                                                     data-permalink="video/video-created-by-vidio/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/01/technology-17.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/video-created-by-vidio/index.html">Video
                                                                                                        Created by
                                                                                                        Vidio</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/demo/index.html">Marc
                                                                                                            Campbell</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>April
                                                                                                        21, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">0</span>
                                                                                                            <span class="post-like-unit"> like</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">0</span>
                                                                                                            <span class="post-dislike-unit"> dislike</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">884</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    sanjeeb sanjeeb
                                                                                                    sanjeeb
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                        <article
                                                                                                class="grid-item video-item video-509 game vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/a-record-breaking-world-championship/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/01/technology-1.jpg"
                                                                                                             alt="A Record Breaking World Championship">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/a-record-breaking-world-championship/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="509"
                                                                                                     data-title="A Record Breaking World Championship"
                                                                                                     data-permalink="video/a-record-breaking-world-championship/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/01/technology-1.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/game/index.html"
                                                                                                       rel="tag">Game</a>,
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/a-record-breaking-world-championship/index.html">A
                                                                                                        Record
                                                                                                        Breaking
                                                                                                        World
                                                                                                        Championship</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/admin/index.html">admin</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>January
                                                                                                        14, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">2.150K</span>
                                                                                                            <span class="post-like-unit"> likes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">151</span>
                                                                                                            <span class="post-dislike-unit"> dislikes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">1.370M</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    Nullam
                                                                                                    imperdiet, sem
                                                                                                    at fringilla
                                                                                                    lobortis, sem
                                                                                                    nibh fringilla
                                                                                                    nibh, id
                                                                                                    gravidrus sit
                                                                                                    amet erat.
                                                                                                    Aenean nec nisi
                                                                                                    quis nisi...
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                        <article
                                                                                                class="grid-item video-item video-508 game vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/academy-rush-week-1-lcs-academy-spring-split/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/01/technology-10.jpg"
                                                                                                             alt="Academy Rush Week 1 &#8211; LCS Academy Spring Split">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/academy-rush-week-1-lcs-academy-spring-split/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="508"
                                                                                                     data-title="Academy Rush Week 1 &#8211; LCS Academy Spring Split"
                                                                                                     data-permalink="video/academy-rush-week-1-lcs-academy-spring-split/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/01/technology-10.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/game/index.html"
                                                                                                       rel="tag">Game</a>,
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/academy-rush-week-1-lcs-academy-spring-split/index.html">Academy
                                                                                                        Rush Week 1
                                                                                                        &#8211; LCS
                                                                                                        Academy
                                                                                                        Spring
                                                                                                        Split</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/admin/index.html">admin</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>January
                                                                                                        14, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">2.150K</span>
                                                                                                            <span class="post-like-unit"> likes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">151</span>
                                                                                                            <span class="post-dislike-unit"> dislikes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">1.369M</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    Nullam
                                                                                                    imperdiet, sem
                                                                                                    at fringilla
                                                                                                    lobortis, sem
                                                                                                    nibh fringilla
                                                                                                    nibh, id
                                                                                                    gravidrus sit
                                                                                                    amet erat.
                                                                                                    Aenean nec nisi
                                                                                                    quis nisi...
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                        <article
                                                                                                class="grid-item video-item video-507 game vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/pubg-new-lobby-music-preview/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/04/technology-9.jpg"
                                                                                                             alt="PUBG &#8211; New Lobby Music Preview">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/pubg-new-lobby-music-preview/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="507"
                                                                                                     data-title="PUBG &#8211; New Lobby Music Preview"
                                                                                                     data-permalink="video/pubg-new-lobby-music-preview/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/04/technology-9.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/game/index.html"
                                                                                                       rel="tag">Game</a>,
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/pubg-new-lobby-music-preview/index.html">PUBG
                                                                                                        &#8211; New
                                                                                                        Lobby Music
                                                                                                        Preview</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/admin/index.html">admin</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>January
                                                                                                        14, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">2.150K</span>
                                                                                                            <span class="post-like-unit"> likes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">151</span>
                                                                                                            <span class="post-dislike-unit"> dislikes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">1.369M</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    Nullam
                                                                                                    imperdiet, sem
                                                                                                    at fringilla
                                                                                                    lobortis, sem
                                                                                                    nibh fringilla
                                                                                                    nibh, id
                                                                                                    gravidrus sit
                                                                                                    amet erat.
                                                                                                    Aenean nec nisi
                                                                                                    quis nisi...
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                        <article
                                                                                                class="grid-item video-item video-506 game vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/pubg-early-days-retrospective/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/01/technology-8.jpg"
                                                                                                             alt="PUBG &#8211; Early Days Retrospective">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/pubg-early-days-retrospective/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="506"
                                                                                                     data-title="PUBG &#8211; Early Days Retrospective"
                                                                                                     data-permalink="video/pubg-early-days-retrospective/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/01/technology-8.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/game/index.html"
                                                                                                       rel="tag">Game</a>,
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/pubg-early-days-retrospective/index.html">PUBG
                                                                                                        &#8211;
                                                                                                        Early Days
                                                                                                        Retrospective</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/admin/index.html">admin</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>January
                                                                                                        14, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">2.150K</span>
                                                                                                            <span class="post-like-unit"> likes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">151</span>
                                                                                                            <span class="post-dislike-unit"> dislikes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">1.369M</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    Nullam
                                                                                                    imperdiet, sem
                                                                                                    at fringilla
                                                                                                    lobortis, sem
                                                                                                    nibh fringilla
                                                                                                    nibh, id
                                                                                                    gravidrus sit
                                                                                                    amet erat.
                                                                                                    Aenean nec nisi
                                                                                                    quis nisi...
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                        <article
                                                                                                class="grid-item video-item video-504 game vidio  default">
                                                                                            <div class="video-item__thumbnail">
                                                                                                <a href="video/stand-united-pgc-2019-official-theme-song/index.html">

                                                                                                    <div class="video-thumbnail image default"
                                                                                                         data-speed="1000">
                                                                                                        <img src="wp-content/uploads/2020/01/technology-7.jpg"
                                                                                                             alt="Stand United &#8211; PGC 2019 Official Theme Song">
                                                                                                    </div>
                                                                                                </a>
                                                                                                <div class="video-item__icon">
                                                                                                    <a href="video/stand-united-pgc-2019-official-theme-song/index.html"
                                                                                                       class="video-item-player-direct">
                                                                                                        <i class="haru-icon haru-play"></i>
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="toolbar-action toolbar-action--background video-watch-later "
                                                                                                     data-id="504"
                                                                                                     data-title="Stand United &#8211; PGC 2019 Official Theme Song"
                                                                                                     data-permalink="video/stand-united-pgc-2019-official-theme-song/index.html"
                                                                                                     data-thumb="wp-content/uploads/2020/01/technology-7.jpg"
                                                                                                >
                                                                                                    <i class="haru-icon haru-clock"></i>
                                                                                                    Watch Later
                                                                                                </div>

                                                                                                <div class="video-item__duration">
                                                                                                    9:15
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="video-item__content">
                                                                                                <div class="video-item__category">
                                                                                                    <a href="video-category/game/index.html"
                                                                                                       rel="tag">Game</a>,
                                                                                                    <a href="video-category/vidio/index.html"
                                                                                                       rel="tag">Vidio</a>
                                                                                                </div>
                                                                                                <h6 class="video-item__title">
                                                                                                    <a href="video/stand-united-pgc-2019-official-theme-song/index.html">Stand
                                                                                                        United
                                                                                                        &#8211; PGC
                                                                                                        2019
                                                                                                        Official
                                                                                                        Theme
                                                                                                        Song</a>
                                                                                                </h6>
                                                                                                <div class="video-item__meta">
                                                                                                    <div class="video-item__author">
                                                                                                        <i class="fa fa-user"></i>
                                                                                                        <a href="members/admin/index.html">admin</a>
                                                                                                    </div>
                                                                                                    <div class="video-item__date">
                                                                                                        <i class="fa fa-calendar"></i>January
                                                                                                        14, 2020
                                                                                                    </div>
                                                                                                    <div class="video-item__like">
                                                                                                        <div class="post-like">
                                                                                                            <span class="post-vote-label">like</span>
                                                                                                            <i class="haru-icon haru-like"></i>
                                                                                                            <span class="post-like-count">2.150K</span>
                                                                                                            <span class="post-like-unit"> likes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__dislike">
                                                                                                        <div class="post-dislike">
                                                                                                            <span class="post-vote-label">dislike</span>
                                                                                                            <i class="haru-icon haru-dislike"></i>
                                                                                                            <span class="post-dislike-count">151</span>
                                                                                                            <span class="post-dislike-unit"> dislikes</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="video-item__view">
                                                                                                        <div class="post-views-count">
                                                                                                            <span class="post-views-label">views</span>
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                            <span class="post-view-count">1.369M</span>
                                                                                                            <span class="post-view-unit"> views</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="video-item__desc">
                                                                                                    Nullam
                                                                                                    imperdiet, sem
                                                                                                    at fringilla
                                                                                                    lobortis, sem
                                                                                                    nibh fringilla
                                                                                                    nibh, id
                                                                                                    gravidrus sit
                                                                                                    amet erat.
                                                                                                    Aenean nec nisi
                                                                                                    quis nisi...
                                                                                                </div>
                                                                                            </div>
                                                                                        </article>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="video-control ">
                                                                                    <div class="video-control-item disable"
                                                                                         data-max_pages="7"
                                                                                         data-current_page="1"
                                                                                         data-video_order_by="date"
                                                                                         data-action="prev"
                                                                                    >
                                                                                        <i class="haru-icon haru-arrow-left"></i>
                                                                                    </div>
                                                                                    <div class="video-control-item"
                                                                                         data-max_pages="7"
                                                                                         data-current_page="1"
                                                                                         data-video_order_by="date"
                                                                                         data-action="next"
                                                                                    >
                                                                                        <i class="haru-icon haru-arrow-right"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    foreach ($PROJECT_ARRAY as $project) {
                                        if (sizeof($project['PRO_VIDEOS']) < 3 || $project['PRO_ID'] == 10652)
                                            continue;
                                        ?>
                                        <div class="vc_row wpb_row vc_row-fluid">
                                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                                <div class="vc_column-inner">
                                                    <div class="wpb_wrapper">
                                                        <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                                                <div class="vc_column-inner">
                                                                    <div class="wpb_wrapper">
                                                                        <div class="wpb_text_column wpb_content_element ">
                                                                            <div class="wpb_wrapper">
                                                                                <div class="series-top-shortcode style-4 "
                                                                                     id="series-top-shortcode1023103098"
                                                                                     data-atts="{&quot;layout&quot;:&quot;style-4&quot;,&quot;title&quot;:&quot;Top Series&quot;,&quot;categories&quot;:&quot;harutheme&quot;,&quot;order_by&quot;:&quot;date&quot;,&quot;order&quot;:&quot;desc&quot;,&quot;series_style&quot;:&quot;default&quot;,&quot;posts_per_page&quot;:&quot;6&quot;}"
                                                                                     data-series_order_by="date"
                                                                                >
                                                                                    <h6 class="series-top-title haru-shortcode-title">
                                                                                        <span><?= ucwords($project['PRO_TITLE']) ?></span>
                                                                                    </h6>

                                                                                    <div class="series-ajax-content style-4">
                                                                                        <div class="ajax-loading-icon">
                                                                                            <div class="loading-bar"></div>
                                                                                        </div>
                                                                                        <div class="series-top-content">

                                                                                            <div class="series-list grid-columns grid-columns__3 animated fadeIn haru-clear">

                                                                                                <?php

                                                                                                $count = 0;
                                                                                                foreach ($project['PRO_VIDEOS'] as $video) {
                                                                                                    if (!@getimagesize($video['VID_THUMB']))
                                                                                                        continue;
                                                                                                    $count++;
                                                                                                    if ($count > 6) //show only six items per category
                                                                                                        break;
                                                                                                    ?>
                                                                                                    <article
                                                                                                            class="grid-item series-item default">
                                                                                                        <div class="series-item__thumbnail">
                                                                                                            <a href="series/the-last-legendary/index.html">
                                                                                                                <div class="series-thumbnail"
                                                                                                                     data-speed="1000">
                                                                                                                    <!--                                                                                                                    <img src="wp-content/uploads/2020/01/travel-18.jpg"-->
                                                                                                                    <!--                                                                                                                         alt="The last legendary">-->

                                                                                                                    <img style="width: 120%"
                                                                                                                         src="<?= $video['VID_THUMB'] ?>"
                                                                                                                         alt="The last legendary">
                                                                                                                </div>
                                                                                                            </a>
                                                                                                            <div class="series-item__icon">
                                                                                                                <a href="series/the-last-legendary/index.html"
                                                                                                                   class="series-player-direct">
                                                                                                                    <i class="haru-icon haru-play"></i>
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="series-item__content">
                                                                                                            <h6 class="series-item__title">
                                                                                                                <a href=""><?= $video['VID_TITLE'] ?></a>
                                                                                                            </h6>
                                                                                                            <div class="series-item__category">
                                                                                                                <a href="series-category/harutheme/index.html"
                                                                                                                   rel="tag">HaruTheme</a>
                                                                                                            </div>
                                                                                                            <div class="series-item__meta">
                                                                                                                <div class="series-item__author">
                                                                                                                    <i class="fa fa-user"></i>
                                                                                                                    <a href="members/admin/index.html">admin</a>
                                                                                                                </div>
                                                                                                                <div class="series-item__date">
                                                                                                                    <i class="fa fa-calendar"></i>
                                                                                                                    <?= date_format(date_create($video['VID_CREATED'] ),"F d, Y"); ?>

                                                                                                                </div>
                                                                                                                <div class="series-item__like">
                                                                                                                    <div class="post-like">
                                                                                                                        <span class="post-vote-label">like</span>
                                                                                                                        <i class="haru-icon haru-like"></i>
                                                                                                                        <span class="post-like-count"><?= $video['VID_VIEWS'] ?></span>
                                                                                                                        <span class="post-like-unit"> like</span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="series-item__dislike">
                                                                                                                    <div class="post-dislike">
                                                                                                                        <span class="post-vote-label">dislike</span>
                                                                                                                        <i class="haru-icon haru-dislike"></i>
                                                                                                                        <span class="post-dislike-count">0</span>
                                                                                                                        <span class="post-dislike-unit"> dislike</span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="series-item__view">
                                                                                                                    <div class="post-views-count">
                                                                                                                        <span class="post-views-label">views</span>
                                                                                                                        <i class="fa fa-eye"></i>
                                                                                                                        <span class="post-view-count"><?= $video['VID_VIEWS'] ?></span>
                                                                                                                        <span class="post-view-unit"> views</span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="series-item__desc">
                                                                                                                <?= $video['VID_DESC'] ?>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </article>

                                                                                                    <?php
                                                                                                }


                                                                                                ?>


                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    ?>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </main>
</div>
<!-- Close HARU Content Main -->

<footer id="haru-footer-main" class="footer-5">
    <div class="container clearfix">
        <div data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div class="vc_row wpb_row vc_inner vc_row-fluid">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_100 vc_sep_pos_align_center vc_separator_no_text vc_custom_1588665615105  vc_custom_1588665615105">
                                                <span class="vc_sep_holder vc_sep_holder_l"><span
                                                            style="border-color:#eae8ea;"
                                                            class="vc_sep_line"></span></span><span
                                                    class="vc_sep_holder vc_sep_holder_r"><span
                                                        style="border-color:#eae8ea;"
                                                        class="vc_sep_line"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1588836754893">
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-4">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_single_image wpb_content_element vc_align_left  vc_custom_1594781417977  footer-logo">

                                            <figure class="wpb_wrapper vc_figure">
                                                <div class="vc_single_image-wrapper   vc_box_border_grey"><img
                                                            width="99" height="24"
                                                            src="wp-content/uploads/2019/08/logo-black.png"
                                                            class="vc_single_image-img attachment-full" alt=""
                                                            loading="lazy"/></div>
                                            </figure>
                                        </div>

                                        <div class="wpb_single_image wpb_content_element vc_align_left  vc_custom_1594781436214  footer-logo-retina">

                                            <figure class="wpb_wrapper vc_figure">
                                                <div class="vc_single_image-wrapper   vc_box_border_grey"><img
                                                            width="198" height="48"
                                                            src="wp-content/uploads/2019/08/logo-black@2x.png"
                                                            class="vc_single_image-img attachment-full" alt=""
                                                            loading="lazy"/></div>
                                            </figure>
                                        </div>

                                        <div class="wpb_text_column wpb_content_element  vc_custom_1588665744376">
                                            <div class="wpb_wrapper">
                                                <p>As we grow, we do so in fits and starts, lurching forward then
                                                    back, sometimes looking more like clowns than seekers.</p>

                                            </div>
                                        </div>
                                        <div class=" vc_custom_1588665738573  ">
                                            <div class="footer-contact-shortcode-wrap style_2 ">
                                                <div class="footer-contact-content">
                                                    <ul class="contact-information">
                                                        <li>
                                                            <span class="contact-icon fab fa-accessible-icon"></span>
                                                            <span class="contact-label">Address</span>
                                                            <span class="contact-description">100 Brooke Mission Suite 443</span>
                                                        </li>
                                                        <li>
                                                            <span class="contact-icon fab fa-accessible-icon"></span>
                                                            <span class="contact-label">Phone</span>
                                                            <span class="contact-description">760-617-8882</span>
                                                        </li>
                                                        <li>
                                                            <span class="contact-icon fab fa-accessible-icon"></span>
                                                            <span class="contact-label">Email</span>
                                                            <span class="contact-description">contact@vidio.com</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-2">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper"><h2 style="text-align: left"
                                                                 class="vc_custom_heading footer_style_3">
                                            Information</h2>
                                        <div class="  ">
                                            <div class="footer-link-shortcode-wrap style_1 ">
                                                <div class="footer-link-content">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="#" target="_self">About us</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">FAQs</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">How it works</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Report videos</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Popular posts</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Terms of Payment</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Privacy Policy</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-2">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper"><h2 style="text-align: left"
                                                                 class="vc_custom_heading footer_style_3">
                                            Categories</h2>
                                        <div class="  ">
                                            <div class="footer-link-shortcode-wrap style_1 ">
                                                <div class="footer-link-content">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="#" target="_self">Game</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">TV Show</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">TV Series</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Live Stream</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Drama</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Cinematic</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Picture</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-2">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper"><h2 style="text-align: left"
                                                                 class="vc_custom_heading footer_style_3">Quick
                                            Links</h2>
                                        <div class="  ">
                                            <div class="footer-link-shortcode-wrap style_1 ">
                                                <div class="footer-link-content">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="#" target="_self">Submit Video</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Terms &amp; Privacy</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">For Advertisers</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Contact Us</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Career Site</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Terms of Payment</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Report Video</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-2">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper"><h2 style="text-align: left"
                                                                 class="vc_custom_heading footer_style_3">Latest
                                            Video</h2>
                                        <div class="  ">
                                            <div class="footer-link-shortcode-wrap style_1 ">
                                                <div class="footer-link-content">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="#" target="_self">Submit Video</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Terms &amp; Privacy</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">For Advertisers</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Contact Us</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Career Site</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Terms of Payment</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self">Report Video</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vc_row wpb_row vc_inner vc_row-fluid">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_100 vc_sep_pos_align_center vc_separator_no_text vc_custom_1588665615105  vc_custom_1588665615105">
                                                <span class="vc_sep_holder vc_sep_holder_l"><span
                                                            style="border-color:#eae8ea;"
                                                            class="vc_sep_line"></span></span><span
                                                    class="vc_sep_holder vc_sep_holder_r"><span
                                                        style="border-color:#eae8ea;"
                                                        class="vc_sep_line"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vc_row wpb_row vc_inner vc_row-fluid">
                            <div class="haru-col-sm-6 wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_text_column wpb_content_element  vc_custom_1604162288188 secondary-font">
                                            <div class="wpb_wrapper">
                                                <p style="text-align: left;">© 2021 Vidio by HaruTheme. All Right
                                                    Reserved.</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <div class=" vc_custom_1592186015098  ">
                                            <div class="footer-social-shortcode-wrap style_1 footer-social-3">
                                                <div class="footer-social-content">
                                                    <ul class="social-list align-right">
                                                        <li>
                                                            <a href="#" target="_self"
                                                               style="background-color: #3b5998"><i
                                                                        class="fab fa-facebook-f"></i>Facebook</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self"
                                                               style="background-color: #00acee"><i
                                                                        class="fab fa-twitter"></i>Twitter</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self"
                                                               style="background-color: #6a453b"><i
                                                                        class="fab fa-instagram"></i>Instagram</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self"
                                                               style="background-color: #c4302b"><i
                                                                        class="fab fa-youtube"></i>Youtube</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" target="_self"
                                                               style="background-color: #86c9ef"><i
                                                                        class="fab fa-vimeo-v"></i>Vimeo</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vc_row-full-width vc_clearfix"></div>
    </div>
</footer>
</div>
<!-- Close haru main -->
<a class="back-to-top" href="javascript:;">
    <i class="fa fa-chevron-up"></i>
</a>
<div class="haru-ajax-overflow">
    <div class="haru-ajax-loading">
        <div class="loading-wrapper">
            <div class="spinner" id="spinner_one"></div>
            <div class="spinner" id="spinner_two"></div>
            <div class="spinner" id="spinner_three"></div>
            <div class="spinner" id="spinner_four"></div>
            <div class="spinner" id="spinner_five"></div>
            <div class="spinner" id="spinner_six"></div>
            <div class="spinner" id="spinner_seven"></div>
            <div class="spinner" id="spinner_eight"></div>
        </div>
    </div>
</div>
<div id="haru-menu-popup" class="white-popup-block mfp-hide mfp-with-anim">
    <div id="primary-menu" class="menu-wrapper">
        <ul id="main-menu" class="haru-nav-popup-menu">
            <li id="menu-item-1265"
                class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children level-0 ">
                <a href="index.php">Home</a><b class="menu-caret"></b>
                <ul class="sub-menu animated menu_fadeInDown" style="">
                    <li id="menu-item-1264"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page menu-item-home level-1 ">
                        <a href="index.php">Home 1 (Basic)</a></li>
                    <li id="menu-item-1263"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="home-2/index.html">Home 2 (Creative 1)</a></li>
                    <li id="menu-item-1262"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="home-3/index.html">Home 3 (Creative 2)</a></li>
                    <li id="menu-item-1261"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="home-4/index.html">Home 4 (Creative 3)</a></li>
                    <li id="menu-item-1260"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-806 current_page_item level-1 ">
                        <a href="index.php">Home 5 (Creative 4)</a></li>
                </ul>
            </li>
            <li id="menu-item-1266"
                class="haru-menu menu_style_dropdown   menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children level-0 ">
                <a href="#">Submit</a><b class="menu-caret"></b>
                <ul class="sub-menu animated menu_fadeInDown" style="">
                    <li id="menu-item-1268"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="submit-video/index.html">Submit Video</a></li>
                    <li id="menu-item-1271"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="submit-channel/index.html">Submit Channel</a></li>
                    <li id="menu-item-1270"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="submit-playlist/index.html">Submit Playlist</a></li>
                    <li id="menu-item-1269"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="submit-series/index.html">Submit Series</a></li>
                </ul>
            </li>
            <li id="menu-item-1267"
                class="haru-menu menu_style_dropdown   menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children level-0 ">
                <a href="#">Pages</a><b class="menu-caret"></b>
                <ul class="sub-menu animated menu_fadeInDown" style="">
                    <li id="menu-item-2433"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="blog/index.html">Blog</a></li>
                    <li id="menu-item-2434"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-1 ">
                        <a href="shop/index.html">Shop</a></li>
                    <li id="menu-item-1274"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children level-1 ">
                        <a href="about-us/index.html">About Us</a><b class="menu-caret"></b>
                        <ul class="sub-menu animated menu_fadeInDown" style="">
                            <li id="menu-item-1275"
                                class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-2 ">
                                <a href="about-us-2/index.html">About Us 2</a></li>
                        </ul>
                    </li>
                    <li id="menu-item-1273"
                        class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children level-1 ">
                        <a href="contact-us/index.html">Contact Us</a><b class="menu-caret"></b>
                        <ul class="sub-menu animated menu_fadeInDown" style="">
                            <li id="menu-item-1272"
                                class="haru-menu menu_style_dropdown   menu-item menu-item-type-post_type menu-item-object-page level-2 ">
                                <a href="contact-us-2/index.html">Contact Us 2</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="haru-lightbox-overlay"></div>
<div class="haru-lightbox">
    <div class="close-lightbox"><i class="haru-icon haru-times"></i></div>
</div>

</body>

</html>