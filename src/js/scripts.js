;(function ($) {

    'use strict';

    $('a[href=\\#]').on('click', function (e) {
        e.preventDefault();
    })

    var Listzen = {

        _init: function () {
            var offCanvas = {
                menuBar: $('.trigger-off-canvas'),
                drawer: $('.listzen-offcanvas-drawer'),
                drawerClass: '.listzen-offcanvas-drawer',
                menuDropdown: $('.dropdown-menu.depth_0'),
            };
            Listzen.readyFunctionality();
            Listzen.magnificPopup();
            Listzen.headerSticky();
            Listzen.menuDrawerOpen(offCanvas);
            Listzen.offcanvasMenuToggle(offCanvas);
            Listzen.headerSearchOpen();
            Listzen.backToTop();
            Listzen.preLoader();
            Listzen.menuOffset();
            Listzen.masonary();
            Listzen.documentReady();
            Listzen.cl_overwrite();
            Listzen.bookmark();
            Listzen.listingLightbox();
            Listzen.listingFAQ();
            Listzen.swiper();
            Listzen.enllax();
            Listzen.popupLogin();
        },

        popupLogin: function () {
            // Open popup
            $(document).on('click', 'a.listzen-popup-login', function (e) {
                e.preventDefault();
                $('.listzen-popup').fadeIn(200);
            });

            // Close popup when clicking close button or overlay
            $(document).on('click', '.listzen-popup-close, .listzen-popup-overlay', function () {
                $('.listzen-popup').fadeOut(200);
            });
        },

        enllax: function () {
            if (typeof $.fn.enllax === 'function') {
                $('.listzen-parallax-bg-yes').enllax();
            }
        },

        swiper: function () {
            $(".listzen-swiper-init").each(function () {
                var swiperElement = this;
                var swiperConfig = $(this).data("swiper-settings") || {};

                var mySwiper;

                if (typeof Swiper === 'undefined') {
                    // Use Elementor's async swiper loader if Swiper is not globally loaded
                    elementorFrontend.utils.swiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {
                        console.log('New Swiper instance is ready: ', newSwiperInstance);
                        mySwiper = newSwiperInstance;
                    });
                } else {
                    // Swiper is already loaded globally
                    console.log('Swiper global variable is ready, create a new instance: ', Swiper);
                    mySwiper = new Swiper(swiperElement, swiperConfig);
                }
            });
        },

        listingFAQ: function () {
            // Initially hide all FAQ contents

            // Toggle on button click
            $('.rtcl-faqs-content').each(function () {
                var $faqContainer = $(this);
                $faqContainer.find('.faq-item.active .faq-content').show();
                $faqContainer.find('.faq-heading').on('click', function (e) {
                    e.preventDefault();

                    var $faqItem = $(this).closest('.faq-item');
                    var $content = $faqItem.find('.faq-content');
                    var $button = $(this).find('button');

                    // Close other items in this container only
                    $faqContainer.find('.faq-item').not($faqItem).find('.faq-content').slideUp();
                    $faqContainer.find('.faq-item').not($faqItem).removeClass('active');
                    $faqItem.toggleClass('active');
                    //$faqContainer.find('.faq-item').not($faqItem).find('i').removeClass('rt-icon-minus').addClass('rt-icon-plus');

                    // Toggle current item
                    $content.slideToggle();
                    // $button.toggleClass('rt-icon-plus rt-icon-minus');
                });
            });
        },
        listingLightbox: function () {

            $('.rtcl-listing-gallery__trigger').on('click', function (e) {
                e.preventDefault();

                var index = $(this).data('index');
                var items = [];

                $('.rtcl-gallery-item img').each(function () {
                    var $img = $(this);
                    var src = $img.data('large_image') || $img.attr('src');
                    var width = parseInt($img.data('large_image_width')) || 1200;
                    var height = parseInt($img.data('large_image_height')) || 800;
                    var alt = $img.attr('alt') || '';

                    items.push({
                        src: src,
                        w: width,
                        h: height,
                        title: alt
                    });
                });

                var options = {
                    index: index || 0, // Start at first image
                    bgOpacity: 0.9,
                    showHideOpacity: true,
                    arrowEl: true,
                    loop: true
                };

                var pswpElement = document.querySelectorAll('.pswp')[0];
                var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            });

            $('.rtcl-file-item.rtcl-file-item-image a').on('click', function (e) {
                e.preventDefault();

                var src = $(this).attr('href');
                var alt = $(this).find('img').attr('alt') || '';

                // You can optionally preload real image dimensions
                var img = new Image();
                img.onload = function () {
                    var items = [{
                        src: src,
                        w: img.naturalWidth || 1200,
                        h: img.naturalHeight || 800,
                        title: alt
                    }];

                    var options = {
                        index: 0,
                        bgOpacity: 0.9,
                        showHideOpacity: true,
                        arrowEl: false, // single image, no need for arrows
                        loop: false
                    };

                    var pswpElement = document.querySelectorAll('.pswp')[0];
                    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                    gallery.init();
                };
                img.src = src;
            });
        },

        bookmark: function () {
            const bookmarkBtn = document.getElementById("rtcl-bookmark-button");

            if (bookmarkBtn) {
                bookmarkBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    if (window.sidebar && window.sidebar.addPanel) { // Firefox version
                        window.sidebar.addPanel(document.title, window.location.href, "");
                    } else if (window.external && ('AddFavorite' in window.external)) { // IE Favorite
                        window.external.AddFavorite(location.href, document.title);
                    } else if (window.opera && window.print) { // Opera Hotlist
                        this.title = document.title;
                        return true;
                    } else { // webkit - safari/chrome
                        alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');
                    }
                });
            }
        },

        documentReady: function () {
            // $('.menu-icon-wrapper .listzen-button').prev().addClass('nav-button-prev');

            $('.listzen-navigation ul li.mega-menu > ul.dropdown-menu').each(function () {
                var liCount = $(this).children('li').length;
                $(this).addClass('columns-' + liCount);
            });


            var $form = $("#rtcl-contact-form");
            var $checkbox = $("#rtcl-terms-condition");

            if ($form.length && $checkbox.length) {
                $form.on("submit", function (e) {
                    if (!$checkbox.is(":checked")) {
                        e.preventDefault();
                        $checkbox.focus();
                        $checkbox.parent().addClass("shake");
                    }
                });
            }

            $(document).on('click', '.rtcl-listing-filter-collapse', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var sidebarWrap = $('.rtcl-sidebar-wrapper');
                sidebarWrap.addClass('open')

                if (sidebarWrap.find('.close-button').length === 0) {
                    sidebarWrap.append('<span class="close-button"><i class="rt-icon-cross-strong"></i></span>');
                }
                var overlay = $('<div class="rt-overlay" style="display: none;"></div>');
                $('body').append(overlay);
                overlay.fadeIn(300);
            })

            $(document).on('click', function (e) {
                var container = $(".rtcl-sidebar-wrapper");

                if (container.hasClass('open')) {
                    // If the click was outside the container
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        container.removeClass('open');
                        $('.rt-overlay').fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                }
            });

            $(document).on('click', '.rtcl-sidebar-wrapper .close-button', function (e) {
                var container = $(".rtcl-sidebar-wrapper");
                container.removeClass('open');
                $('.rt-overlay').fadeOut(300, function () {
                    $(this).remove();
                });
            })

            $(document).on('click', '.rtclVideoPlaceholder', function () {
                var $wrapper = $(this).closest('.rtcl-slider-item');
                var $iframe = $wrapper.find('.rtcl-lightbox-iframe');
                var src = $iframe.attr('data-src'); // direct attr, not .data()

                if (!src) return;

                // Fix: determine if ? or & is needed
                var separator = src.includes('?') ? '&' : '?';

                if (src.includes('youtube.com')) {
                    src += separator + 'autoplay=1';
                } else if (src.includes('vimeo.com')) {
                    src += separator + 'autoplay=1&muted=0';
                }

                $iframe.attr('src', src); // Update iframe to trigger play
                $(this).fadeOut();
            });

        },


        cl_overwrite: function () {
            $('.rtcl-filter-content').each(function () {
                var filterElement = $(this);
                var toggleOpenItem = filterElement.find('rtcl-filter-title-wrap');

                var options = filterElement.data('options');
                if (options.hasOwnProperty('type') && options['type'] === 'rt-select') {
                    filterElement.closest('.rtcl-ajax-filter-item').addClass('rtcl-custom-select');
                }
            })

            //Ajax Filter
            $('.rtcl-ajax-filter-wrap').css({'opacity': 1})

            $('body').on('click', '.rtcl-custom-select .rtcl-filter-title-wrap', function (e) {
                e.stopPropagation();
                const $item = $(this).closest('.rtcl-ajax-filter-item');
                $('.rtcl-ajax-filter-item').not($item).removeClass('select-active'); // Close others
                $item.toggleClass('select-active');
            });

            // Prevent closing when clicking anywhere inside .rtcl-ajax-filter-item
            $('body').on('click', '.rtcl-ajax-filter-item.rtcl-custom-select, .rtcl-ajax-filter-item.disable-collapse', function (e) {
                e.stopPropagation();
            });

            // Click outside: remove class
            $(document).on('click', function () {
                $('.rtcl-ajax-filter-item.rtcl-custom-select').removeClass('select-active');
            });
        },

        masonary: function () {
            if (typeof $.fn.masonry === 'function') {
                var masonryGrid = $('.listzen-masonry-grid').masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-item',
                    percentPosition: true
                });

                // Trigger layout after images load
                masonryGrid.imagesLoaded().progress(function () {
                    masonryGrid.masonry('layout');
                });
            }
        },
        headerSticky: function () {

            if ($('body').hasClass('has-sticky-header')) {

                var stickyPlaceHolder = $("#listzen-sticky-placeholder");
                var mainMenu = $(".main-header-section");
                var menuParent = mainMenu.closest('.site-header');
                var menuHeight = mainMenu.outerHeight() || 0;
                var headerTopbar = $('.listzen-topbar').outerHeight() || 0;
                var targrtScroll = headerTopbar + menuHeight;

                if ($('body').hasClass('listzen-header-2')) {
                    targrtScroll = $(window).height() - menuHeight;
                }

                // Main Menu
                if ($(window).scrollTop() > targrtScroll) {
                    menuParent.addClass('listzen-sticky');
                    stickyPlaceHolder.height(menuHeight);
                } else {
                    menuParent.removeClass('listzen-sticky');
                    stickyPlaceHolder.height(0);
                }

                //Mobile Menu
                var mobileMenu = $("#meanmenu");
                var mobileTopHeight = $('#mobile-menu-sticky-placeholder');

                if ($(window).scrollTop() > mobileMenu.outerHeight() + headerTopbar) {
                    mobileMenu.addClass('listzen-sticky');
                    mobileTopHeight.height(mobileMenu.outerHeight());
                } else {
                    mobileMenu.removeClass('listzen-sticky');
                    mobileTopHeight.height(0);
                }
            }
        },


        magnificPopup: function () {
            var yPopup = $(".listzen-popup-video");

            if (yPopup.length) {
                yPopup.magnificPopup({
                    disableOn: 700,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }
        },

        menuOffset: function () {
            var $dropDownMenu = $('.dropdown-menu > li');
            if ($dropDownMenu.length) {
                setTimeout(function () {
                    $dropDownMenu.each(function () {
                        var $this = $(this),
                            $win = $(window);

                        if ($this.offset().left + ($this.width() + 30) > $win.width() + $win.scrollLeft() - $this.width()) {
                            $this.addClass("dropdown-inverse");
                        } else if ($this.offset().left < ($this.width() + 30)) {
                            $this.addClass("dropdown-inverse-left");
                        } else {
                            $this.removeClass("dropdown-inverse");
                        }
                    });
                }, 500);

            }
        },

        readyFunctionality: function () {
            const siteHeader = $('.site-header');
            const adminBar = $('#wpadminbar');
            const paddingTop = siteHeader.height() + 30 + adminBar.height();

            const headerHeight = $('.site-header').outerHeight();
            const topbarHeight = $('.listzen-topbar').outerHeight() || 0;

            // Set CSS variables on the body
            $('body').css({
                '--header-height': `${headerHeight}px`,
                '--topbar-height': `${topbarHeight}px`
            });
            $('.has-trheader .listzen-breadcrumb-wrapper').css({'paddingTop': paddingTop + 'px', 'opacity': 1})
            $('.has-trheader.no-banner .content-area').css({'paddingTop': (paddingTop + 20) + 'px', 'opacity': 1})


            if ($(".rtcl-listing-card").length > 0) {
                $(".rtcl-listing-card").each(function () {

                    const currentEl = $(this);
                    const thumbWrapper = currentEl.find('.listing-thumb-inner');
                    const categoryWrap = currentEl.find('.rt-categories, .category').first();
                    const badgeEl = currentEl.find('.rtcl-listing-badge-wrap');

                    if (!categoryWrap.length) {
                        return;
                    }

                    if (!badgeEl.length) {
                        return;
                    }

                    const categoryWrpperPosition = categoryWrap.position().top + categoryWrap.height();

                    if (currentEl.hasClass('style-category-thumb')) {
                        badgeEl.attr(
                            'style',
                            'top: ' + (categoryWrpperPosition + 4) + 'px !important'
                        );

                        categoryWrap.css({"max-width": (thumbWrapper.width() - 15) +'px' })
                    }

                    if (badgeEl.is(':empty')) {
                        currentEl.addClass('badge-is-empty');
                    }
                })

            }
        },

        menuDrawerOpen: function (offCanvas) {
            offCanvas.menuBar.on('click', e => {
                e.preventDefault();
                offCanvas.menuBar.toggleClass('is-open')
                offCanvas.drawer.toggleClass('is-open');
                e.stopPropagation()
            });

            $(document).on('click', e => {
                if (!$(e.target).closest(offCanvas.drawerClass).length) {
                    offCanvas.drawer.removeClass('is-open');
                    offCanvas.menuBar.removeClass('is-open')
                }
            });
        },

        offcanvasMenuToggle: function (offCanvas) {
            offCanvas.drawer.each(function () {
                const caret = $(this).find('.caret');
                caret.on('click', function (e) {
                    e.preventDefault();
                    $(this).closest('li').toggleClass('is-open');
                    $(this).closest('a').next().slideToggle(300);
                })
            })
        },

        headerSearchOpen: function () {
            const $headerSearch = $('#listzen-header-search');
            const $openButton = $('a[href="#header-search"]');
            const $closeButton = $headerSearch.find('button.close');

            // Open the search popup
            $openButton.on("click", function (event) {
                event.preventDefault();
                event.stopPropagation();
                $headerSearch.addClass("open");

                setTimeout(function () {
                    $headerSearch.find('input[type="search"]').focus();
                }, 500)
            });

            // Close the search popup on close button click
            $closeButton.on("click", function (event) {
                event.preventDefault();
                $headerSearch.removeClass("open");
            });

            // Close the search popup when clicking outside
            $('body').on('click', function (event) {
                if (
                    $headerSearch.hasClass('open') &&
                    !$(event.target).closest($headerSearch).length &&
                    !$(event.target).is($openButton)
                ) {
                    $headerSearch.removeClass("open");
                }
            });
        }
        ,

        backToTop: function () {
            /* Scroll to top */
            $('.scrollToTop').on('click', function () {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });
        },

        /* windrow back to top scroll */
        backTopTopScroll: function () {
            if ($(window).scrollTop() > 100) {
                $('.scrollToTop').addClass('show');
            } else {
                $('.scrollToTop').removeClass('show');
            }
        },


        /* preloader */
        preLoader: function () {
            $('#preloader').fadeOut('slow', function () {
                $(this).remove();
            });
        },


    };

    $(document).ready(function (e) {
        Listzen._init();
    });

    $(document).on('load', () => {
        Listzen.menuOffset();
    })

    $(window).on('scroll', (event) => {
        Listzen.headerSticky(event);
        Listzen.backTopTopScroll(event);
    });

    $(window).on('resize', () => {
        Listzen.menuOffset();
    });

    $(window).on('elementor/frontend/init', () => {
        if (elementorFrontend.isEditMode()) {
            //For all widgets
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', () => {
                Listzen._init();
            });

        }
    });

    window.Listzen = Listzen;

})(jQuery);
