/* jshint devel:true */
/* global Vue */
/* global zaxuDocs */
/* global wp */
/* global ajaxurl */

Vue.directive('sortable', {
    bind: function(el, binding) {
        var $el = jQuery(el);
        $el.sortable({
            handle: '.zaxudocs-btn-reorder',
            stop: function(event, ui) {
                var ids = [];
                jQuery( ui.item.closest('ul') ).children('li').each(function(index, el) {
                    ids.push( jQuery(el).data('id') );
                });
                wp.ajax.post({
                    action: 'zaxudocs_sortable_docs',
                    ids: ids,
                    _wpnonce: zaxuDocs.nonce
                });
            },
            cursor: 'move',
            // connectWith: ".connectedSortable"
        });
    }
});

new Vue({
    el: '#zaxudocs-app',
    data: {
        editurl: '',
        docs: []
    },
    mounted: function() {
        var self = this,
            dom = jQuery(self.$el);
        this.editurl = zaxuDocs.editurl;
        this.viewurl = zaxuDocs.viewurl;
        dom.find('ul.docs').removeClass('not-loaded').addClass('loaded');
        jQuery.get(ajaxurl, {
            action: 'zaxudocs_admin_get_docs',
            _wpnonce: zaxuDocs.nonce
        }, function(data) {
            dom.find('.spinner').remove();
            dom.find('.no-docs').removeClass('not-loaded');
            self.docs = data.data;
        });
    },
    methods: {
        onError: function(error) {
            alert(error);
        },
        addDoc: function() {
            var that = this;
            this.docs = this.docs || [];
            var inputValue = prompt(zaxuDocs.enter_doc_title);
            if (inputValue != null && inputValue != "") {
                wp.ajax.send( {
                    data: {
                        action: 'zaxudocs_create_doc',
                        title: inputValue,
                        parent: 0,
                        _wpnonce: zaxuDocs.nonce
                    },
                    success: function(res) {
                        that.docs.unshift(res);
                    },
                    error: this.onError
                });
            };
        },
        removeDoc: function(doc, docs) {
            var self = this;
            if( confirm(zaxuDocs.delete_doc_title) ) {
                self.removePost(doc, docs);
            };
        },
        addSection: function(doc) {
            var inputValue = prompt(zaxuDocs.enter_section_title);
            if (inputValue != null && inputValue != "") {
                inputValue = inputValue.trim();
                if (inputValue) {
                    wp.ajax.send( {
                        data: {
                            action: 'zaxudocs_create_doc',
                            title: inputValue,
                            parent: doc.post.id,
                            order: doc.child.length,
                            _wpnonce: zaxuDocs.nonce
                        },
                        success: function(res) {
                            doc.child.push(res);
                        },
                        error: this.onError
                    });
                }
            };
        },
        removeSection: function(section, sections) {
            var self = this;
            if( confirm(zaxuDocs.delete_section_title) ) {
                self.removePost(section, sections);
            };
        },
        addArticle: function(section, event) {
            var parentEvent = event;
            var inputValue = prompt(zaxuDocs.enter_doc_title);
            if (inputValue != null && inputValue != "") {
                wp.ajax.send( {
                    data: {
                        action: 'zaxudocs_create_doc',
                        title: inputValue,
                        parent: section.post.id,
                        status: 'draft',
                        order: section.child.length,
                        _wpnonce: zaxuDocs.nonce
                    },
                    success: function(res) {
                        section.child.push(res);
                        var articles = jQuery(parentEvent.target).closest('.section-title').next();
                        if ( articles.hasClass('collapsed') ) {
                            articles.removeClass('collapsed');
                        }
                    },
                    error: function(error) {
                        alert(error);
                    }
                });
            };
        },
        removeArticle: function(article, articles) {
            var self = this;
            if( confirm(zaxuDocs.delete_article_title) ) {
                self.removePost(article, articles);
            };
        },
        removePost: function(index, items, message) {
            message = message || 'This post has been deleted';
            wp.ajax.send( {
                data: {
                    action: 'zaxudocs_remove_doc',
                    id: items[index].post.id,
                    _wpnonce: zaxuDocs.nonce
                },
                success: function() {
                    Vue.delete(items, index);
                },
                error: function(error) {
                    alert(error);
                }
            });
        },
        toggleCollapse: function(event) {
            jQuery(event.target).siblings('ul.articles').toggleClass('collapsed');
        }
    },
});
