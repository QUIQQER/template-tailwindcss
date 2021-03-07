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

        Binds  : [
            'toggle'
        ],
        options: {
            previewwidth: 550
        },

        initialize: function (options) {
            this.parent(options);

            this.active       = 0;
            this.isAnimating  = false;
            this.activeEntry  = null;
            this.$right       = null;
            this.$left        = null;
            this.$top         = 0;
            this.$width       = 0;
            this.windowWidth  = 0;
            this.controlWidth = 0;
            this.controlPos   = 0; // left corner pos

//            QUI.addEvent('resize', this.calcSizes);

            QUI.addEvent('resize', function () {
                this.calcSizes();
            }.bind(this));

            this.addEvents({
                onImport: this.$onImport
            });

        },

        /**
         * event : on import
         */
        $onImport: function () {
            var self = this;

            this.$Elm         = this.getElm();
            this.entries      = this.$Elm.getElements('.qui-control-siteListPreview-entry');
            this.previews     = this.$Elm.getElements('.qui-control-siteListPreview-preview');
            this.$width       = this.getAttribute('previewwidth').toInt();
            this.windowWidth  = QUI.getBodyScrollSize().x;
            this.controlWidth = this.getElm().getSize().x;
            this.controlPos   = this.getElm().getPosition().x;


            if (!this.entries || this.entries.length < 1 ||
                !this.previews || this.previews.length < 1) {
                return;
            }

            this.calcSizes();

            this.entries.addEvent('click', this.toggle);

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
                        Preview.set('data-qui-open', 0);

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
            Preview.setStyle('display', 'block');
            Preview.set('data-qui-open', 1);

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
            console.log(1)
            // mobile
            if (QUI.getBodyScrollSize().x < 768) {
                this.calcSizesMobile();
                return;
            }

            if (!this.previews || this.previews.length < 1) {
                return;
            }

            this.windowWidth  = QUI.getBodyScrollSize().x;
            this.controlWidth = this.getElm().getSize().x;
            this.controlPos   = this.getElm().getPosition().x; // left corner pos

            var self         = this,
                Preview      = this.previews[0],
                previewWidth = this.getAttribute('previewwidth').toInt();

            var previewPos = Preview.measure(function () {
                return this.getPosition().x;
            });


            // show preview on the left side
            if (this.controlPos > this.windowWidth - this.controlPos + this.controlWidth) {
                this.$right = this.controlWidth + 20;
                this.$left  = null;

                // preview is wider than available space
                if (previewPos < 20 ||
                    20 + previewWidth > this.controlPos) {
                    this.$width = this.controlPos - 20 - 20;
                } else {
                    this.$width = previewWidth;
                }

            } else {
                // show preview on the right side
                this.$right = null;
                this.$left  = this.controlWidth + 20;

//                previewPos = previewPos + controlWidth;

//                console.warn("this.controlPos", this.controlPos)
//                console.log("previewPos", previewPos)
//                console.log("controlWidth", this.controlWidth)
//                console.log("this.windowWidth", this.windowWidth)
//                console.log("this.$width", this.$width)

                // preview is wider than available space
                /*if (previewPos + previewWidth > windowWidth - 20) {
                    console.log(1)
                    this.$width = windowWidth - previewPos - 20 - 20;
                } else {
                    console.log(2)
                    this.$width = this.getAttribute('previewwidth').toInt();
                }*/
            }

            this.previews.forEach(function (Preview) {
                // preview is open now
                if (Preview.get('data-qui-open') == 1) {
                    Preview.setStyles({
                        left : self.$left,
                        right: self.$right,
                        width: self.$width,
                        position: null
                    });

                    return;
                }

                // preview ist closed
                Preview.setStyles({
                    left : self.$left ? self.$left - 20 : self.left,
                    right: self.$right ? self.$right - 20 : self.$right,
                    width: self.$width,
                    position: null
                });
            });
        },

        calcSizesMobile: function () {
            this.previews.forEach(function (Preview) {
                Preview.setStyles({
                    left : 0,
                    right: 0,
                    width: '100%',
                    position: 'relative'
                });
            });
        }
    });
});