/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/
var MegaMenu = MegaMenu || {};
(function($){
    "use strict";
    MegaMenu = {
        initialize: function() {
            MegaMenu.event();
        },
        event: function() {
            MegaMenu.menu_event();
            MegaMenu.window_scroll(); // Use this for vertical header
            MegaMenu.tabs_position(5);
            MegaMenu.menu_fullwidth();
            MegaMenu.windowLoad();
            MegaMenu.windowResize();
        },
        windowLoad: function() {
            $(window).load(function(){
                // Process header sidebar(doesn't use now)
                
            });
        },
        window_scroll: function(){
            $(window).on('scroll',function(event){
                // Do something
            });
        },
        windowResize: function(){
            $(window).on('resize',function(event){
                // Do something
            });
        },
        tabs_position: function(number_retry) {
            $('.navbar-nav').each(function() { // If use multi mega menu
                $('.menu_style_tab', this).each(function() {
                    var $this = $(this);
                    var tab_left_width = $(this).parent().outerWidth();

                    $('.menu_style_tab').each(function() {
                        var $tab = $(this);
                        
                        $(this).on('mouseover', function() {
                            if ($('> ul > li.active', $(this)).length == 0) {
                                // Add class active for first child
                                $('> ul > li:first-child', $(this)).addClass('active');

                                // Process tab height first tab
                                var tab_height = 0; // the height of the highest element (after the function runs)
                                var t_elem;  // the highest element (after the function runs)

                                $('*','.menu_style_tab > ul > li.active').each(function () {
                                    var $this = $(this);

                                    if ( $this.outerHeight() > tab_height ) {
                                        t_elem = this;
                                        tab_height = $this.outerHeight();
                                    }
                                    $('.menu_style_tab > ul').css('min-height', tab_height + 'px');
                                });

                                // Check carousel in tab
                                if ( $tab.find('.haru-carousel').length > 0 ) {
                                    tab_height = $(this).find('.owl-item').outerHeight(); // Need check

                                    $('.menu_style_tab > ul').css('min-height', tab_height + 'px');
                                }
                            } else { // Case hover to other tab different first tab then hover again to .menu_style_tab
                                // Process tab height other tab
                                var tab_height = 0; // the height of the highest element (after the function runs)
                                var t_elem;  // the highest element (after the function runs)

                                $('*','.menu_style_tab > ul > li.active').each(function () {
                                    var $this = $(this);

                                    if ( $this.outerHeight() > tab_height ) {
                                        t_elem = this;
                                        tab_height = $this.outerHeight();
                                    }
                                    $('.menu_style_tab > ul').css('min-height', tab_height + 'px');
                                });
                            }
                            // console.log(tab_height);
                        });
                    });

                    // Left for menu content
                    $('> li', this).each(function(){
                        $('> ul', this).css('left', tab_left_width + 'px');
                    });

                    // Process when hover tab
                    $('.menu_style_tab > ul > li').each(function(){
                        $(this).on('mouseover', function() {
                            $('li').removeClass('active');

                            $(this).addClass('active');

                            // Tab height when hover
                            $('li.active').each(function(){
                                MegaMenu.tab_position($(this));
                            });
                        });
                    });

                });
            });
        },
        tab_position: function($tab) {
            var tab_height = 0;
            var t_elem;
            // Height of tab when hover
            if ($('*', $tab).length != 0) {
                $('*','.menu_style_tab > ul > li.active').each(function () {
                    var $this = $(this);
                    if ( $this.outerHeight() > tab_height ) {
                        t_elem = this;
                        tab_height = $this.outerHeight();
                    }
                    $('.menu_style_tab > ul').css('min-height', tab_height + 'px');
                });
            }
        },
        menu_event: function() {
            MegaMenu.process_menu_mobile_click();
            MegaMenu.process_menu_popup_click();
        },
        process_menu_mobile_click: function() {
            $('.haru-mobile-header-nav li.menu-item .menu-caret, header.header-left li.menu-item .menu-caret').on('click', function(event) {
                $(this).toggleClass('active');
                
                if ($('> ul.sub-menu', $(this).parent()).length == 0) {
                    return;
                }
                if ($( event.target ).closest($('> ul.sub-menu', $(this).parent())).length > 0 ) {
                    return;
                }
                event.preventDefault();
                $($(this).parent()).toggleClass('sub-menu-open');
                $('> ul.sub-menu', $(this).parent()).slideToggle('fast');
            });
        },
        process_menu_popup_click: function() {
            $('.haru-nav-popup-menu li.menu-item .menu-caret').on('click', function(event) {
                $(this).toggleClass('active');
                
                if ($('> ul.sub-menu', $(this).parent()).length == 0) {
                    return;
                }
                if ($( event.target ).closest($('> ul.sub-menu', $(this).parent())).length > 0 ) {
                    return;
                }
                event.preventDefault();
                $($(this).parent()).toggleClass('sub-menu-open');
                $('> ul.sub-menu', $(this).parent()).slideToggle();
            });
        },
        menu_fullwidth: function() {
            $('.navbar-nav:not(.vertical-megamenu)').each(function() {
                $('> .mega-fullwidth', this).each(function() {
                    var $this = $(this);

                    $this.on('mouseenter', function() { // mouseover
                        var container = 1170; // Don't use container if fullscreen
                        var position = $('> .sub-menu', $this).offset().left - $(window).scrollLeft();
                        var window_width = $( window ).width();
                        if ( (window_width > 991) && (window_width < 1200) ) {
                            container = 970;
                        }
                        var left = (window_width - container) / 2;
                        var last_left = position - left;

                        $('> .sub-menu', $this).css({
                            'left'         : -last_left + 'px'
                        });
                    });

                    $this.on('mouseleave', function() { // mouseout
                        $('> .sub-menu', $this).css({
                            'left'         : '0'
                        });
                    });
                });
            });
        }
    }
    $(document).ready(function(){
        MegaMenu.initialize();
    });
})(jQuery);