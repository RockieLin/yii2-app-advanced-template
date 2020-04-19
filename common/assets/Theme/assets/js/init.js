(function ($, window) {
    // generate a random number within a range (PHP's mt_rand JavaScript implementation)
    window.mt_rand = function (min, max) {
        var argc = arguments.length;
        if (argc === 0) {
            min = 0;
            max = 2147483647;
        } else if (argc === 1) {
            throw new Error('Warning: mt_rand() expects exactly 2 parameters, 1 given');
        } else {
            min = parseInt(min, 10);
            max = parseInt(max, 10);
        }
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    $('#content .modal').appendTo('body');

    // tooltips
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    // popovers
    $('[data-toggle="popover"]').popover();

    // print
    $('[data-toggle="print"]').click(function (e) {
        e.preventDefault();
        window.print();
    });

    $('ul.collapse')
        .on('show.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).closest('li').addClass('active');
        })
        .on('hidden.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).closest('li').removeClass('active');
        });


    if ($('html').is('.ie'))
        $('html').removeClass('app');

    if (typeof coreInit == 'undefined') {
        $('body')
            .on('mouseenter', '[data-toggle="dropdown"].dropdown-hover', function () {
                if (!$(this).parent('.dropdown').is('.open'))
                    $(this).click();
            });
    } else {
        $('[data-toggle="dropdown"]').dropdown();
    }

    $('.navbar.main')
        .add('#menu-top')
        .on('mouseleave', function () {
            $(this).find('.dropdown.open').find('> [data-toggle="dropdown"]').click();
        });

    $('[data-height]').each(function () {
        $(this).css({'height': $(this).data('height')});
    });


    window.enableNavbarMenusHover = function () {
        $('.navbar.main [data-toggle="dropdown"]')
            .add('#menu-top [data-toggle="dropdown"]')
            .addClass('dropdown-hover');
    };

    window.disableNavbarMenusHover = function () {
        $('.navbar.main [data-toggle="dropdown"]')
            .add('#menu-top [data-toggle="dropdown"]')
            .removeClass('dropdown-hover');
    };

    window.enableResponsiveNavbarSubmenus = function () {
        $('.navbar .dropdown-submenu > a').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parent().toggleClass('open');
        });
    };

    window.disableResponsiveNavbarSubmenus = function () {
        $('.navbar .dropdown-submenu > a')
            .off('click')
            .parent()
            .removeClass('open');
    };

    if (typeof $.fn.setBreakpoints !== 'undefined') {
        $(window).setBreakpoints({
            distinct: false,
            breakpoints: [768,
                992]
        });

        $(window).bind('exitBreakpoint768', function () {
            $('.container-fluid').addClass('menu-hidden');
            disableNavbarMenusHover();
            enableResponsiveNavbarSubmenus();
        });

        $(window).bind('enterBreakpoint768', function () {
            $('.container-fluid').removeClass('menu-hidden');
            enableNavbarMenusHover();
            disableResponsiveNavbarSubmenus();
        });

    }

    window.coreInit = true;

    $(window).on('load', function () {
        window.loadTriggered = true;

        if ($(window).width() < 768) {
            $('.container-fluid').addClass('menu-hidden');
            enableResponsiveNavbarSubmenus();
        } else {
            enableNavbarMenusHover();
        }

    });

    // weird chrome bug, sometimes the window load event isn't triggered
    setTimeout(function () {
        if (!window.loadTriggered)
            $(window).trigger('load');
    }, 2000);

})(jQuery, window);

function readURL(e, t) {
    if (e.files && e.files[0]) {
        var o = new FileReader;
        o.onload = function (e) {
            // t.attr("src", e.target.result)
            t.css('background-image', 'url(' + e.target.result + ')')
        }, o.readAsDataURL(e.files[0])
    }
}

function imagePreview() {
    $("body").on("click", ".uploadButton", function (e) {
        uploadBtn = $(this);
        fileInput = uploadBtn.siblings(".form-group").find('input:file');
        fileInput.click();
        fileInput.change(function () {
            readURL(this, uploadBtn.siblings(".imageLink").children(".imageContent"));
        })
    });
}