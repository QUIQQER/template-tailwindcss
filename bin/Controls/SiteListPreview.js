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
            this.activeEntry = null;

            this.addEvents({
                onImport: this.$onImport
            });

        },

        /**
         * event : on import
         */
        $onImport: function () {
            var self = this;

            this.$Elm    = this.getElm();
            this.entries = this.$Elm.getElements('.qui-control-siteListPreview-entry');
            this.previews = this.$Elm.getElements('.qui-control-siteListPreview-preview');

            if (this.entries && this.entries.length > 0) {
                this.entries.addEvent('click', this.toggle);
            }

            if (this.previews && this.previews.length > 0) {
                this.previews.forEach(function (Preview) {
                    var Close = Preview.getElement('.qui-control-siteListPreview-preview-close');


                    if (Close) {
                        Close.addEvent('click', function () {
                            self.hide(Preview);
                        });
                    }
                });

                this.previews.addEvent('click', function (event) {
                    event.stopPropagation();
                });
            }
        },

        /**
         * Toggle preview
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
                Preview   = Target.getElement('.qui-control-siteListPreview-preview');

            // close active
            if (Target.hasClass('active')) {
                this.isAnimating = true;

                this.hide(Preview).then(function () {
                    LastActiveEntry.removeClass('active');
                    self.isAnimating = false;
                });

                return;
            }

            this.isAnimating = true;

            if (!LastActiveEntry) {
                self.isAnimating = true;
                Target.addClass('active');

                this.show(Preview).then(function () {
                    self.isAnimating = false;
                });

                return;
            }

            var LastActivePreview = LastActiveEntry.getElement('.qui-control-siteListPreview-preview');

            this.hide(LastActivePreview).then(function () {
                LastActiveEntry.removeClass('active');
                self.show(Preview);
            }).then(function () {
                self.isAnimating = false;
                Target.addClass('active');
            });
        },

        /**
         * Hide site preview
         *
         * @param Preview HTMLNode
         * @param Entry HTMLNode
         * @returns {Promise}
         */
        hide: function (Preview, Entry) {
            Preview.setStyle('pointer-events', 'none');

            if (!Entry || !Entry.hasClass('qui-control-siteListPreview-entry')) {
                Entry = Preview.getParent('.qui-control-siteListPreview-entry')
            }

            Entry.removeClass('active');

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
         * Show site preview
         *
         * @param Preview HTMLNode
         * @param Entry HTMLNode
         * @returns {Promise}
         */
        show: function (Preview, Entry) {
            Preview.setStyles({
                display      : 'block',
                pointerEvents: null
            });

            if (!Entry || !Entry.hasClass('qui-control-siteListPreview-entry')) {
                Entry = Preview.getParent('.qui-control-siteListPreview-entry')
            }

            Entry.addClass('active');

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