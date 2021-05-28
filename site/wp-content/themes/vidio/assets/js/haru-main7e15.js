/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

var HARU = HARU || {};
(function ($) {
    "use strict";

    var $window = $(window),
        deviceAgent = navigator.userAgent.toLowerCase(),
        isMobile    = deviceAgent.match(/(iphone|ipod|android|iemobile)/),
        isMobileAlt = deviceAgent.match(/(iphone|ipod|ipad|android|iemobile)/),
        $body       = $('body');

    // Base functions
    HARU.base = {
        init: function() {
            HARU.base.haruCarousel();
            HARU.base.prettyPhoto(); // @TODO: new lightbox (outdate)
            HARU.base.stellar();
            HARU.base.newsletterPopup();
            HARU.base.instagramCarousel();
        },
        haruCarousel: function() {
            $('.haru-carousel.owl-carousel').each(function(index, value){
                var $self          = $(this);
                var items          = parseInt($(this).attr('data-items'));
                var items_desktop  = parseInt($(this).attr('data-items-desktop')) ? parseInt($(this).attr('data-items-desktop')) : items;
                var items_tablet   = parseInt($(this).attr('data-items-tablet'));
                var items_mobile   = parseInt($(this).attr('data-items-mobile'));
                var margin         = parseInt($(this).attr('data-margin'));
                var autoplay       = $(this).attr('data-autoplay') == 'true' ? true : false;
                var loop           = $(this).attr('data-loop') == 'true' ? true : false;
                var counter       = $(this).attr('data-counter') == 'true' ? true : false;
                var slide_duration = parseInt($(this).attr('data-slide-duration'));

                setTimeout(function(){ // VC Stretch row
                    $self.on('initialized.owl.carousel changed.owl.carousel', function(e) {
                        if (!e.namespace)  {
                          return;
                        }
                        var carousel = e.relatedTarget;
                        if ( counter ) {
                            $('.slider-counter', $self.parent()).text(carousel.relative(carousel.current()) + 1 + '/' + carousel.items().length);
                        }
                    }).owlCarousel({
                        items : items,
                        margin: margin,
                        loop: loop,
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
                        navText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
                        rewind: true,
                        navElement: 'div',
                        slideBy: 1,
                        dots: true,
                        dotsEach: false,
                        lazyLoad: false,
                        lazyContent: false,

                        autoplay: autoplay, // autoplay
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
                                items: (items < items_mobile) ? items : items_mobile
                            },
                            500: {
                                items: (items < items_mobile) ? items : items_mobile
                            },
                            768: {
                                items: items_tablet
                            },
                            991: {
                                items: items_desktop
                            },
                            1200: {
                                items: items
                            },
                            1300: {
                                items: items
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
                    });
                }, 10);
            });
        },
        prettyPhoto: function() {
            $("a[data-rel^='prettyPhoto']").prettyPhoto({
                hook:'data-rel',
                social_tools:'',
                animation_speed:'normal',
                theme:'light_square'
            });
        },
        stellar: function() {
            $.stellar({
                horizontalScrolling: false,
                scrollProperty: 'scroll',
                positionProperty: 'position'
            });
        },
        newsletterPopup: function() {
            // Reference: http://stackoverflow.com/questions/1458724/how-to-set-unset-cookie-with-jquery
            var et_popup_closed = $.cookie('haru_popup_closed');
            var popup_effect    = $('.haru-popup').data('effect');
            var popup_delay     = $('.haru-popup').data('delay');

            setTimeout(function() {
                $('.haru-popup').magnificPopup({
                    items: {
                      src: '#haru-popup',
                      type: 'inline'
                    },
                    removalDelay: 500, //delay removal by X to allow out-animation
                    callbacks: {
                        beforeOpen: function() {
                            this.st.mainClass = popup_effect;
                        },
                        beforeClose: function() {
                        if($('#showagain:checked').val() == 'do-not-show')
                            $.cookie('haru_popup_closed', 'do-not-show', { expires: 1, path: '/' } );
                        },
                    }
                    // (optionally) other options
                });

                if(et_popup_closed != 'do-not-show' && $('.haru-popup').length > 0 && $('body').hasClass('open-popup')) {
                    $('.haru-popup').magnificPopup('open');
                }  
            }, popup_delay);
        },
        instagramCarousel: function() {
            $('.null-instagram-feed.slick').each(function(index, value){
                $(this).find('.instagram-pics').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 4,
                    centerMode: true,
                    centerPadding: '10%',
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 479,
                            settings: {
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: 2
                            }
                        }
                    ]
                });
            });
        },
        isDesktop: function () {
            var responsive_breakpoint = 991;

            return window.matchMedia('(min-width: ' + (responsive_breakpoint + 1) + 'px)').matches;
        }
    }

    // Blog functions
    HARU.blog = {
        init: function() {
            HARU.blog.jPlayerSetup();
            HARU.blog.loadMore();
            HARU.blog.infiniteScroll();
            HARU.blog.gridLayout();
            HARU.blog.masonryLayout();
        },
        windowResized: function() {
            HARU.blog.processWidthAudioPlayer();
        },  
        jPlayerSetup: function() {
            $('.jp-jplayer').each(function () {
                var $this = $(this),
                    url            = $this.data('audio'),
                    title          = $this.data('title'),
                    type           = url.substr(url.lastIndexOf('.') + 1),
                    player         = '#' + $this.data('player'),
                    audio          = {};
                    audio[type]    = url;
                    audio['title'] = title;
                    $this.jPlayer({
                        ready: function () {
                            $this.jPlayer('setMedia', audio);
                    },
                    swfPath: '../libraries/jPlayer',
                    cssSelectorAncestor: player
                });
            });
            HARU.blog.processWidthAudioPlayer();
        },
        processWidthAudioPlayer: function() {
            setTimeout(function () {
                $('.jp-audio .jp-type-playlist').each(function () {
                    var _width = $(this).outerWidth() - $('.jp-play-pause', this).outerWidth() - parseInt($('.jp-play-pause', this).css('margin-left').replace('px',''),10) - parseInt($('.jp-progress', this).css('margin-left').replace('px',''),10) - $('.jp-volume', this).outerWidth() - parseInt($('.jp-volume', this).css('margin-left').replace('px',''),10) - 15;
                    $('.jp-progress', this).width(_width);
                });
            }, 100);
        },
        loadMore: function() {
            $('.blog-load-more').on('click', function (event) {
                event.preventDefault();
                var $this          = $(this).button('loading');
                var link           = $(this).attr('data-href');
                var contentWrapper = '.archive-content-layout .row';
                var element        = '.archive-content-layout article';

                $.get(link, function (data) {
                    var next_href = $('.blog-load-more', data).attr('data-href');
                    var $newElems = $(element, data).css({
                        opacity: 0
                    });

                    $(contentWrapper).append($newElems);
                    $newElems.imagesLoaded(function () {
                        HARU.base.haruCarousel(); // Maybe don't need
                        HARU.blog.jPlayerSetup();
                        HARU.base.prettyPhoto();
                        $newElems.animate({
                            opacity: 1
                        });

                        // Process masonry/grid blog layout
                        if( ($(contentWrapper).parent().hasClass('layout-style-masonry')) || ($(contentWrapper).parent().hasClass('layout-style-grid')) ) {
                            $(contentWrapper).isotope('appended', $newElems);
                            setTimeout(function() {
                                $(contentWrapper).isotope('layout');
                            }, 400);
                        }

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
        infiniteScroll: function() {
            var contentWrapper = '.archive-content-layout .row';

            if ( $(contentWrapper).length ) {
                $(contentWrapper).infinitescroll({
                    navSelector: "#infinite_scroll_button",
                    nextSelector: "#infinite_scroll_button a",
                    itemSelector: ".archive-content-layout article",
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
                        HARU.base.haruCarousel(); // Maybe don't need
                        HARU.blog.jPlayerSetup();
                        HARU.base.prettyPhoto();
                        $newElems.animate({
                            opacity: 1
                        });

                        // Process masonry/grid blog layout
                        if (($(contentWrapper).parent().hasClass('layout-style-masonry'))  || ($(contentWrapper).parent().hasClass('layout-style-grid'))) {
                            $(contentWrapper).isotope('appended', $newElems);
                            setTimeout(function() {
                                $(contentWrapper).isotope('layout');
                            }, 400);
                        }
                    });
                });
            }
        },
        gridLayout: function() {
            var $blog_grid = $('.layout-style-grid .row');

            if ( $blog_grid.length ) {
                $blog_grid.imagesLoaded( function() {
                    $blog_grid.isotope({
                        itemSelector : '.layout-style-grid article',
                        layoutMode: "fitRows"
                    });
                    setTimeout(function () {
                        $blog_grid.isotope('layout');
                    }, 500);
                });
            }
        },
        masonryLayout : function() {
            var $blog_masonry = $('.layout-style-masonry .row');

            if ( $blog_masonry.length ) {
                $blog_masonry.imagesLoaded( function() {
                    $blog_masonry.isotope({
                        itemSelector : '.layout-style-masonry article',
                        layoutMode: "masonry"
                    });

                    setTimeout(function () {
                        $blog_masonry.isotope('layout');
                    }, 500);
                });
            }
        }
    }

    // Page functions
    HARU.page = {
        init: function() {
            HARU.page.backToTop();
            HARU.page.overlayVisualComposer();
            HARU.page.onepage();
            HARU.page.stickySidebar();
        },
        windowLoad: function() {
            if ($body.hasClass('haru-site-preloader')) {
                HARU.page.pageIn();
            }
        },
        pageIn: function() {
            setTimeout(function() {
                $('#haru-site-preloader').fadeOut(300);
            }, 300);
        },
        backToTop: function() {
            var $backToTop = $('.back-to-top');
            if ( $backToTop.length > 0 ) {
                $backToTop.on('click', function(event) {
                    event.preventDefault();
                    $('html,body').animate({
                        scrollTop: '0px'
                    }, 800);
                });
                $window.on('scroll', function (event) {
                    var scrollPosition = $window.scrollTop();
                    var windowHeight = $window.height() / 2;
                    if (scrollPosition > windowHeight) {
                        $backToTop.addClass('in');
                    }
                    else {
                        $backToTop.removeClass('in');
                    }
                });
            }
        },
        overlayVisualComposer : function() {
            $('[data-overlay-color]').each(function() {
                var $selector = $(this);
                setTimeout(function() {
                    var overlay_color = $selector.data('overlay-color');
                    var html = '<div class="overlay-bg-vc" style="background-color: '+ overlay_color +'"></div>';
                    $selector.prepend(html);
                }, 100);
            });
        },
        onepage: function() {
            if ( $('body').hasClass('onepage') ) {
                if ( $(window).width() > 991 ) {
                    var onepage_sections = [];
                    var anchors = []
                    $('.vc_row.section').each(function(){
                        var row_id = $(this).attr('id');
                        onepage_sections.push(row_id);
                    });
                    for (var i = 0; i < onepage_sections.length; i++) {
                        var section = String(i);
                        anchors.push(section);
                    }
                    console.log(anchors);
                    $('#haru-content-main .entry-content').fullpage({
                        // Navigation
                        navigation: true,
                        navigationPosition: 'right',
                        anchors: anchors,
                        //options here
                        scrollingSpeed: 1000,
                        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
                        scrollOverflow: true,
                        lockAnchors: true,
                    });
                }
            }
        },
        stickySidebar: function() {
            if ( $('.sticky-sidebar').length > 0 ) {
                if ( $(window).width() > 767 ) {
                    var stickySidebar = new StickySidebar('#sticky-sidebar', {
                        topSpacing: 100,
                        bottomSpacing: 0,
                        containerSelector: '.sticky-sidebar',
                        innerWrapperSelector: '#sticky-sidebar .vc_column-inner'
                    });
                }
            }
        }
    }

    // Header functions
    HARU.header = {
        init: function () {
            HARU.header.stickyMenu();
            HARU.header.stickyMobileMenu();
            HARU.header.menuMobile();
            HARU.header.canvasSidebar(); // Canvas Sidebar
            HARU.header.cartSidebar(); // Cart Sidebar
            HARU.header.verticalMenu();
            HARU.header.loginPopup();
            HARU.header.menuPopup();
            HARU.header.searchButton(); // Search button popup
            HARU.header.searchBox(); // Search box ajax
            HARU.header.searchProductCategory(); // Search product with category
        },
        windowResized : function(){
            if (HARU.base.isDesktop()) {
                $('.toggle-icon-wrap[data-drop]').removeClass('in');
            }
            var $adminBar = $('#wpadminbar');

            if ($adminBar.length > 0) {
                $body.attr('data-offset', $adminBar.outerHeight() + 1);
            }
            if ($adminBar.length > 0) {
                $body.attr('data-offset', $adminBar.outerHeight() + 1);
            }
            
            HARU.header.stickyMenu();
            HARU.header.stickyMobileMenu();
            HARU.header.menuMobileFly();
            HARU.header.menuMobileDropdown();
        },
        windowLoad: function() {
            HARU.header.menuMobileDropdown();
            HARU.header.menuMobileFly();
        },
        stickyMenu : function() {
            if( $('#haru-header').hasClass('header-sticky') ) {
                var header = $('#haru-header'),
                    topHeader = $('.haru-top-header'),
                    topHeaderHeight = $('.haru-top-header').length > 0 ? $('.haru-top-header').outerHeight() : 0,
                    headerNavAbove = $('.haru-header-nav-above-wrap'),
                    headerNavAboveHeight = $('.haru-header-nav-above-wrap').length > 0 ? $('.haru-header-nav-above-wrap').outerHeight() : 0,
                    headerNav = $('.haru-header-nav-wrap'),
                    headerNavHeight = $('.haru-header-nav-wrap').length > 0 ? $('.haru-header-nav-wrap').outerHeight() : 0,
                    adminBar = $('.admin-bar').length > 0 ? $('#wpadminbar').height() : 0,
                    headerNavStickyHeight = 60,
                    headerNavToTop = topHeaderHeight + headerNavAboveHeight,
                    headerHeight = headerNavAboveHeight + headerNavHeight;

                if( headerNavHeight >  headerNavStickyHeight) {
                    headerNavToTop = headerNavToTop + (headerNavHeight - headerNavStickyHeight);
                }

                header.height(headerHeight + 'px');

                $(window).on('scroll', function() {
                    if ($(this).scrollTop() > headerNavToTop) {
                        headerNav.addClass('nav-sticky');
                        header.addClass('sticky');
                    } else {
                        headerNav.removeClass('nav-sticky');
                        header.removeClass('sticky');
                    }
                });
            }
        },
        stickyMobileMenu : function() {
            if ( $('#haru-mobile-header').hasClass('header-mobile-sticky') ) {
                var mobileHeader = $('#haru-mobile-header'),
                    topHeader = $('.haru-top-header'),
                    topHeaderHeight = $('.haru-top-header').length > 0 ? $('.haru-top-header').outerHeight() : 0,
                    mobileHeaderNavAbove = $('.header-mobile-above'),
                    mobileHeaderNavAboveHeight = $('.header-mobile-above').length > 0 ? $('.header-mobile-above').outerHeight() : 0,
                    mobileHeaderNav = $('.haru-mobile-header-wrap'),
                    mobileHeaderNavHeight = $('.haru-mobile-header-wrap').length > 0 ? $('.haru-mobile-header-wrap').outerHeight() : 0,
                    adminBar = $('.admin-bar').length > 0 ? $('#wpadminbar').height() : 0,
                    mobileHeaderNavToTop = topHeaderHeight + mobileHeaderNavAboveHeight + adminBar,
                    mobileHeaderHeight = mobileHeaderNavAboveHeight + mobileHeaderNavHeight;

                mobileHeader.height(mobileHeaderHeight + 'px');

                $(window).on('scroll', function() {
                    if ($(this).scrollTop() > mobileHeaderNavToTop) {
                        mobileHeaderNav.addClass('nav-sticky');
                        mobileHeader.addClass('sticky');
                    } else {
                        mobileHeaderNav.removeClass('nav-sticky');
                        mobileHeader.removeClass('sticky');
                    }
                    
                    // Add class for style fly menu
                    if ( adminBar > 0 ) {
                        if ($(this).scrollTop() > 46) {
                            $('.menu-mobile-fly').css('top', 0);
                        } else {
                            $('.menu-mobile-fly').css('top', 46 - $(this).scrollTop());
                        }
                    }
                });
            }
        },
        menuMobile : function() {
            $('.toggle-mobile-menu[data-ref]').on('click', function(event) {
                event.preventDefault();

                var $this     = $(this);
                var data_drop = $this.data('ref');
                $this.toggleClass('in');
                switch ($this.data('drop-type')) {
                    case 'dropdown':
                        $('#' + data_drop).slideToggle();
                        break;
                    case 'fly':
                        $('body').toggleClass('menu-mobile-in');
                        $('#' + data_drop).toggleClass('in');
                        break;
                }
            });

            $('.toggle-icon-wrap[data-ref]:not(.toggle-mobile-menu)').on('click', function(event) {
                event.preventDefault();

                var $this    = $(this);
                var data_ref = $this.data('ref');
                $this.toggleClass('in');
                $('#' + data_ref).toggleClass('in');
            });

            $('.haru-mobile-menu-overlay, .mobile-menu-close').on('click', function() {
                $body.removeClass('menu-mobile-in');

                $('#haru-nav-mobile-menu').removeClass('in');
                $('.toggle-icon-wrap[data-ref]').removeClass('in');
            });
        },
        menuMobileDropdown: function() {
            var top = 0;
            if (($('#wpadminbar').length > 0) && ($('#wpadminbar').css('position') == 'fixed')) {
                top = $('#wpadminbar').outerHeight();
            }
            if (top > 0) {
                $('.haru-mobile-header-nav.menu-mobile-fly').css('top',top + 'px');
            } else {
                $('.haru-mobile-header-nav.menu-mobile-fly').css('top','');
            }
        },
        menuMobileFly: function() {
            var top = 0;

            if (($('#wpadminbar').length > 0) && ($('#wpadminbar').css('position') == 'fixed')) {
                top = $('#wpadminbar').outerHeight();
            }
            if (top > 0) {
                $('.haru-mobile-header-nav.menu-mobile-fly').css('top',top + 'px');
            } else {
                $('.haru-mobile-header-nav.menu-mobile-fly').css('top','');
            }
        },
        cartSidebar: function () {
            $('.cart-mask-overlay').on('click', function(event) {
                $('.cart_list_wrap').removeClass('in');
                $('.cart-mask-overlay').removeClass('in');
            });

            $('.widget_shopping_cart_icon').on('click', function (event) {
                event.preventDefault();
                $('.cart_list_wrap').toggleClass('in');
                $('.cart-mask-overlay').toggleClass('in');
            });
            $('.cart-sidebar-close').on('click', function (event) {
                event.preventDefault();
                $('.cart_list_wrap').removeClass('in');
                $('.cart-mask-overlay').removeClass('in');
            });
        },
        canvasSidebar: function () {
            $('.canvas-mask-overlay').on('click', function(event) {
                if (($(event.target).closest('.haru-canvas-sidebar-wrap').length == 0) && ($(event.target).closest('.canvas-sidebar-toggle')).length == 0) {
                    $('.haru-canvas-sidebar-wrap').removeClass('in');
                    $('.canvas-mask-overlay').removeClass('in');
                }
            });

            $('.canvas-sidebar-toggle').on('click', function (event) {
                event.preventDefault();
                $('.haru-canvas-sidebar-wrap').toggleClass('in');
                $('.canvas-mask-overlay').toggleClass('in');
            });
            $('.canvas-sidebar-close').on('click', function (event) {
                event.preventDefault();
                $('.haru-canvas-sidebar-wrap').removeClass('in');
                $('.canvas-mask-overlay').removeClass('in');
            });
        },
        verticalMenu: function () {
            // Close menu if not on homepage
            if ( !$('body').hasClass('home') ) {
                $(document).on('click', function(e) {
                    var container = $(".vertical-menu-wrap");
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        $('#vertical-menu-wrap').hide();
                    }
                });
            } else {
                $('#vertical-menu-wrap').show();
            }

            $('.vertical-menu-toggle').on('click', function (event) {
                event.preventDefault();
                $('#vertical-menu-wrap').slideToggle(300);
            });

            if ( $( '#vertical-menu-wrap' ).length > 0 ) {
                var all_item = 0;
                var items_show = $('#vertical-menu-wrap').data('items-show')-1;

                var all_item = $('#vertical-menu-wrap .vertical-megamenu>li').length;
                if ( all_item > (items_show + 1) ) {
                    $('#vertical-menu-wrap').addClass('show-view-all');
                }

                $('#vertical-menu-wrap').find('.vertical-megamenu>li').each(function(i) {
                    all_item = all_item + 1;
                  
                    if (i > items_show) {
                        $(this).addClass('menu-item-more');
                    }
                })
            }
            
            $(document).on('click', '.vertical-view-cate',function() {
                var $this = $(this);
                $(this).toggleClass('show-category');
                $(this).closest('.vertical-menu-wrap').find('li.menu-item-more').each(function() {
                    $(this).toggleClass('show');
                });
                var open_text = $(this).data('open-text');
                var close_text = $(this).data('close-text');
                if ( $this.hasClass('show-category') ) {
                    $this.html(close_text);
                } else {
                    $this.html(open_text);
                }
            });

        },
        loginPopup: function() {
            // Do something
        },
        menuPopup: function() {
            var popup_effect    = $('#popup-menu-button').data('effect');
            var popup_delay    = $('#popup-menu-button').data('delay');

            $('#popup-menu-button').magnificPopup({
                items: {
                  src: '#haru-menu-popup',
                  type: 'inline'
                },
                removalDelay: 500, //delay removal by X to allow out-animation
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = popup_effect;
                    },
                    beforeClose: function() {
                        // Do something
                    },
                }
                // (optionally) other options
            });
        },
        searchButton: function() {
            var popup_effect    = 'search-popup ZoomIn';

            $('.header-search-button').magnificPopup({
                items: {
                    src: '#haru-search-popup',
                    type: 'inline'
                },
                removalDelay: 500, // delay removal by X to allow out-animation
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = popup_effect;
                    },
                    open: function() {
                        // Clear search form and result
                        $('.ajax-search-result', '.haru-search-wrap').html('');
                        $('input[type="text"]', '.haru-search-wrap').val('');

                        HARU.header.searchButtonProcess();
                    },
                    beforeClose: function() {
                        // Do something
                    },
                }
                // (optionally) other options
            });
        },
        searchButtonProcess : function() {
            $('.haru-search-wrap').each(function() {
                var $this = $(this);

                if ( $('.search-popup-form', $this).data('search-type') == 'ajax' ) {
                    // Clear

                    // Doesn't allow submit form
                    $('.search-popup-form', $this).on('submit', function() {
                        return false;
                    });
                    // Process when typing
                    $('input[type="search"]', $this).on('keyup', function(event) {
                        var s_timeOut_search = null;

                        if (event.altKey || event.ctrlKey || event.shiftKey || event.metaKey) {
                            return;
                        }

                        // Process when select ajax result
                        var keys = ["Control", "Alt", "Shift"];
                        if (keys.indexOf(event.key) != -1) return;
                        switch (event.which) {
                            case 38:    // Press Up Key
                                HARU.header.process_search_up($this);
                                break;
                            case 40:    // Press Down Key
                                HARU.header.process_search_down($this);
                                break;
                            case 13:    // Press Enter Key
                                var $item = $('li.selected a', $this);
                                if ($item.length == 0) {
                                    event.preventDefault();
                                    return false;
                                }
                                HARU.header.process_search_enter($this);
                                break;
                            default:
                                clearTimeout(s_timeOut_search);
                                s_timeOut_search = setTimeout(function() {
                                    popup_seach($this);
                                }, 1000); // Can cause can't up/down select
                                break;
                        }
                    });

                    // Process keyword
                    function popup_seach($this) {
                        var keyword = $('input[type="search"]', $this).val();

                        if (keyword.length < 3) {
                            var hint_message = $this.attr('data-hint-message');

                            $('.ajax-search-result', $this).html('<ul><li class="no-result">' + hint_message + '</li></ul>');
                            return;
                        }
                        // Process icon-search
                        $('.icon-search', $this).addClass('fa-spinner fa fa-spin');
                        $('.icon-search', $this).removeClass('fa fa-search');
                        // Ajax result
                        $.ajax({
                            type   : 'POST',
                            data   : 'action=popup_search_result&keyword=' + keyword,
                            url    : haru_framework_ajax_url,
                            success: function (data) {
                                $('.icon-search', $this).removeClass('fa-spinner fa fa-spin');
                                $('.icon-search', $this).addClass('fa fa-search');
                                
                                if (data) {
                                    $('.ajax-search-result', $this).html(data);
                                    $('.ajax-search-result', $this).scrollTop(0);
                                }
                            },
                            error : function(data) {
                                $('.icon-search', $this).removeClass('fa-spinner fa fa-spin');
                                $('.icon-search', $this).addClass('fa fa-search');
                            }
                        });
                    }
                    // Process keyword up, down, enter
                } else {
                    return false; // Standard Search
                }
            });
        },
        searchBox: function() {
            $('.haru-search-box-wrap').each(function() {
                var $this = $(this);

                if ($('.search-box-form', $this).data('search-type') == 'ajax') {
                    // Clear or close all state when closed search
                    $(document).on('click', function(event) {
                        if ($(event.target).closest('.ajax-search-result', $this).length == 0) {
                            $('.ajax-search-result', $this).html('');
                            $('> input[type="text"]', $this).val('');
                        }
                    });
                    // Don't allow submit form
                    $('.search-box-form', $this).on('submit', function() {
                        return false;
                    });
                    // Process when typing
                    $('.search-box-form > input[type="text"]', $this).on('keyup', function(event) {
                        var s_timeOut_search = null;

                        if (event.altKey || event.ctrlKey || event.shiftKey || event.metaKey) {
                            return;
                        }

                        var keys = ["Control", "Alt", "Shift"];
                        if (keys.indexOf(event.key) != -1) return;
                        switch (event.which) {
                            case 27:    // Press ESC key
                                $('.ajax-search-result', $this).html('');
                                $(this).val('');
                                break;
                            case 38:    // Press UP key
                                HARU.header.process_search_up($this);
                                break;
                            case 40:    // Press DOWN key
                                HARU.header.process_search_up($this);
                                break;
                            case 13:    // Press ENTER key
                                HARU.header.process_search_enter($this);
                                break;
                            default:
                                clearTimeout(s_timeOut_search);
                                s_timeOut_search = setTimeout(function() {
                                    box_search($this);
                                }, 1000);
                                break;
                        }
                    });
                    // Process keyword
                    function box_search($this) {
                        var keyword = $('input[type="text"]', $this).val();

                        if (keyword.length < 3) {
                            var hint_message = $this.attr('data-hint-message');

                            $('.ajax-search-result', $this).html('<ul><li class="no-result">' + hint_message + '</li></ul>');
                            return;
                        }
                        // Process icon-search
                        $('button > i', $this).addClass('fa-spinner fa fa-spin');
                        $('button > i', $this).removeClass('fa fa-search');
                        // Ajax result
                        $.ajax({
                            type   : 'POST',
                            data   : 'action=popup_search_result&keyword=' + keyword,
                            url    : haru_framework_ajax_url,
                            success: function (data) {
                                $('button > i', $this).removeClass('fa-spinner fa fa-spin');
                                $('button > i', $this).addClass('fa fa-search');
                                
                                if (data) {
                                    $('.ajax-search-result', $this).html(data);
                                    $('.ajax-search-result', $this).scrollTop(0);
                                }
                            },
                            error : function(data) {
                                $('button > i', $this).removeClass('fa-spinner fa fa-spin');
                                $('button > i', $this).addClass('fa fa-search');
                            }
                        });
                    }
                    // Process keyword up, down, enter
                } else {
                    return false; // Standard Search
                }
            });
        },
        searchProductCategory: function() {
            $('.search-product-category').each(function() {
                var $productCategory = $('.select-category', this);
                var $this            = $(this);

                // Clear or close all state when closed search
                $(document).on('click', function(event) {
                    if ($(event.target).closest('.select-category', $this).length === 0) {
                        $(' > ul', $productCategory).slideUp(300);
                    }
                    if (($(event.target).closest('.ajax-search-result', $this).length === 0)) {
                        $('.ajax-search-result', $this).html('');
                        $('input', $this).val('');
                    }
                });

                var sHtml = '<li><span data-catid="-1" data-value="' + $('> span', $productCategory).text() + '">[' + $('> span', $productCategory).text() + ']</span></li>';
                $('> ul', $productCategory).prepend(sHtml);

                // Select Category
                $('> span', $productCategory).on('click', function() {
                    $('> ul', $(this).parent()).slideToggle(300);
                });

                // Category Click
                $('li > span', $productCategory).on('click', function() {
                    var $this = $(this);
                    var id = $this.attr('data-catid');
                    var text = '';
                    if (typeof ($this.attr('data-value')) != "undefined") {
                        text = $this.attr('data-value');
                    }
                    else {
                        text = $this.text();
                    }

                    var $cat_current = $('> span', $productCategory);
                    $cat_current.text(text);
                    $cat_current.attr('data-catid', id);
                    $(' > ul', $productCategory).slideUp(300);
                });

                // Process when typing
                $('input[type="text"]', $this).on('keyup', function(event) {
                    var s_timeOut_search = null;

                    if (event.altKey || event.ctrlKey || event.shiftKey || event.metaKey) {
                        return;
                    }

                    var keys = ["Control", "Alt", "Shift"];
                    if (keys.indexOf(event.key) != -1) return;
                    switch (event.which) {
                        case 37:
                        case 39:
                            break;
                        case 27:    // Press ESC key
                            $('.ajax-search-result', $this).html('');
                            $(this).val('');
                            break;
                        case 38:    // Press UP key
                            HARU.header.process_search_up($this);
                            break;
                        case 40:    // Press DOWN key
                            HARU.header.process_search_down($this);
                            break;
                        case 13:    // Press ENTER key
                            var $item = $('.ajax-search-result li.selected a', $this);
                            if ($item.length == 0) {
                                event.preventDefault();
                                return false;
                            }

                            HARU.header.process_search_enter($this);

                            event.preventDefault();
                            break;
                        default:
                            clearTimeout(s_timeOut_search);
                            s_timeOut_search = setTimeout(function() {
                                category_search($this);
                            }, 1000);
                            break;
                    }
                });

                function category_search($this) {
                    var keyword = $('input[type="text"]', $this).val();
                    if (keyword.length < 3) {
                        var hint_message = $this.attr('data-hint-message');

                        $('.ajax-search-result', $this).html('<ul><li class="no-result">' + hint_message + '</li></ul>');
                        return;
                    }
                    // Process icon-search
                    $('button > i', $this).addClass('fa fa-spinner fa-spin');
                    $('button > i', $this).removeClass('fa fa-search');
                    $.ajax({
                        type   : 'POST',
                        data   : 'action=search_product_category&keyword=' + keyword + '&cat_id=' + $('.select-category > span', $this).attr('data-catid'),
                        url    : haru_framework_ajax_url,
                        success: function (data) {
                            $('button > i', $this).removeClass('fa fa-spinner fa-spin');
                            $('button > i', $this).addClass('fa fa-search');
                            if (data) {
                                $('.ajax-search-result', $this).html(data);
                                $('.ajax-search-result', $this).scrollTop(0);
                            }
                        },
                        error : function(data) {
                            $('button > i', $this).removeClass('fa fa-spinner fa-spin');
                            $('button > i', $this).addClass('fa fa-search');
                        }
                    });
                }
            });            
        },
        process_search_up : function($this) {
            var $item = $('li.selected', $this);

            if ($('li', $this).length < 2) return; // Only one item
            var $prev_item = $item.prev();

            $item.removeClass('selected');
            if ($prev_item.length) {
                $prev_item.addClass('selected');
            } else {
                $('li:last', $this).addClass('selected');
                $prev_item = $('li:last', $this);
            }
            if ($prev_item.position().top < $('.ajax-search-result', $this).scrollTop()) {
                $('.ajax-search-result', $this).scrollTop($prev_item.position().top);
            } else if ($prev_item.position().top + $prev_item.outerHeight() > $('.ajax-search-result', $this).scrollTop() + $('.ajax-search-result', $this).height()) {
                $('.ajax-search-result', $this).scrollTop($prev_item.position().top - $('.ajax-search-result', $this).height() + $prev_item.outerHeight());
            }
        },
        process_search_down : function($this) {
            var $item = $('li.selected', $this);

            if ($('li', $this).length < 2) return; // Only one item
            var $next_item = $item.next();

            $item.removeClass('selected');
            if ($next_item.length) {
                $next_item.addClass('selected');
            } else {
                $('li:first', $this).addClass('selected');
                $next_item = $('li:first', $this);
            }
            if ($next_item.position().top < $('.ajax-search-result', $this).scrollTop()) {
                $('.ajax-search-result', $this).scrollTop($next_item.position().top);
            } else if ($next_item.position().top + $next_item.outerHeight() > $('.ajax-search-result', $this).scrollTop() + $('.ajax-search-result', $this).height()) {
                $('.ajax-search-result', $this).scrollTop($next_item.position().top - $('.ajax-search-result', $this).height() + $next_item.outerHeight());
            }
        },
        process_search_enter : function($this) {
            var $item = $('li.selected a', $this);

            if ($item.length > 0) {
                window.location = $item.attr('href');
            }
        }
    };

    // Document ready
    HARU.onReady = {
        init: function () {
            HARU.base.init();
            HARU.header.init();
            HARU.page.init();
            HARU.blog.init();
        }
    };

    // Window resize
    HARU.onResize = {
    	init: function() {
            HARU.header.windowResized();
    	}
    }

    // Window onLoad
    HARU.onLoad = {
    	init: function() {
            HARU.header.windowLoad();
    		HARU.page.windowLoad();
    	}
    }

    // Window onScroll
    HARU.onScroll = {
    	init: function() {
    		
    	}
    }
    $(window).resize(HARU.onResize.init);
    $(window).scroll(HARU.onScroll.init);
    $(document).ready(HARU.onReady.init);
    $(window).load(HARU.onLoad.init); 

})(jQuery);