/**
 * @module package/quiqqer/template-tailwindcss/bin/Controls/VerticalTabSwitcher
 * @author www.pcsg.de (Michael Danielczok)
 */
define('package/quiqqer/template-tailwindcss/bin/Controls/VerticalTabSwitcher', [

    'qui/QUI',
    'qui/controls/Control'

], function (QUI, QUIControl) {
    "use strict";
    
    return new Class({

        Extends: QUIControl,
        Type   : 'package/quiqqer/template-tailwindcss/bin/Controls/VerticalTabSwitcher',

        Binds: [
            'toggle'
        ],

        initialize: function (options) {
            this.parent(options);

            this.active = 0;
            this.isAnimating = false;
            this.Nav = null;
            this.ContentContainer = null;
            this.navEntries = null;

            this.addEvents({
                onImport: this.$onImport
            });

        },

        /**
         * event : on import
         */
        $onImport: function () {


            this.$Elm = this.getElm();

            this.Nav = this.$Elm.getElement('.verticalTabSwitcher-nav ul');
            this.ContentContainer = this.$Elm.getElement('.verticalTabSwitcher-content ul');

            this.navEntries = this.Nav.getElements('.verticalTabSwitcher-nav-entry');

            if (this.navEntries) {
                this.navEntries.addEvent('click', this.toggle);
            }
        },

        /**
         * Toggle status
         *
         * @param event
         */
        toggle: function (event) {
            if (this.isAnimating) {
                return;
            }

            var Target = event.target;

            if (Target.nodeName !== 'LI') {
                Target = Target.getParent('.verticalTabSwitcher-nav-entry')
            }

            if (Target.hasClass('active')) {
                return;
            }

            var self = this;
            this.isAnimating = true;

            this.navEntries.forEach(function (NavEntry) {
                NavEntry.removeClass('text-black shadow-lg active')
            });

            Target.addClass('text-black shadow-lg active');

            var lastActive = this.active;

            this.active = Target.getAttribute('data-entry-nav-pos');

            var ContentActiveLast = this.ContentContainer.getElement(
                '[data-entry-content-pos="' + lastActive + '"]'
            );

            var ContentActiveNew = this.ContentContainer.getElement(
                '[data-entry-content-pos="' + this.active + '"]'
            );

            // nav icons
            var navIcons = this.Nav.getElements('.verticalTabSwitcher-nav-entry-icon');

            if (navIcons) {
                navIcons.forEach(function (Icon) {
                    self.removeIconColor(Icon)
                })
            }

            var NewIcon = Target.getElement('.verticalTabSwitcher-nav-entry-icon');

            if (NewIcon) {
                this.addIconColor(NewIcon);
            }

            this.hideContent(ContentActiveLast).then(function () {
                return self.showContent(ContentActiveNew);
            }).then(function () {
                self.isAnimating = false;
            });
        },

        /**
         * Hide content with animation
         *
         * @param Content HTMLNode
         * @returns {Promise}
         */
        hideContent: function (Content) {
            return new Promise(function (resolve) {
                moofx(Content).animate({
                    right  : -10,
                    opacity: 0
                }, {
                    duration: 300,
                    callback: function () {
                        Content.removeClass('active');
                        resolve();
                    }
                })
            })
        },

        /**
         * Show content with animation
         *
         * @param Content HTMLNode
         * @returns {Promise}
         */
        showContent: function (Content) {
            Content.setStyles({
                right  : -10,
                opacity: 0
            });

            Content.addClass('active');

            return new Promise(function (resolve) {
                moofx(Content).animate({
                    right  : 0,
                    opacity: 1
                }, {
                    duration: 300,
                    callback: resolve
                })
            })
        },

        /**
         * Remove background and set the color as font-color property
         *
         * @param Icon HTMLNode
         */
        removeIconColor: function (Icon) {
            var color = Icon.getAttribute('data-qui-color');

            if (!color) {
                return;
            }

            Icon.setStyles({
                color          : color,
                backgroundColor: 'transparent'
            })
        },

        /**
         * Set background font-color property and font color to white (#fff)
         *
         * @param Icon HTMLNode
         */
        addIconColor: function (Icon) {
            var color = Icon.getAttribute('data-qui-color');

            if (!color) {
                return;
            }

            Icon.setStyles({
                color          : '#fff',
                backgroundColor: color
            })
        }
    });
});