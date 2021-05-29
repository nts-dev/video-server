/** 
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

var HARUSHOP = HARUSHOP || {};
(function ($) {
    "use strict";

    HARUSHOP = {
        init: function() {
            HARUSHOP.$html = $('html');
            HARUSHOP.$window = $(window);
            HARUSHOP.$shopFilterMenu       = $('#haru-shop-filter-menu');
            HARUSHOP.$searchPanel          = $('#haru-shop-search');
            HARUSHOP.$shopWrap             = $('.haru-archive-product');
            HARUSHOP.$shopBrowseWrap       = $('.archive-product-wrapper');
            HARUSHOP.$searchBtn            = $('#haru-shop-search-btn');
            HARUSHOP.$searchNotice         = $('#haru-shop-search-notice');
            HARUSHOP.filterPanelSliding    = false;
            HARUSHOP.filterPanelSlideSpeed = 300;
            HARUSHOP.shopLoaderSpeed       = 300;

            HARUSHOP.shopFilterMenuFnNames = {
                'cat':      'shopFiltersCategoriesToggle',
                'filter':   'shopFiltersSidebarToggle',
                'search':   'shopFiltersSearchToggle'
            };

            HARUSHOP.$window.on('popstate.harushop', function(e) {
                if (!e.originalEvent.state) { return; }

                if (e.originalEvent.state.haruShop) {
                    HARUSHOP.shopGetPage(window.location.href, true);
                }
            });

            if (HARUSHOP.$html.hasClass('history')) {
                HARUSHOP.hasPushState = true;
                window.history.replaceState({haruShop: true}, '', window.location.href);
            } else {
                HARUSHOP.hasPushState = false;
            }

            HARUSHOP.shopFiltersBind();
            HARUSHOP.shopSearchBind();
            HARUSHOP.shopLoadmoreBind();
        },
        shopFiltersBind: function() {
            HARUSHOP.$shopFilterMenu.find('a').on('click', function(e) {
                e.preventDefault();
              
                if (HARUSHOP.filterPanelSliding) { return; }
                    
                HARUSHOP.filterPanelSliding = true;
                
                var to = 0,
                    $this = $(this).parent('li'),
                    thisData = $this.data('panel');                 
             
                if (!$this.hasClass('active')) {
                    to = HARUSHOP.shopFiltersHideActivePanel();
                }
                
                $this.toggleClass('active');
                
                setTimeout(function() {
                    var fn = HARUSHOP.shopFilterMenuFnNames[thisData];
                    HARUSHOP[fn]();
                }, to);
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-categories a',  function(e) {
                e.preventDefault();
                
                var $this = $(this),
                    $thisLi = $this.parent('li');                   
                                    
                if (HARUSHOP.$searchBtn.parent('li').hasClass('active')) {
                    HARUSHOP.$searchBtn.trigger('click');
                }
                                    
                $('#haru-shop-categories').children('.current-cat').removeClass('current-cat');
                $thisLi.addClass('current-cat');
                
                HARUSHOP.shopGetPage($this.attr('href'));
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .haru_widget a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'));
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget_product_categories a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'));
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget_layered_nav a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'));
            });

            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget_layered_nav_filters a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'));
            });
                            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget_product_tag_cloud a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'), false, true);
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget_rating_filter a', function(e) {
                e.preventDefault();
                HARUSHOP.shopGetPage($(this).attr('href'));
            });

            HARUSHOP.$shopWrap.on('click', '#haru-shop-filters-reset', function(e) {
                e.preventDefault();
                var resetUrl = location.href.replace(location.search, '');
                HARUSHOP.shopGetPage(resetUrl);
            });
            
            HARUSHOP.$shopWrap.on('click', '#haru-shop-search-taxonomy-reset', function(e) {
                e.preventDefault();
                
                var $resetButton = $(this);
                if ($resetButton.closest('.haru-shop-results-bar').hasClass('is-search')) {
                    // Search
                    var urlSearchParam = HARUSHOP.urlGetParameter('s'),
                        resetUrl = (urlSearchParam) ? $resetButton.data('shop-url') : location.href.replace(location.search, '');
                        // @TODO: Close search form
                        HARUSHOP.$searchBtn.trigger('click');
                } else {
                    // Category or tag
                    var resetUrl = $resetButton.data('shop-url');
                }

                HARUSHOP.shopGetPage(resetUrl);
            });

            // Wigdet title click toggle on mobile
            HARUSHOP.$shopWrap.on('click', '#haru-shop-sidebar .widget-title', function(e) {
                $(this).toggleClass('active');
                $(this).parent().find('div, ul').toggleClass('show');
            });
        },
        shopFiltersHideActivePanel: function() {
            var to = 0,
                $activeMenu = HARUSHOP.$shopFilterMenu.children('.active');
            
            // Hide active panel
            if ($activeMenu.length) {
                $activeMenu.removeClass('active');
                
                var activeData = $activeMenu.data('panel');
                
                if ($activeMenu.is(':hidden') && activeData == 'cat') {
                    HARUSHOP.shopFiltersCategoriesReset();
                } else {
                    to = 300;
                    
                    var fn = HARUSHOP.shopFilterMenuFnNames[activeData];
                    HARUSHOP[fn]();
                }
            }
            
            return to;
        },
        shopFiltersCategoriesToggle: function() {          
            $('#haru-shop-categories').slideToggle(HARUSHOP.filterPanelSlideSpeed, function() {
                var $this = $(this);
                
                $this.toggleClass('fade-in');
                if (!$this.hasClass('fade-in')) {
                    $this.removeClass('force-show').css('display', '');
                }
                
                HARUSHOP.filterPanelSliding = false;
            });
        },
        shopFiltersCategoriesReset: function() {
            $('#haru-shop-categories').removeClass('fade-in force-show').css('display', '');
        },
        shopFiltersSidebarToggle: function() {
            var $shopSidebar = $('#haru-shop-sidebar'),
                isOpen = $shopSidebar.is(':visible');

            if (isOpen) {
                $shopSidebar.removeClass('fade-in');
            }
            
            $shopSidebar.slideToggle(HARUSHOP.filterPanelSlideSpeed, function() {
                if (!isOpen) {
                    $shopSidebar.addClass('fade-in');
                }
                
                HARUSHOP.filterPanelSliding = false;
            });
        },
        shopFiltersSearchToggle: function() {
            HARUSHOP.searchPanelToggle();
            
            HARUSHOP.currentSearch = '';
        },
        searchPanelToggle: function() {
            var $searchInput = $('#haru-shop-search-input');
            
            HARUSHOP.$searchPanel.slideToggle(200, function() {
                HARUSHOP.$searchPanel.toggleClass('fade-in');
                                                
                if (HARUSHOP.$searchPanel.hasClass('fade-in')) {
                    $searchInput.focus();
                } else {
                    $searchInput.val('');
                }
                
                HARUSHOP.filterPanelSliding = false;
            });
            
            // Hide search notice
            HARUSHOP.shopSearchHideNotice();
        },
        shopSearchHideNotice: function(s) {
            $('#haru-shop-search-notice').removeClass('show');
        },
        shopGetPage: function(pageUrl, isBackButton, isProductTag) {
            if (HARUSHOP.shopAjax) { return false; }
            
            if (pageUrl) {
                HARUSHOP.shopShowLoader();
                
                pageUrl = pageUrl.replace(/\/?(\?|#|$)/, '/$1');
                
                if (!isBackButton) {
                    HARUSHOP.setPushState(pageUrl);
                }
                    
                HARUSHOP.shopAjax = $.ajax({
                    url: pageUrl,
                    data: {
                        shop_load: 'full'
                    },
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    
                    method: 'POST',
                    
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Haru: AJAX error - shopGetPage() - ' + errorThrown);

                        HARUSHOP.shopHideLoader();
                        
                        HARUSHOP.shopAjax = false;
                    },
                    success: function(response) {
                        HARUSHOP.shopUpdateContent(response);
                        
                        HARUSHOP.shopLoadmoreBind();
                        HARUSHOP.shopAjax = false;
                    }
                });
            }
        },
        setPushState: function(pageUrl) {
            window.history.pushState({haruShop: true}, '', pageUrl);
        },
        shopUpdateContent: function(ajaxHTML) {
            var $ajaxHTML = $('<div>' + ajaxHTML + '</div>');
            
            var $ajaxCategories     = $ajaxHTML.find('#haru-shop-categories'),
                $ajaxSidebarFilters = $ajaxHTML.find('#haru-shop-widgets-ul'),
                $ajaxShopBrowseWrap = $ajaxHTML.find('.archive-product-wrapper');
                                            
            if ($ajaxCategories.length) { 
                var $shopCategories = $('#haru-shop-categories');
                
                if ($shopCategories.hasClass('fade-in')) {
                    $ajaxCategories.addClass('fade-in force-show');
                }
                
                $shopCategories.replaceWith($ajaxCategories); 
            }

            if ($ajaxSidebarFilters.length) {
                var $shopSidebarFilters = $('#haru-shop-widgets-ul');
                
                $shopSidebarFilters.replaceWith($ajaxSidebarFilters);
            }

            if ($ajaxShopBrowseWrap.length) {
                HARUSHOP.$shopBrowseWrap.replaceWith($ajaxShopBrowseWrap);
            }
            
            HARUSHOP.$shopBrowseWrap = $('.archive-product-wrapper');
    
            HARUSHOP.$shopBrowseWrap.find( 'ul.products' ).addClass( 'grid' );
            HARUSHOPMAIN.WooCommerce.init();
        },
        shopShowLoader: function(disableAnimation) {
            var $shopLoader = $('#haru-shop-products-overlay');
                            
            $shopLoader.addClass('show');
        },
        shopHideLoader: function(disableAnimation) {
            var $shopLoader = $('#haru-shop-products-overlay');
            
            $shopLoader.removeClass('haru-loader').addClass('fade-out');
            setTimeout(function() {
                $shopLoader.removeClass('show fade-out').addClass('haru-loader'); 
            }, HARUSHOP.shopLoaderSpeed);
        },
        shopSearchBind: function() {
            var $input, s, keyCode, validSearch;

            HARUSHOP.$searchInput = $('#haru-shop-search-input');
            
            HARUSHOP.searchAjax = null;
            HARUSHOP.currentSearch = '';
            
            $('#haru-shop-search-close').on('click', function(e) {
                e.preventDefault();
                HARUSHOP.$searchBtn.trigger('click');
            });
            
            $('#haru-shop-search-input').on('input', function() {
                var keyword_search = HARUSHOP.$searchInput.val();
                    keyword_search = keyword_search.trim();
                
                validSearch = keyword_search.length;
                
                if (validSearch) {
                    HARUSHOP.$searchNotice.addClass('show');
                } else {
                    HARUSHOP.$searchNotice.removeClass('show');
                }
            }).trigger('input');
            
            HARUSHOP.$searchInput.keypress(function(event) {
                $input = $(this);
                s = $input.val();
                keyCode = (event.keyCode ? event.keyCode : event.which);
                
                if (keyCode == '13') {
                    
                    // Prevent default form submit on "Enter" keypress
                    event.preventDefault();

                    var keyword_search = HARUSHOP.$searchInput.val();
                    keyword_search = keyword_search.trim();

                    if ( keyword_search.length && HARUSHOP.currentSearch !== s ) {
                        setTimeout(function() {
                            HARUSHOP.shopSearch(s);
                        }, HARUSHOP.filterPanelSlideSpeed);
                    } else {
                        HARUSHOP.currentSearch = s;
                    }
                }
            });
        },
        shopSearch: function(s) {
            HARUSHOP.shopShowLoader();
            
            HARUSHOP.currentSearch = s;

            HARUSHOP.searchAjax = $.ajax({
                url: searchUrl + encodeURIComponent(s),
                data: {
                    shop_load: 'search',
                    post_type: 'product'
                },
                dataType: 'html',
                method: 'GET',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log('Haru: AJAX error - shopSearch() - ' + errorThrown);
                    
                    HARUSHOP.shopHideLoader();
                    
                    HARUSHOP.searchAjax = null;
                },
                success: function(data) {
                    HARUSHOP.shopUpdateContent(data);
                    
                    HARUSHOP.shopLoadmoreBind();

                    HARUSHOP.searchAjax = null;
                }
            });
        },
        urlGetParameter: function(param) {
            var url = decodeURIComponent(window.location.search.substring(1)),
                urlVars = url.split('&'),
                paramName, i;

            for (i = 0; i < urlVars.length; i++) {
                paramName = urlVars[i].split('=');
                if (paramName[0] === param) {
                    return paramName[1] === undefined ? true : paramName[1];
                }
            }
        },
        updateUrlParameter: function(uri, key, value) {
            var i = uri.indexOf('#'),
                hash = i === -1 ? '' : uri.substr(i);
            uri = (i === -1) ? uri : uri.substr(0, i);
            
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i"),
                separator = (uri.indexOf('?') !== -1) ? "&" : "?";
            
            if (uri.match(re)) {
                uri = uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                uri = uri + separator + key + "=" + value;
            }
            
            return uri + hash;
        },
        shopLoadmoreBind: function() {
            HARUSHOP.$shopBrowseWrap.on('click', '.haru-loadmore-btn', function(e) {
                e.preventDefault();
                HARUSHOP.shopLoadmoreGetPage();
            });
        },
        shopLoadmoreGetPage: function() {
            if (HARUSHOP.shopAjax) return false;

            var $nextPageLink = HARUSHOP.$shopBrowseWrap.find('.haru-loadmore-link').find('a'),
                $infloadControls = HARUSHOP.$shopBrowseWrap.find('.haru-loadmore-controls'),
                nextPageUrl = $nextPageLink.attr('href');

            if (nextPageUrl) {
                nextPageUrl = HARUSHOP.updateUrlParameter(nextPageUrl, 'shop_load', 'products');
                
                $infloadControls.addClass('haru-loader');
                
                HARUSHOP.shopAjax = $.ajax({
                    url: nextPageUrl,
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    method: 'GET',
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Haru: AJAX error - shopLoadmoreGetPage() - ' + errorThrown);
                    },
                    complete: function() {
                        $infloadControls.removeClass('haru-loader');
                    },
                    success: function(response) {
                        var $response = $('<div>' + response + '</div>'), // Wrap the returned HTML string in a dummy 'div' element we can get the elements
                            $newElements = $response.find('ul.products').children('li.product').css({
                                opacity: 0
                            });

                        // Append the new elements
                        HARUSHOP.$shopBrowseWrap.find('ul.products').append($newElements);

                        $newElements.imagesLoaded(function () {
                            $newElements.animate({
                                opacity: 1
                            });

                            $('ul.products').isotope('appended', $newElements);
                            setTimeout(function() {
                                $('ul.products').isotope('layout');
                            }, 400);

                            HARUSHOPMAIN.WooCommerce.init();
                        });
                        
                        // Get the 'next page' URL
                        nextPageUrl = $response.find('.haru-loadmore-link').children('a').attr('href');

                        if (nextPageUrl) {
                            $nextPageLink.attr('href', nextPageUrl);
                        } else {
                            $infloadControls.addClass('hide-btn');
                            $('.haru-loadmore-all').addClass('show');

                            $nextPageLink.removeAttr('href');
                        }
                        
                        HARUSHOP.shopAjax = false;
                    }
                });
            } else {
                $infloadControls.addClass('hide-btn');
                $('.haru-loadmore-all').addClass('show');
            }
        }
    }

    $(document).ready(HARUSHOP.init);
})(jQuery);