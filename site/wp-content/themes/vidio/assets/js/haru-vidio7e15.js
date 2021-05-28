/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

(function ($) {
    "use strict";
    var HaruVidio = {
        init: function() {
            HaruVidio.base.init();
            HaruVidio.teammember.init();
        }
    };
    
    HaruVidio.base = {
        init: function() {
            HaruVidio.base.bannerCreative();
            HaruVidio.base.shortcodeImagesGallery();
            HaruVidio.base.shortcodeCounter();
            HaruVidio.base.shortcodeVideo();
            HaruVidio.base.shortcodeTestimonial();
            HaruVidio.base.contactUs();
        },
        bannerCreative: function() {
            setTimeout(function(){
                $('.banner-creative-shortcode-wrap').each(function(){
                    var $this = $(this);
                    var $container      = $(this).find('.banner-list'); // parent element of .item
                    var masonry_options = {
                        'gutter' : 0
                    };
                    var $columns = parseInt($this.attr('data-columns'));
                    var $padding = $this.attr('data-padding');

                    $container.isotope({
                        itemSelector : '.banner-item', // .item
                        transitionDuration : '0.4s',
                        masonry : masonry_options,
                        layoutMode : 'packery'
                    });

                    imagesLoaded($this,function(){
                        $container.isotope('layout');
                    });

                    HaruVidio.base.packeryPadding($this, $padding, $columns);

                    $(window).resize(function(){
                        setTimeout(function(){
                            $container.isotope('layout');
                            HaruVidio.base.packeryPadding($this, $padding, $columns);
                        }, 300);
                    });
                });
            }, 300);
        },
        packeryPadding: function(element, $padding, $columns) {
            if( element.hasClass('packery') || element.hasClass('packery_2') && (typeof $padding !== 'undefined') && ($padding != 0) && ($(window).width() > 767) ) { // Use padding
                var banner_wrapper_width = $(element).find('.banner-list').width();
                var padding_total = Number($columns) * Number($padding) * 2; // Column from banner-packery.php file
                // console.log(banner_wrapper_width);
                // console.log(padding_total);

                var banner_item_height = (banner_wrapper_width - padding_total) / $columns;
                console.log(banner_item_height);

                // Default
                // $(element).find('.banner-item.default').each(function() {
                //     $(this).css({"height": Number(banner_item_height)});
                //     $('img',this).css({"height": Number(banner_item_height)});
                // });
                // Small squared

                // Landscape
                $(element).find('.banner-item.landscape').each(function() {
                    $(this).css({"height": banner_item_height});
                    $('img',this).css({"height": banner_item_height});
                });
                // Portrait
                $(element).find('.banner-item.portrait').each(function() {
                    $(this).css({"height": (Number(banner_item_height) + Number($padding)) * 2 });
                    $('img',this).css({"height": (Number(banner_item_height) + Number($padding)) * 2 });
                });
                // Big Squared
                $(element).find('.banner-item.big_squared').each(function() {
                    $(this).css({"height": (Number(banner_item_height) + Number($padding)) * 2 });
                    $('img',this).css({"height": (Number(banner_item_height) + Number($padding)) * 2 });
                });
                // Special
                $(element).find('.banner-item.special').each(function() {
                    $(this).css({"height": (Number(banner_item_height) + Number($padding)) * 2 });
                    $('img',this).css({"height": (Number(banner_item_height) - 1 + Number($padding)) * 2 }); // Need -2 for border
                });
                // Style 3
                $(element).find('.banner-item.style_3').each(function() {
                    $(this).css({"height": Number(banner_item_height)});
                    $('img',this).css({"height": (Number(banner_item_height) - 2 ) });
                });
                // Style 5
                $(element).find('.banner-item.style_5').each(function() {
                    $(this).css({"height": (Number(banner_item_height) - 10 + Number($padding)) });
                    $('img',this).css({"height": (Number(banner_item_height) - 18 + Number($padding)) }); // Need -2 for border
                });
                
            }
        },
        shortcodeImagesGallery: function() {
            $('.images-gallery-shortcode-wrap').each(function(){
                var $this = $(this);

                if ( $this.hasClass('grid') ) {
                    var $container      = $(this).find('.images-list'); // parent element of .item
                    var masonry_options = {
                        'gutter' : 0
                    };

                    $container.isotope({
                        itemSelector : '.image-item', // .item
                        transitionDuration : '0.4s',
                        masonry : masonry_options,
                        layoutMode : 'packery'
                    });

                    imagesLoaded($this,function(){
                        $container.isotope('layout');
                    });

                    $this.find('.image-actions').magnificPopup({
                        delegate: 'a.image-gallery-popup',
                        gallery: {
                            enabled: true
                        },
                        type: 'image'
                        // other options
                    });
                }
                // Slick
                if ($this.hasClass('slick')) {
                    $this.find('.slider-for').on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
                        // currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                        var i = (currentSlide ? currentSlide : 0) + 1;
                        $this.find('.slide-count-wrap').text(i + '/' + slick.slideCount);
                    });
                    $this.find('.slider-for').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        centerMode: true,
                        centerPadding: '60px',
                        variableWidth: true,
                        arrows: true,
                        fade: false,
                        dotsClass: 'custom_paging',
                        responsive: [{
                                breakpoint: 1199,
                                settings: {
                                    variableWidth: true
                                }
                            },
                            {
                                breakpoint: 991,
                                settings: {
                                    variableWidth: true
                                }
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    variableWidth: true
                                }
                            }
                        ]
                    });
                }
            });
        },
        shortcodeCounter: function() {
            $('.counter-shortcode-wrap').each(function(){
                var $this = $(this);

                // Appear
                if (!$(".gr-animated").length) return;

                $(".gr-animated").appear();

                $(document.body).on("appear", ".gr-animated", function () {
                    $(this).addClass("go");
                });

                $(document.body).on("disappear", ".gr-animated", function () {
                    $(this).removeClass("go");
                });

                // Counter
                if (!$(".gr-number-counter").length) return;
                $(".gr-number-counter").appear(); // require jquery-appear

                $('body').on("appear", ".gr-number-counter", function () {
                    var counter = $(this);
                    if (!counter.hasClass("count-complete")) {
                        counter.countTo({
                            speed: 1500,
                            refreshInterval: 100,
                            onComplete: function () {
                                counter.addClass("count-complete");
                            }
                        });
                    }
                });
                
                $('body').on("disappear", ".gr-number-counter", function () {
                    $(this).removeClass("count-complete");
                });
            });
        },
        shortcodeVideo: function() {
            $('.video-popup-link').magnificPopup({
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
        shortcodeTestimonial: function() {
            
        },
        contactUs: function() {
            $('.our-team-btn a').click(function() {
                var speed = 1000,
                    href = $(this).attr("href"),
                    target = $(href == "#" || href == "" ? 'html' : href),
                    position = target.offset().top - 60;

                $('body').animate({ scrollTop: position }, speed, 'swing');
                $('html').animate({ scrollTop: position }, speed, 'swing');

                history.pushState('', document.title, window.location.pathname + window.location.search);

                return false;
            });
        }
    };

    HaruVidio.teammember = {
        init: function() {
            HaruVidio.teammember.shortcodeTeammember();
            HaruVidio.teammember.teammemberLoadMore();
            HaruVidio.teammember.teammemberInfiniteScroll();
        },
        shortcodeTeammember: function() {
            var default_filter = [];
            var array_filter = []; // Push filter to an array to process when don't have filter

            $('.teammember-shortcode-wrap.grid').each(function(index, value) {
                // Process filter each shortcode
                $(this).find('.teammember-filter li').first().find('a').addClass('selected');
                default_filter[index] = $(this).find('.teammember-filter li').first().find('a').attr('data-option-value');

                var self = $(this);
                var $container = $(this).find('.teammember-list'); // parent element of .item
                var $filter = $(this).find('.teammember-filter a');
                var masonry_options = {
                    'gutter': 0
                };

                array_filter[index] = $filter;

                // Add to process members layout style
                var layoutMode = 'fitRows';
                if (($(this).hasClass('masonry'))) {
                    var layoutMode = 'masonry';
                }

                for (var i = 0; i < array_filter.length; i++) {
                    if (array_filter[i].length == 0) {
                        default_filter = '';
                    }
                    $container.isotope({
                        itemSelector: '.team-item', // .item
                        transitionDuration: '0.4s',
                        masonry: masonry_options,
                        layoutMode: layoutMode,
                        filter: default_filter[i]
                    });
                }

                imagesLoaded(self, function() {
                    $container.isotope('layout');
                });

                $(window).resize(function() {
                    $container.isotope('layout');
                });

                $filter.on('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    var $this = $(this);
                    // Don't proceed if already selected
                    if ($this.hasClass('selected')) {
                        return false;
                    }
                    var filters = $this.closest('ul');
                    filters.find('.selected').removeClass('selected');
                    $this.addClass('selected');

                    var options = {
                            layoutMode: layoutMode,
                            transitionDuration: '0.4s',
                            packery: {
                                horizontal: true
                            },
                            masonry: masonry_options
                        },
                        key = filters.attr('data-option-key'),
                        value = $this.attr('data-option-value');
                    value = value === 'false' ? false : value;
                    options[key] = value;

                    $container.isotope(options);
                });
            });
        },
        teammemberLoadMore: function() {
            $('.teammember-shortcode-paging-wrapper .teammember-load-more').off().on('click', function(event) {
                event.preventDefault();

                var $this = $(this).button('loading');
                var link = $(this).attr('data-href');
                var shortcode_wrapper = '.teammember-shortcode-wrap';
                var contentWrapper = '.teammember-shortcode-wrap .teammember-list'; // parent element of .item
                var element = '.team-item'; // .item

                $.get(link, function(data) {
                    var next_href = $('.teammember-load-more', data).attr('data-href');
                    var $newElems = $(element, data).css({
                        opacity: 0
                    });

                    $(contentWrapper).append($newElems);
                    $newElems.imagesLoaded(function() {
                        $newElems.animate({
                            opacity: 1
                        });

                        $(contentWrapper).isotope('appended', $newElems);
                        setTimeout(function() {
                            $(contentWrapper).isotope('layout');
                        }, 400);

                        HaruVidio.teammember.init();
                    });


                    if (typeof(next_href) == 'undefined') {
                        $this.parent().remove();
                    } else {
                        $this.button('reset');
                        $this.attr('data-href', next_href);
                    }

                });
            });
        },
        teammemberInfiniteScroll: function() {
            var shortcode_wrapper = '.teammember-shortcode-wrap.grid';
            var contentWrapper = '.teammember-shortcode-wrap.grid .teammember-list'; // parent element of .item
            $('.teammember-list', shortcode_wrapper).infinitescroll({
                navSelector: "#infinite_scroll_button",
                nextSelector: "#infinite_scroll_button a",
                itemSelector: ".team-item", // .item
                loading: {
                    'selector': '#infinite_scroll_loading',
                    'img': haru_framework_theme_url + '/assets/images/ajax-loader.gif',
                    'msgText': 'Loading...',
                    'finishedMsg': ''
                }
            }, function(newElements, data, url) {
                var $newElems = $(newElements).css({
                    opacity: 0
                });
                $newElems.imagesLoaded(function() {
                    $newElems.animate({
                        opacity: 1
                    });

                    $(contentWrapper).isotope('appended', $newElems);
                    setTimeout(function() {
                        $(contentWrapper).isotope('layout');
                    }, 400);

                    HaruVidio.teammember.init();
                });

            });
        }
    };

    $(document).ready(function() {
        HaruVidio.init();
    });
})(jQuery);