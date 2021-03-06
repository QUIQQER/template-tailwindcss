var initTailwind = function () {
    "use strict";

    document.getElements('[href=#top]').addEvent('click', function (event) {
        event.stop();
        new Fx.Scroll(window).toTop();
    });


    require(['qui/QUI', 'utils/Controls'], function (QUI, Controls) {
        QUI.addEvent("onError", function (msg, url, linenumber) {
            console.error(msg);
            console.error(url);
            console.error('LineNo: ' + linenumber);
        });


        /**
         * PageToTop button
         */
        if (document.getElements('[href=#top]')) {
            var PageToTop     = document.getElements('[href=#top]'),
                buttonVisible = false;

            // show on load after 1s delay
            if (QUI.getScroll().y > 300) {
                PageToTop.addClass('pageToTop__show');
                buttonVisible = true;
            }

            // show button PageToTop after scrolling down
            QUI.addEvent('scroll', function () {
                if (QUI.getScroll().y > 300) {
                    if (!buttonVisible) {
                        PageToTop.addClass('pageToTop__show');
                        buttonVisible = true;
                    }
                    return;
                }

                if (!buttonVisible) {
                    return;
                }
                PageToTop.removeClass('pageToTop__show');
                buttonVisible = false;
            });

            // scroll to top
            PageToTop.addEvent('click', function (event) {
                event.stop();
                new Fx.Scroll(window).toTop();
            });
        }


        /**
         * Header bar / Navigation
         */
        var HeaderBar = document.getElement('.header-bar');

        if (HeaderBar && QUI.getWindowSize().x >= 767) {
            setHeaderBarPos(HeaderBar, QUI);
        }

        /**
         * show the search input after clicking on the icon
         */
        if (document.getElement('.header-bar-suggestSearch') &&
            document.getElement('.header-bar-suggestSearch').getElement('.fa-search')) {

            var searchBar   = document.getElement('.header-bar-suggestSearch'),
                searchIcon  = searchBar.getElement('.fa-search'),
                searchInput = searchBar.getElement('input[type="search"]'),
                open        = false;

            searchIcon.addEvent('click', function (event) {
                event.stopPropagation();

                /* open */
                if (!open) {
                    searchInput.addEvent('click', function (e) {
                        e.stopPropagation();
                    });
                    window.addEvent('click', function () {
                        searchBar.removeClass('showSearch');
                        open = false;
                        window.removeEvents('click');
                    });

                    searchBar.addClass('showSearch');
                    searchInput.focus();
                    open = true;
                    return;
                }

                /* close */
                searchBar.removeClass('showSearch');
                open = false;
                window.removeEvents('click');
            })
        }
    });
};

if (typeof whenQuiLoaded !== 'undefined') {
    whenQuiLoaded().then(initTailwind);
} else {
    window.addEvent("domready", initTailwind);
}


/**
 * Handle header bar position.
 *
 * @param HeaderBar
 * @param QUI
 */
function setHeaderBarPos (HeaderBar, QUI) {
    var fixed    = false,
        istAlt   = false,
        altClass = 'header-bar-alt';

    if (HeaderBar.hasClass(altClass)) {
        istAlt = true;
    }

    var setAbsolute = function () {
        if (!istAlt) {
            HeaderBar.removeClass(altClass)
        }

        HeaderBar.setStyles({
            top     : 0,
            position: 'absolute'
        });
    };

    var setFixed = function () {
        if (!istAlt) {
            HeaderBar.addClass(altClass)
        }

        HeaderBar.setStyles({
            top     : -150,
            position: 'fixed'
        });
    };

    var showNav = function () {
        return new Promise(function (resolve) {
            moofx(HeaderBar).animate({
                top: 0,
            }, {
                duration: 500,
                callback: resolve
            });
        });
    };

    if (QUI.getScroll().y > 200) {
        setFixed();

        (function () {
            showNav();
            fixed = true;
        }).delay(1000)
    }

    QUI.addEvent('scroll', function () {
        if (QUI.getScroll().y > 200) {
            if (!fixed) {
                setFixed();
                (function () {
                    showNav();
                }).delay(100);

                fixed = true;
            }
            return;
        }

        if (!fixed) {
            return;
        }

        // set pos abs after user has scrolled to top
        if (QUI.getScroll().y === 0) {
            setAbsolute();
            fixed = false;
        }
    });
}
