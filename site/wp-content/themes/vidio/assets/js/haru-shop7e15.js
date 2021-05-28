/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

var HARUSHOPMAIN = HARUSHOPMAIN || {};
(function ($) {
    "use strict";

    var $window = $(window),
        deviceAgent = navigator.userAgent.toLowerCase(),
        isMobile    = deviceAgent.match(/(iphone|ipod|android|iemobile)/),
        isMobileAlt = deviceAgent.match(/(iphone|ipod|ipad|android|iemobile)/),
        $body       = $('body');

    var products_ajax_category_data = []; // Use this for product ajax category shortcode
    var products_ajax_creative_data = []; // Use this for product ajax category shortcode
    var products_ajax_order_data = []; // Use this for product ajax order shortcode

    // WooCommerce functions
    HARUSHOPMAIN.WooCommerce = {
        init: function() {
            HARUSHOPMAIN.WooCommerce.singleProductImages();
            HARUSHOPMAIN.WooCommerce.singleProductImagesGallery();
            HARUSHOPMAIN.WooCommerce.singleProductVideo();
            HARUSHOPMAIN.WooCommerce.singleProductSizeGuide();
            HARUSHOPMAIN.WooCommerce.shopMasonry();
            HARUSHOPMAIN.WooCommerce.addToCart();
            HARUSHOPMAIN.WooCommerce.addToWishlist();
            HARUSHOPMAIN.WooCommerce.compare();
            HARUSHOPMAIN.WooCommerce.quickView();
            HARUSHOPMAIN.WooCommerce.changeArchiveLayout();
            HARUSHOPMAIN.WooCommerce.addToCartVariation();
            HARUSHOPMAIN.WooCommerce.productAttribute();
            HARUSHOPMAIN.WooCommerce.productQuantity();
            // Shortcode ajax
            HARUSHOPMAIN.WooCommerce.shortcodeIsotope();
            // Product ajax category
            HARUSHOPMAIN.WooCommerce.productAjaxCategory();
            HARUSHOPMAIN.WooCommerce.productAjaxCreative();
            HARUSHOPMAIN.WooCommerce.productAjaxOrder();
            HARUSHOPMAIN.WooCommerce.productCountdown();
        },
        windowResized : function () {
            // Do something
        },
        windowLoad : function() {
            // Do something
        },
        singleProductImages : function() {
            // http://poligon.pro/owl/
            // https://github.com/OwlCarousel2/OwlCarousel2/issues/80
            // https://codepen.io/washaweb/pen/KVRxRW
            if ( $('.haru-single-product').length ) {
                var product_images    = $("#product-images1", ".single-product-image-inner");
                var product_thumbnails    = $("#product-thumbnails1", ".single-product-image-inner");

                product_images.slick();
                product_thumbnails.slick();

                HARUSHOPMAIN.WooCommerce.singleProductImageZoom();

                $(document).on('change','.variations_form .variations select,.variations_form .variation_form_section select,div.select',function() {
                    var variation_form   = $(this).closest( '.variations_form' );
                    var current_settings = {},
                        reset_variations = variation_form.find( '.reset_variations' );
                    variation_form.find('.variations select,.variation_form_section select' ).each( function() {
                        // Encode entities
                        var value = $(this ).val();

                        // Add to settings array
                        current_settings[ $( this ).attr( 'name' ) ] = jQuery(this ).val();
                    });

                    variation_form.find('.variation_form_section div.select input[type="hidden"]' ).each( function() {
                        // Encode entities
                        var value = $(this ).val();

                        // Add to settings array
                        current_settings[ $( this ).attr( 'name' ) ] = jQuery(this ).val();
                    });

                    var all_variations = variation_form.data( 'product_variations' );
                    var variation_id   = 0;
                    var match          = true;

                    for (var i = 0; i < all_variations.length; i++) {
                        match                     = true;
                        var variations_attributes = all_variations[i]['attributes'];
                        for(var attr_name in variations_attributes) {
                            var val1 = variations_attributes[attr_name];
                            var val2 = current_settings[attr_name];
                            if (val1 == undefined || val2 == undefined ) {
                                match = false;
                                break;
                            }
                            if (val1.length == 0) {
                                continue;
                            }

                            if (val1 != val2) {
                                match = false;
                                break;
                            }
                        }
                        if (match) {
                            variation_id = all_variations[i]['variation_id'];
                            break;
                        }
                    }
                    if (variation_id > 0) {
                        var index = parseInt($('a[data-variation_id*="|'+variation_id+'|"]','#product-images1').data('index'),10) ;

                        var gallery_index = parseInt($('a[data-variation_id*="|'+variation_id+'|"]','#product-images').data('index'),10) ;

                        if ( !isNaN(gallery_index) ) {
                            console.log(gallery_index);
                            var speed = 500,
                                href= $(this).attr("href"),
                                target = $('.woocommerce-image-zoom:nth-child(' + (gallery_index +1 ) + ')'),
                                position = target.offset().top;

                            $('body').animate({scrollTop:position}, speed, 'swing');
                            $('html').animate({scrollTop:position}, speed, 'swing');
                        }

                        if ( !isNaN(index) ) {
                            product_images.slick('slickGoTo', index, true);
                        }
                    }
                });
            }
        },
        singleProductImagesGallery: function() {
            if ( $('.single-product-content #product-images').length ) {
                $('#product-thumbnails').onePageNav({
                    currentClass: 'current'
                });
                $("#product-thumbnails, .entry-summary").stick_in_parent();
            }
        },
        singleProductImageZoom: function() {
            if ( !isMobile && !isMobileAlt ) {
                $('.haru-single-product .woocommerce-image-zoom').zoom({
                    on: 'mouseover',
                    magnify: 1.3
                });
            }
        },
        singleProductVideo: function() {
            $('.product-video-link').magnificPopup({
                type: 'iframe',
                iframe: {
                    markup: '<div class="mfp-iframe-scaler">'+
                            '<div class="mfp-close"></div>'+
                            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                            '<div class="mfp-title">Some caption</div>'+
                          '</div>'
                },
                callbacks: {
                    markupParse: function(template, values, item) {
                        values.title = item.el.attr('title');
                    }   
                }
            });
        },
        singleProductSizeGuide: function() {
            $('.product-size-guide-link').magnificPopup({
                type: 'image',
                callbacks: {
                    markupParse: function(template, values, item) {
                        values.title = item.el.attr('title');
                    }   
                }
            });
        },
        shopMasonry : function() {
            var $shop_masonry = $('.haru-archive-product .archive-product-wrapper .products');

            if ( $shop_masonry.length ) {
                $shop_masonry.imagesLoaded( function() {
                    $shop_masonry.isotope({
                        itemSelector : 'li.product',
                        'gutter': 10,
                        layoutMode: "fitRows" // masonry
                    });

                    setTimeout(function () {
                        $shop_masonry.isotope('layout');
                    }, 500);
                });
            }
        },
        changeArchiveLayout : function() {
            // Set default layout
            if ($.cookie( 'gridcookie' ) == null) {
                $( 'ul.products' ).addClass( 'grid' );
                $( '.gridlist-toggle #grid' ).addClass( 'active' );
            }

            // Change layout when click
            $('#grid').on('click', function() {
                $(this).addClass('active');
                $('#list').removeClass('active');
                $.cookie('gridcookie','grid', { path: '/' });
                $('ul.products').fadeOut(300, function() {
                    $(this).addClass('grid').removeClass('list').fadeIn(300);
                    HARUSHOPMAIN.WooCommerce.shopMasonry();
                });
                return false;
            });

            $('#list').on('click', function() {
                $(this).addClass('active');
                $('#grid').removeClass('active');
                $.cookie('gridcookie','list', { path: '/' });
                $('ul.products').fadeOut(300, function() {
                    $(this).removeClass('grid').addClass('list').fadeIn(300);
                    HARUSHOPMAIN.WooCommerce.shopMasonry();
                });
                return false;
            });

            if ($.cookie('gridcookie')) {
                $('ul.products, #gridlist-toggle').addClass($.cookie('gridcookie'));
            }

            if ($.cookie('gridcookie') == 'grid') {
                $('.gridlist-toggle #grid').addClass('active');
                $('.gridlist-toggle #list').removeClass('active');
            }

            if ($.cookie('gridcookie') == 'list') {
                $('.gridlist-toggle #list').addClass('active');
                $('.gridlist-toggle #grid').removeClass('active');
            }

            $('#gridlist-toggle a').on('click', function(event) {
                event.preventDefault();
            });
        },
        addToCart : function () {
            $(document).on('click', '.add_to_cart_button', function () {

                var button = $(this),
                    buttonWrap = button.parent();

                if (!button.hasClass('single_add_to_cart_button') && button.is( '.product_type_simple' )) {
   
                    button.addClass("added-spinner");
                    button.find('i').attr('class', 'fa fa-spinner fa-spin');
                }

            });

            $body.on("added_to_cart", function (event, fragments, cart_hash, $thisbutton) {

                HARUSHOPMAIN.WooCommerce.init();

                var is_single_product = $thisbutton.hasClass('single_add_to_cart_button');

                if (is_single_product) return;

                setTimeout(function () {
                    var button         = $thisbutton,
                        buttonWrap     = button.parent(),
                        buttonViewCart = buttonWrap.find('.added_to_cart'),
                        // addedTitle     = buttonViewCart.text(),
                        productWrap    = buttonWrap.parent().parent().parent().parent();

                        // buttonWrap.find('.added').remove();
                        buttonViewCart.html('<i class="fa fa-check"></i><span class="haru-tooltip button-tooltip">' + haru_framework_constant.product_viewcart + '</span>');
                }, 10);

            });
        },
        addToWishlist : function() {
            $(document).on('click', '.add_to_wishlist', function () {
                var button = $(this),
                    buttonWrap = button.parent().parent();

                if (!buttonWrap.parent().hasClass('single-product-function')) {
                    button.addClass("added-spinner");
                    button.find('i').attr('class', 'fa fa-spinner fa-spin');

                    var productWrap = buttonWrap.parent().parent().parent().parent();
                    if (typeof(productWrap) == 'undefined') {
                        return;
                    }
                    productWrap.addClass('active');
                }

            });

            $body.on("added_to_wishlist", function (event, fragments, cart_hash, $thisbutton) {
                var button = $('.added-spinner.add_to_wishlist'),
                    buttonWrap = button.parent().parent();
                if (!buttonWrap.parent().hasClass('single-product-function')) {
                    var productWrap = buttonWrap.parent().parent().parent().parent();
                    if (typeof(productWrap) == 'undefined') {
                        return;
                    }
                    setTimeout(function () {
                        productWrap.removeClass('active');
                        button.removeClass('added-spinner');
                    }, 700);
                }
                // Add to update wishlist
                HARUSHOPMAIN.WooCommerce.updateWishlist();
            });
            // Add to update wishlist on wishlist page
            $('#yith-wcwl-form table tbody tr td a.remove, #yith-wcwl-form table tbody tr td a.add_to_cart_button').on('click',function() {
                var old_num_product = $('#yith-wcwl-form table tbody tr[id^="yith-wcwl-row"]').length;
                var count = 1;
                var time_interval = setInterval(function(){
                    count++;
                    var new_num_product = $('#yith-wcwl-form table tbody tr[id^="yith-wcwl-row"]').length;
                    if( old_num_product != new_num_product || count == 20 ) {
                        clearInterval(time_interval);
                        HARUSHOPMAIN.WooCommerce.updateWishlist();
                    }
                },500);
            });
        },
        updateWishlist : function() {
            if( typeof haru_framework_ajax_url == 'undefined' ) {
                return;
            }
                
            var wishlist_wrapper = jQuery('.my-wishlist-wrap');
            if( wishlist_wrapper.length == 0 ) {
                return;
            }
            
            wishlist_wrapper.addClass('loading');
            
            jQuery.ajax({
                type : 'POST',
                url : haru_framework_ajax_url,
                data : {
                    action : 'update_woocommerce_wishlist'
                },
                success : function(response) {
                    var first_icon = wishlist_wrapper.children('i.fa:first');
                    wishlist_wrapper.html(response);
                    if( first_icon.length > 0 ){
                        wishlist_wrapper.prepend(first_icon);
                    }
                    wishlist_wrapper.removeClass('loading');
                }
            });      
        },
        compare : function () {
            $('a.compare').on('click', function (event) {
                event.preventDefault();
                var button = $(this);
                
                setTimeout(function () {
                    button.html('<i class="fa fa-signal"></i><span class="haru-tooltip button-tooltip">' + haru_framework_constant.product_compare + '</span>');
                }, 5000);
                // Maybe need change time to make icon work
            });
        },
        quickView : function() {
            $('a.quickview').prettyPhoto({
                deeplinking: false,
                opacity: 1,
                social_tools: false,
                default_width: 900,
                default_height: 650,
                theme: 'pp_woocommerce',
                changepicturecallback : function() {
                    $('.pp_inline').find('form.variations_form').wc_variation_form();
                    $('.pp_inline').find('form.variations_form .variations select').change();
                    $('body').trigger('wc_fragments_loaded');
                    
                    $('.pp_woocommerce').addClass('loaded');

                    var product_images    = $("#product-images1", ".popup-product-quick-view-wrapper");
                    var product_thumbnails    = $("#product-thumbnails1", ".popup-product-quick-view-wrapper");

                    // Maybe use settimeout to fix
                    product_images.slick();
                    product_thumbnails.slick();

                    // Re run addToCartVariation to make it work
                    HARUSHOPMAIN.WooCommerce.addToCartVariation();

                    $(document).on('change','.variations_form .variations select,.variations_form .variation_form_section select,div.select',function() {
                        var variation_form   = $(this).closest( '.variations_form' );
                        var current_settings = {},
                            reset_variations = variation_form.find( '.reset_variations' );
                        variation_form.find('.variations select,.variation_form_section select' ).each( function() {
                            // Encode entities
                            var value = $(this).val();

                            // Add to settings array
                            current_settings[ $(this).attr( 'name' ) ] = jQuery(this).val();
                        });

                        variation_form.find('.variation_form_section div.select input[type="hidden"]' ).each( function() {
                            // Encode entities
                            var value = $(this).val();

                            // Add to settings array
                            current_settings[ $(this).attr( 'name' ) ] = jQuery(this).val();
                        });

                        var all_variations = variation_form.data( 'product_variations' );
                        var variation_id   = 0;
                        var match          = true;

                        for (var i = 0; i < all_variations.length; i++) {
                            match                     = true;
                            var variations_attributes = all_variations[i]['attributes'];
                            for(var attr_name in variations_attributes) {
                                var val1 = variations_attributes[attr_name];
                                var val2 = current_settings[attr_name];
                                if (val1 == undefined || val2 == undefined ) {
                                    match = false;
                                    break;
                                }
                                if (val1.length == 0) {
                                    continue;
                                }

                                if (val1 != val2) {
                                    match = false;
                                    break;
                                }
                            }
                            if (match) {
                                variation_id = all_variations[i]['variation_id'];
                                break;
                            }
                        }

                        if (variation_id > 0) {
                            var index = parseInt($('a[data-variation_id*="|'+variation_id+'|"]','#product-images1').data('index'),10) ;
                            if (!isNaN(index) ) {
                                product_images.slick('slickGoTo', index, true);
                            }
                        }
                    });
                }
            });
        },
        addToCartVariation: function() {
            $('.variations_form .variations ul.variable-items-wrapper').each(function (i, el) {

                var select = $(this).prev('select');
                var li = $(this).find('li');
                $(this).on('click', 'li:not(.selected)', function () {
                    var value = $(this).data('value');

                    li.removeClass('selected');
                    select.val('').trigger('change'); // Add to fix VM15713:1 Uncaught TypeError: Cannot read property 'length' of null
                    select.val(value).trigger('change');
                    $(this).addClass('selected');
                });

                $(this).on('click', 'li.selected', function () {
                    li.removeClass('selected');
                    select.val('').trigger('change');
                    select.trigger('click');
                    select.trigger('focusin');
                    select.trigger('touchstart');
                });
            });

            $('.variations_form .variations').each(function (i, el) {
                $(this).on('click', '.reset_variations',function() {
                    $('.variations_form .variations').find('li').removeClass('selected');
                });
            });
        },
        productAttribute: function () {
            $body.on('click', '.haru-variation-image', function (e) {
                e.preventDefault();
                $(this).siblings('.haru-variation-image').removeClass('selected');
                $(this).addClass('selected');
                var imgSrc = $(this).data('src'),
                    imgSrcSet = $(this).data('src-set'),
                    $mainImages = $(this).parents('li.product').find('.product-thumbnail .product-thumb-primary'),
                    $image = $mainImages.find('img').first(),
                    imgWidth = $image.first().width(),
                    imgHeight = $image.first().height();

                $mainImages.css({
                    width  : imgWidth,
                    height : imgHeight,
                    display: 'block'
                });

                $image.attr('src', imgSrc);

                if (imgSrcSet) {
                    $image.attr('srcset', imgSrcSet);
                }

                $image.load(function () {
                    $mainImages.removeAttr('style');
                });
            });
        },
        productQuantity: function () {
            $(document.body).on("click", ".quantity .input-button", function() {
                var t = $(this),
                    o = t.siblings(".input-text").val(),
                    i = "" === o ? 0 : parseInt(o, 10);
                t.is(".plus") ? i++ : i > 0 && t.is(".minus") && i--, t.siblings(".qty").val(i).trigger("change")
            });
        },
        shortcodeIsotope: function () {
            var default_filter = [];
            var array_filter   = []; // Push filter to an array to process when don't have filter

            $('.products-shortcode-wrap').each(function(index, value) {
                // Process filter each shortcode
                $(this).find('.product-filters ul li').first().find('a').addClass('selected');
                default_filter[index] = $(this).find('.product-filters ul li').first().find('a').attr('data-option-value')

                var self            = $(this);
                var $container      = $(this).find('.products'); // parent element of .item
                var $filter         = $(this).find('.product-filters a');
                var masonry_options = {
                    'gutter' : 0
                };
                
                array_filter[index] = $filter;

                // Add to process products layout style
                var shortcode_inner = '.products-shortcode-wrap';
                var layoutMode      = 'fitRows';
                if ( ($(shortcode_inner).hasClass('masonry')) ) {
                    var layoutMode = 'masonry';
                }

                for( var i = 0; i < array_filter.length; i++ ) {
                    if( array_filter[i].length == 0 ) {
                        default_filter = '';
                    }
                    $container.isotope({
                        itemSelector : 'li.product', // .item
                        transitionDuration : '0.5s',
                        masonry : masonry_options,
                        layoutMode : layoutMode,
                        filter: default_filter[i]
                    });   
                }                  

                imagesLoaded(self,function(){
                    $container.isotope('layout');
                });

                $(window).resize(function(){
                    $container.isotope('layout');
                });

                $filter.on('click', function(e){
                    e.stopPropagation();
                    e.preventDefault();

                    var $this = jQuery(this);
                    // don't proceed if already selected
                    if ($this.hasClass('selected')) {
                        return false;
                    }
                    var filters = $this.closest('ul');
                    filters.find('.selected').removeClass('selected');
                    $this.addClass('selected');

                    var options = {
                            layoutMode : 'fitRows',
                            transitionDuration : '0.5s',
                            'masonry' : {
                                'gutter' : 0
                            }
                        },
                    key          = filters.attr('data-option-key'),
                    value        = $this.attr('data-option-value');
                    value        = value === 'false' ? false : value;
                    options[key] = value;

                    $container.isotope(options);
                });

                // Loadmore
                $('.product-load-more', self).off().on('click', function (event) {
                    event.preventDefault();

                    var $this           = $(this).button('loading');
                    var link            = $(this).attr('data-href');
                    var element         = '.products-shortcode-wrap li.product'; // .item

                    $.get(link, function (data) {
                        var next_href = $('.product-load-more', data).attr('data-href');
                        var $newElems = $(element, data).css({
                            opacity: 0
                        });

                        $container.append($newElems);
                        $newElems.imagesLoaded(function () {
                            $newElems.animate({
                                opacity: 1
                            });

                            $container.isotope('appended', $newElems);
                            setTimeout(function() {
                                $container.isotope('layout');
                            }, 400);

                            HARUSHOPMAIN.WooCommerce.init();
                        });

                        if (typeof(next_href) == 'undefined') {
                            $this.parent().remove();
                        } else {
                            $this.button('reset');
                            $this.attr('data-href', next_href);
                        }
                    });
                });

                // Infinite Scroll
                $container.infinitescroll({
                    navSelector: "#infinite_scroll_button",
                    nextSelector: "#infinite_scroll_button a",
                    itemSelector: ".products-shortcode-wrap li.product", // .item
                    loading: {
                        'selector': '#infinite_scroll_loading',
                        'img': haru_framework_theme_url + '/assets/images/ajax-loader.gif',
                        'msgText': 'Loading...',
                        'finishedMsg': ''
                    }
                }, function (newElements, data, url) {
                    var $newElems = $(newElements).css({
                        opacity: 0
                    });
                    $newElems.imagesLoaded(function () {
                        $newElems.animate({
                            opacity: 1
                        });

                        $container.isotope('appended', $newElems);
                        setTimeout(function() {
                            $container.isotope('layout');
                        }, 400);

                        HARUSHOPMAIN.WooCommerce.init();
                    });
                });
            });
        },
        productAjaxCategory: function () {
            $('.haru-woo-shortcodes-products-ajax-category').each(function(){
                var $this = $(this);

                $this.find('.products-tabs .tab-item').on('click', function() {
                    if ( $(this).hasClass('current') || $(this).parents('.haru-woo-shortcodes-products-ajax-category').find('.products-content').hasClass('loading') ){
                        return;
                    }
                    var element       = $(this).parents('.haru-woo-shortcodes-products-ajax-category');
                    var element_id    = element.attr('id');
                    var product_cat   = $(this).data('product_cat');
                    var see_more_link = $(this).data('link');
                    var atts          = element.data('atts');
                    
                    var is_all_tab = $(this).hasClass('all-tab') ? 1 : 0;
                    
                    if ( element.find('a.see-more-button').length > 0 ) {
                        element.find('a.see-more-button').attr('href', see_more_link);
                    }
                    
                    element.find('.products-tabs .tab-item').removeClass('current');
                    $(this).addClass('current');
                    
                    /* Check cache */
                    var tab_data_index = element_id + '-' + product_cat.toString().split(',').join('-');
                    if ( products_ajax_category_data[tab_data_index] != undefined ){
                        element.find('.products-content .products').remove();
                        element.find('.products-content').append( products_ajax_category_data[tab_data_index] );

                        // Generate Isotope Grid
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_isotope( element );

                        // Generate Products Slider
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_slider( element, atts.show_nav, atts.auto_play, atts.columns, atts.slide_duration );
                        
                        // Recall quickView
                        HARUSHOPMAIN.WooCommerce.quickView();

                        /* See more button handle */
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_view_more( element, atts );
                        
                        return;
                    }
                    
                    element.find('.products-content').addClass('loading');

                    $.ajax({
                        type : "POST",
                        timeout : 30000,
                        url : haru_framework_ajax_url,
                        data : {
                            action: 'haru_get_product_content_in_category_tab', 
                            atts: atts, 
                            product_cat: product_cat, 
                            is_all_tab: is_all_tab
                        },
                        error: function(xhr,err) {
                            
                        },
                        success: function(response) {
                            if ( response ) {       
                                element.find('.products-content .products').remove();
                                element.find('.products-content').append( response ).find('li.product-type').css('opacity',0).animate({opacity: 1},500);

                                /* save cache */
                                products_ajax_category_data[tab_data_index] = response;
                                
                                /* See more button handle */
                                HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_view_more( element, atts );

                                /* Generate isotope */
                                if ( $this.hasClass('grid') ) {
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_isotope( element );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                } 
                                if ( $this.hasClass('slider') ) { 
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_category_slider( element, atts.show_nav, atts.auto_play, atts.columns, atts.slide_duration );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                }
                            }
                            element.find('.products-content').removeClass('loading');
                        }
                    });
                });
            });

            // Click first tab when page load
            $('.haru-woo-shortcodes-products-ajax-category.grid').each(function() {
                $(this).find('.products-tabs .tab-item:first').trigger('click');
            });

            $('.haru-woo-shortcodes-products-ajax-category.slider').each(function() {
                $(this).find('.products-tabs .tab-item:first').trigger('click');
            });
        },
        haru_product_ajax_category_isotope: function(element) {
            if ( element.find('.products-content.grid li.type-product').length > 0 ) {
                setTimeout(function(){
                    element.find('.products-content.grid .products').isotope({
                        itemSelector : 'li.type-product',
                        layoutMode: 'fitRows'
                    });

                    imagesLoaded(element, function() {
                        element.find('.products-content.grid .products').isotope('layout');
                    });
                }, 100);
            }
        },
        haru_product_ajax_category_slider: function( element, show_nav, auto_play, columns, slide_duration ) {
            if( element.find('.products-content li.type-product').length > 0 ) {
                show_nav       = (show_nav == 1) ? true : false;
                auto_play      = (auto_play == 1) ? true : false;
                columns        = parseInt(columns);
                slide_duration = parseInt(slide_duration);
                var _slider_data = { 
                    items : columns,
                    margin: 20,
                    loop: true,
                    center: false,
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    freeDrag: false,
                    stagePadding: 0,
                    merge: false,
                    mergeFit: true,
                    autoWidth: false,

                    startPosition: 0,
                    URLhashListener: false,
                    nav: show_nav,
                    navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                    rewind: true,
                    navElement: 'div',

                    slideBy: 1,
                    dots: true,
                    dotsEach: false,
                    lazyLoad: false,
                    lazyContent: false,

                    autoplay: auto_play,
                    autoplayTimeout: slide_duration, // Need change to option
                    autoplayHoverPause: true,
                    smartSpeed: 250,
                    fluidSpeed: false,
                    autoplaySpeed: false,
                    navSpeed: false,
                    dotsSpeed: false,
                    dragEndSpeed: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        500: {
                            items: 2
                        },
                        991: {
                            items: columns
                        },
                        1200: {
                            items: columns
                        },
                        1300: {
                            items: columns
                        }
                    },
                    responsiveRefreshRate: 200,
                    responsiveBaseElement: window,
                    video: false,
                    videoHeight: false,
                    videoWidth: false,
                    animateOut: false,
                    animateIn: false,
                    fallbackEasing: 'swing',

                    info: false,

                    nestedItemSelector: false,
                    itemElement: 'div',
                    stageElement: 'div',

                    navContainer: false,
                    dotsContainer: false
                };
                
                element.find('.products-content.slider .products').owlCarousel( _slider_data );
            }
        },
        haru_product_ajax_category_view_more: function(element, atts) {
            var hide_see_more = element.find('.products .hide-see-more').length;
            element.find('.products .hide-see-more').remove();
            
            if ( element.find('.tab-item.current').hasClass('general-tab') && atts.hide_see_more_general_tab == 1 ) {
                hide_see_more = true;
            }
            
            if ( element.find('.products .product').length == 0 ) {
                hide_see_more = true;
            }
            
            if ( atts.show_see_more_button == 1 ) {
                if ( hide_see_more ) {
                    element.find('.see-more-wrapper').addClass('hidden');
                    element.removeClass('has-see-more-button');
                }
                else {
                    element.find('.see-more-wrapper').removeClass('hidden');
                    element.addClass('has-see-more-button');
                }
            }
        },
        // Creative
        productAjaxCreative: function () {
            $('.haru-woo-shortcodes-products-ajax-creative').each(function(){
                var $this = $(this);

                $this.find('.products-tabs .tab-item').on('click', function() {
                    if ( $(this).hasClass('current') || $(this).parents('.haru-woo-shortcodes-products-ajax-creative').find('.products-content').hasClass('loading') ){
                        return;
                    }
                    var element       = $(this).parents('.haru-woo-shortcodes-products-ajax-creative');
                    var element_id    = element.attr('id');
                    var product_cat   = $(this).data('product_cat');
                    var see_more_link = $(this).data('link');
                    var atts          = element.data('atts');
                    
                    var is_all_tab = $(this).hasClass('all-tab') ? 1 : 0;
                    
                    if ( element.find('a.see-more-button').length > 0 ) {
                        element.find('a.see-more-button').attr('href', see_more_link);
                    }
                    
                    element.find('.products-tabs .tab-item').removeClass('current');
                    $(this).addClass('current');
                    
                    /* Check cache */
                    var tab_data_index = element_id + '-' + product_cat.toString().split(',').join('-');
                    if ( products_ajax_creative_data[tab_data_index] != undefined ){
                        element.find('.products-content .products').remove();
                        element.find('.products-content').append( products_ajax_creative_data[tab_data_index] );

                        // Generate Isotope Grid
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_creative_isotope( element );

                        // Generate Products Slider
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_creative_slider( element, atts.show_nav, atts.auto_play, atts.columns, atts.slide_duration );
                        
                        // Recall quickView
                        HARUSHOPMAIN.WooCommerce.quickView();
                        
                        return;
                    }
                    
                    element.find('.products-content').addClass('loading');

                    $.ajax({
                        type : "POST",
                        timeout : 30000,
                        url : haru_framework_ajax_url,
                        data : {
                            action: 'haru_get_product_content_creative', 
                            atts: atts, 
                            product_cat: product_cat, 
                            is_all_tab: is_all_tab
                        },
                        error: function(xhr,err) {
                            
                        },
                        success: function(response) {
                            if ( response ) {       
                                element.find('.products-content .products').remove();
                                element.find('.products-content').append( response ).find('li.product-type').css('opacity',0).animate({opacity: 1},500);

                                /* save cache */
                                products_ajax_creative_data[tab_data_index] = response;

                                /* Generate isotope */
                                if ( $this.hasClass('grid') ) {
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_creative_isotope( element );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                } 
                                if ( $this.hasClass('slider') ) { 
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_creative_slider( element, atts.show_nav, atts.auto_play, atts.columns, atts.slide_duration );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                }
                            }
                            element.find('.products-content').removeClass('loading');
                        }
                    });
                });
            });

            // Click first tab when page load
            $('.haru-woo-shortcodes-products-ajax-creative.grid').each(function() {
                $(this).find('.products-tabs .tab-item:first').trigger('click');
            });

            $('.haru-woo-shortcodes-products-ajax-creative.slider').each(function() {
                $(this).find('.products-tabs .tab-item:first').trigger('click');
            });
        },
        haru_product_ajax_creative_isotope: function(element) {
            if ( element.find('.products-content.grid li.type-product').length > 0 ) {
                setTimeout(function(){
                    element.find('.products-content.grid .products').isotope({
                        itemSelector : 'li.type-product',
                        layoutMode: 'masonry'
                    });
                }, 100);
            }
        },
        haru_product_ajax_creative_slider: function( element, show_nav, auto_play, columns, slide_duration ) {
            if( element.find('.products-content li.type-product').length > 0 ) {
                show_nav       = (show_nav == 1) ? true : false;
                auto_play      = (auto_play == 1) ? true : false;
                columns        = parseInt(columns);
                slide_duration = parseInt(slide_duration);
                var _slider_data = { 
                    items : columns,
                    margin: 20,
                    loop: true,
                    center: false,
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    freeDrag: false,
                    stagePadding: 0,
                    merge: false,
                    mergeFit: true,
                    autoWidth: false,

                    startPosition: 0,
                    URLhashListener: false,
                    nav: show_nav,
                    navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                    rewind: true,
                    navElement: 'div',

                    slideBy: 1,
                    dots: true,
                    dotsEach: false,
                    lazyLoad: false,
                    lazyContent: false,

                    autoplay: auto_play,
                    autoplayTimeout: slide_duration, // Need change to option
                    autoplayHoverPause: true,
                    smartSpeed: 250,
                    fluidSpeed: false,
                    autoplaySpeed: false,
                    navSpeed: false,
                    dotsSpeed: false,
                    dragEndSpeed: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        500: {
                            items: 2
                        },
                        991: {
                            items: columns
                        },
                        1200: {
                            items: columns
                        },
                        1300: {
                            items: columns
                        }
                    },
                    responsiveRefreshRate: 200,
                    responsiveBaseElement: window,
                    video: false,
                    videoHeight: false,
                    videoWidth: false,
                    animateOut: false,
                    animateIn: false,
                    fallbackEasing: 'swing',

                    info: false,

                    nestedItemSelector: false,
                    itemElement: 'div',
                    stageElement: 'div',

                    navContainer: false,
                    dotsContainer: false
                };
                
                element.find('.products-content.slider .products').owlCarousel( _slider_data );
            }
        },
        productCountdown: function(element, atts) {
            $('.countdown-time').each(function(){
                var days_text = $(this).attr('data-days-text');
                var hours_text = $(this).attr('data-hours-text');
                var minutes_text = $(this).attr('data-minutes-text');
                var seconds_text = $(this).attr('data-seconds-text');
                var sale_from = $(this).attr('data-sale-from');
                var sale_to = $(this).attr('data-sale-to');

                $(this).countdown(sale_to, function(event) {
                    $(this).html(
                        event.strftime(
                            '<ul class="list-time">' +
                                '<li class="cd-days"><p class="countdown-number">%D</p> <p class="countdown-text">' + days_text + '</p></li>' +
                                '<li class="cd-hours"><p class="countdown-number">%H</p><p class="countdown-text">' + hours_text + '</p></li>' + 
                                '<li class="cd-minutes"><p class="countdown-number">%M</p><p class="countdown-text">' + minutes_text + '</p></li>' + 
                                '<li  class="cd-seconds"> <p class="countdown-number">%S</p><p class="countdown-text">' + seconds_text + '</p></li>' +
                            '</ul>'
                        )
                    );
                });
            });
        },
        // Ajax Order
        productAjaxOrder: function () {
            $('.haru-products-ajax-order').each(function(){
                var $this = $(this);

                $this.find('.products-tabs .tab-item').on('click', function() {
                    if ( $(this).hasClass('current') || $(this).parents('.haru-products-ajax-order').find('.products-content').hasClass('loading') ){
                        return;
                    }
                    var element       = $(this).parents('.haru-products-ajax-order');
                    var element_id    = element.attr('id');
                    var atts          = element.data('atts');
                    var product_order = $(this).data('product_order');
                    
                    element.find('.products-tabs .tab-item').removeClass('current');
                    $(this).addClass('current');
                    
                    /* Check cache */
                    var tab_data_index = element_id + '-' + product_order.toString().split(',').join('-');
                    if ( products_ajax_order_data[tab_data_index] != undefined ){
                        element.find('.products-content .products').remove();
                        element.find('.products-content').append( products_ajax_order_data[tab_data_index] );

                        // Generate Isotope Grid
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_order_isotope( element );

                        // Generate Products Slider
                        HARUSHOPMAIN.WooCommerce.haru_product_ajax_order_slider( element, atts.auto_play, atts.columns, atts.slide_duration );
                        
                        // Recall quickView
                        HARUSHOPMAIN.WooCommerce.quickView();
                        
                        return;
                    }
                    
                    element.find('.products-content').addClass('loading');

                    $.ajax({
                        type : "POST",
                        timeout : 30000,
                        url : haru_framework_ajax_url,
                        data : {
                            action: 'haru_get_product_content_in_order_tab', 
                            atts: atts, 
                            product_order: product_order
                        },
                        error: function(xhr,err) {
                            
                        },
                        success: function(response) {
                            if ( response ) {       
                                element.find('.products-content .products').remove();
                                element.find('.products-content').append( response ).find('li.product-type').css('opacity',0).animate({opacity: 1},500);

                                /* Save cache */
                                products_ajax_order_data[tab_data_index] = response;

                                /* Generate isotope */
                                if ( $this.hasClass('grid') ) {
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_order_isotope( element );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                }
                                /* Generate slider */
                                if ( $this.hasClass('slider') ) { 
                                    HARUSHOPMAIN.WooCommerce.haru_product_ajax_order_slider( element, atts.auto_play, atts.columns, atts.slide_duration );
                                    HARUSHOPMAIN.WooCommerce.quickView();
                                }
                            }
                            element.find('.products-content').removeClass('loading');
                        }
                    });
                });

                // Click first tab when page load
                $('.haru-products-ajax-order').each(function() {
                    $(this).find('.products-tabs .tab-item:first').trigger('click');
                });
            });
        },
        haru_product_ajax_order_isotope: function(element) {
            setTimeout(function(){
                element.imagesLoaded( function() {
                    element.find('.products-content.grid .products').isotope({
                        itemSelector : 'li.type-product',
                        layoutMode: 'fitRows'
                    });
                });
            }, 100);
        },
        haru_product_ajax_order_slider: function( element, auto_play, columns, slide_duration ) {
            if( element.find('.products-content li.type-product').length > 0 ) {
                var items_mobile    = 2;
                var items_tablet    = 3;
                auto_play           = (auto_play == 1) ? true : false;
                columns             = parseInt(columns);
                slide_duration      = parseInt(slide_duration);
                var _slider_options = { 
                    items : columns,
                    margin: 20,
                    loop: true,
                    center: false,
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    freeDrag: false,
                    stagePadding: 0,
                    merge: false,
                    mergeFit: true,
                    autoWidth: false,
                    startPosition: 0,
                    URLhashListener: false,
                    nav: true,
                    navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                    rewind: true,
                    navElement: 'div',
                    slideBy: 1,
                    dots: true,
                    dotsEach: false,
                    lazyLoad: false,
                    lazyContent: false,

                    autoplay: auto_play, // autoplay
                    autoplayTimeout: slide_duration,
                    autoplayHoverPause: true,
                    
                    smartSpeed: 250,
                    fluidSpeed: false,
                    autoplaySpeed: false,
                    navSpeed: false,
                    dotsSpeed: false,
                    dragEndSpeed: false,
                    responsive: {
                        0: {
                            items: (columns < items_mobile) ? columns : items_mobile
                        },
                        500: {
                            items: (columns < items_mobile) ? columns : items_mobile
                        },
                        768: {
                            items: items_tablet
                        },
                        991: {
                            items: columns
                        },
                        1200: {
                            items: columns
                        },
                        1300: {
                            items: columns
                        }
                    },
                    responsiveRefreshRate: 200,
                    responsiveBaseElement: window,
                    video: false,
                    videoHeight: false,
                    videoWidth: false,
                    animateOut: false,
                    animateIn: false,
                    fallbackEasing: 'swing',

                    info: false,

                    nestedItemSelector: false,
                    itemElement: 'div',
                    stageElement: 'div',

                    navContainer: false,
                    dotsContainer: false
                };
                
                element.find('.products-content.slider .products').owlCarousel( _slider_options );
            }
        },
    }

    // Document ready
    HARUSHOPMAIN.onReady = {
        init: function () {
            HARUSHOPMAIN.WooCommerce.init();

        }
    };

    // Window resize
    HARUSHOPMAIN.onResize = {
    	init: function() {
            
    	}
    }

    // Window onLoad
    HARUSHOPMAIN.onLoad = {
    	init: function() {

    	}
    }

    // Window onScroll
    HARUSHOPMAIN.onScroll = {
    	init: function() {
    		
    	}
    }
    $(window).resize(HARUSHOPMAIN.onResize.init);
    $(window).scroll(HARUSHOPMAIN.onScroll.init);
    $(document).ready(HARUSHOPMAIN.onReady.init);
    $(window).load(HARUSHOPMAIN.onLoad.init); 

})(jQuery);