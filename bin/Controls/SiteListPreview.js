/**
 * @module package/quiqqer/template-tailwindcss/bin/Controls/SiteListPreview
 * @author www.pcsg.de (Michael Danielczok)
 */
define('package/quiqqer/template-tailwindcss/bin/Controls/SiteListPreview', [

    'qui/QUI',
    'qui/controls/Control'

], function (QUI, QUIControl) {
    "use strict";

    return new Class({

        Extends: QUIControl,
        Type   : 'package/quiqqer/template-tailwindcss/bin/Controls/SiteListPreview',

        Binds: [
            'toggle'
        ],

        initialize: function (options) {
            this.parent(options);

            this.active      = 0;
            this.isAnimating = false;

            this.addEvents({
                onImport: this.$onImport
            });

        },

        /**
         * event : on import
         */
        $onImport: function () {
            this.$Elm    = this.getElm();
            this.entries = this.$Elm.getElements('.qui-control-siteListPreview-entry');

            if (this.entries) {
                this.entries.addEvent('click', this.toggle);
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

            if (Target.className !== 'qui-control-siteListPreview-entry') {
                console.log()
                Target = Target.getParent('.qui-control-siteListPreview-entry')
            }

            var self            = this,
                LastActiveEntry = this.$Elm.getElement('.qui-control-siteListPreview-entry.active'),
                ActivePreview   = Target.getElement('.qui-control-siteListPreview-preview');

            // close active
            if (Target.hasClass('active')) {
                this.isAnimating = true;

                this.hide(ActivePreview).then(function () {
                    LastActiveEntry.removeClass('active');
                    self.isAnimating = false;
                });

                return;
            }

            this.isAnimating = true;

            if (!LastActiveEntry) {
                self.isAnimating = true;
                Target.addClass('active');

                this.show(ActivePreview).then(function () {
                    self.isAnimating = false;
                });

                return;
            }

            var LastActivePreview = LastActiveEntry.getElement('.qui-control-siteListPreview-preview');

            this.hide(LastActivePreview).then(function () {
                LastActiveEntry.removeClass('active');
                self.show(ActivePreview);
            }).then(function () {
                self.isAnimating = false;
                Target.addClass('active');
            });
        },

        /**
         * Hide content with animation
         *
         * @param Preview HTMLNode
         * @returns {Promise}
         */
        hide: function (Preview) {
            Preview.setStyle('pointer-events', 'none');

            return new Promise(function (resolve) {
                moofx(Preview).animate({
                    transform: 'translateX(-15px)',
                    opacity  : 0
                }, {
                    duration: 250,
                    callback: function () {
                        Preview.setStyle('display', 'none');
                        resolve();
                    }
                })
            })
        },

        /**
         * Show content with animation
         *
         * @param Preview HTMLNode
         * @returns {Promise}
         */
        show: function (Preview) {
            Preview.setStyles({
                display      : 'block',
                pointerEvents: null
            });

            return new Promise(function (resolve) {
                moofx(Preview).animate({
                    transform: 'translateX(0)',
                    opacity  : 1
                }, {
                    duration: 250,
                    callback: resolve
                })
            })
        }
    });
});