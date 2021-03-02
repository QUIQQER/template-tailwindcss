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
            this.$right      = null;
            this.$left       = null;
            this.$top        = 0;
            this.$maxWidth   = null;

            this.addEvents({
                onImport: this.$onImport
            });

        },

        /**
         * event : on import
         */
        $onImport: function () {
            var self = this;

            this.calcSizes();

            this.$Elm     = this.getElm();
            this.entries  = this.$Elm.getElements('.qui-control-siteListPreview-entry');
            this.previews = this.$Elm.getElements('.qui-control-siteListPreview-preview');

            if (this.entries && this.entries.length > 0) {
                this.entries.addEvent('click', this.toggle);
            }

            if (this.previews && this.previews.length > 0) {
                this.previews.forEach(function (Preview) {
                    Preview.setStyles({
                        left : self.$left ? self.$left - 15 : self.left,
                        right: self.$right ? self.$right - 15 : self.$right,
                    });

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
                Target = Target.getParent('.qui-control-siteListPreview-entry')
            }

            console.log(Target)
            console.log(this.$Elm)
            var self            = this,
                LastActiveEntry = this.$Elm.getElement('.qui-control-siteListPreview-entry.active'),
                Preview         = Target.getElement('.qui-control-siteListPreview-preview');

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

        /**$maxWidth
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

            var animation = {
                opacity: 0
            };

            if (this.$left) {
                animation.left = this.$left - 15;
            } else {
                animation.right = this.$right - 15;
            }

            return new Promise(function (resolve) {
                moofx(Preview).animate(animation, {
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
                pointerEvents: null,
                left         : this.$left ? this.$left - 15 : this.left,
                right        : this.$right ? this.$right - 15 : this.$right,
                maxWidth     : this.$maxWidth ? this.$maxWidth - 20 : this.$maxWidth
            });

            if (!Entry || !Entry.hasClass('qui-control-siteListPreview-entry')) {
                Entry = Preview.getParent('.qui-control-siteListPreview-entry')
            }

            Entry.addClass('active');

            var animation = {
                opacity: 1
            };

            if (this.$left) {
                animation.left = this.$left;
            } else {
                animation.right = this.$right;
            }

            return new Promise(function (resolve) {
                moofx(Preview).animate(animation, {
                    duration: 250,
                    callback: resolve
                })
            })
        },

        calcSizes: function () {
            if (QUI.getBodyScrollSize().x < 768) {
                // mobile
                return;
            }
            var windowWidth  = QUI.getBodyScrollSize().x,
                controlWidth = this.getElm().getSize().x,
                controlPos   = this.getElm().getPosition().x, // left corner pos
                Preview      = this.getElm().getElement('.qui-control-siteListPreview-preview'),
                previewWidth = 0,
                previewPos   = 0;

            if (!Preview) {
                return;
            }


            previewWidth = Preview.measure(function () {
                return this.getSize().x;
            });

            previewPos = Preview.measure(function () {
                return this.getPosition().x;
            });

            // show preview on left side
            if (controlPos > windowWidth - controlPos + controlWidth) {
                this.$right = controlWidth + 20;
                this.$left  = null;

                console.log(previewPos)

                if (previewPos < 20) {
                    console.log("huhu")
                    this.$maxWidth = previewWidth - 20;
                }

            } else {
                // show preview on right side
                this.$right = null;
                this.$left  = controlWidth + 20;
            }

            // calc preview max width


        }
    });
});