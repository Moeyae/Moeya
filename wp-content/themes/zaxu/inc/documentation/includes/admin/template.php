<?php
/*
 * @Description: Template VUE
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;
?>

<div class="wrap" id="zaxudocs-app">
    <h1><?php _e('Documentations', 'zaxu'); ?> <a class="page-title-action" href="javascript:;" v-on:click.prevent="addDoc"><?php _e('Add New Documentation', 'zaxu'); ?></a></h1>
    <!-- <pre>{{ $data | json }}</pre> -->
    <span class="spinner is-active" style="float: none;"></span>
    <ul class="docs not-loaded" v-sortable>
        <li class="single-doc" v-for="(doc, index) in docs" :data-id="doc.post.id">
            <h3>
                <a v-if="doc.post.caps.edit" target="_blank" :href="editurl + doc.post.id">{{ doc.post.title }}<span v-if="doc.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span></a>
                <span v-else>{{ doc.post.title }}<span v-if="doc.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span></span>
                <span class="zaxudocs-row-actions">
                    <a target="_blank" :href="doc.post.url" title="<?php _e('Preview the documentation', 'zaxu'); ?>">
                        <span class="dashicons dashicons-visibility"></span>
                    </a>
                    <span v-if="doc.post.caps.delete" class="zaxudocs-btn-remove" v-on:click="removeDoc(index, docs)" title="<?php _e('Delete this documentation', 'zaxu'); ?>">
                        <span class="dashicons dashicons-trash"></span>
                    </span>
                    <span class="zaxudocs-btn-reorder" title="<?php _e('Re-order the documentation', 'zaxu'); ?>">
                        <span class="dashicons dashicons-menu"></span>
                    </span>
                </span>
            </h3>
            <div class="inside">
                <ul class="sections" v-sortable>
                    <li v-for="(section, index) in doc.child" :data-id="section.post.id">
                        <span class="section-title" v-on:click="toggleCollapse">
                            <a v-if="section.post.caps.edit" target="_blank" :href="editurl + section.post.id">{{ section.post.title }}<span v-if="section.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span> <span v-if="section.child.length > 0" class="count">{{ section.child.length }}</span></a>
                            <span v-else>{{ section.post.title }}<span v-if="section.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span> <span v-if="section.child.length > 0" class="count">{{ section.child.length }}</span></span>
                            <span class="actions zaxudocs-row-actions">
                                <span class="zaxudocs-btn-reorder" title="<?php _e('Re-order this section', 'zaxu'); ?>">
                                    <span class="dashicons dashicons-menu"></span>
                                </span>
                                <a target="_blank" :href="section.post.url" title="<?php _e('Preview the section', 'zaxu'); ?>">
                                    <span class="dashicons dashicons-visibility"></span>
                                </a>
                                <span class="zaxudocs-btn-remove" v-if="section.post.caps.delete" v-on:click="removeSection(index, doc.child)" title="<?php _e('Delete this section', 'zaxu'); ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </span>
                                <span class="add-article" v-on:click="addArticle(section,$event)" title="<?php _e('Add a new article', 'zaxu'); ?>">
                                    <span class="dashicons dashicons-plus-alt"></span>
                                </span>
                            </span>
                        </span>
                        <ul class="articles collapsed connectedSortable" v-if="section.child" v-sortable>
                            <li class="article" v-for="(article, index) in section.child" :data-id="article.post.id">
                                <span>
                                    <a v-if="article.post.caps.edit" target="_blank" :href="editurl + article.post.id">{{ article.post.title }}<span v-if="article.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span></a>
                                    <span v-else>{{ article.post.title }}</span>
                                    <span class="actions zaxudocs-row-actions">
                                        <span class="zaxudocs-btn-reorder" title="<?php _e('Re-order this article', 'zaxu'); ?>">
                                            <span class="dashicons dashicons-menu"></span>
                                        </span>
                                        <a target="_blank" :href="article.post.url" title="<?php _e('Preview the article', 'zaxu'); ?>"><span class="dashicons dashicons-visibility"></span></a>
                                        <span class="zaxudocs-btn-remove" v-if="article.post.caps.delete" v-on:click="removeArticle(index, section.child)" title="<?php _e('Delete this article', 'zaxu'); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </span>
                                    </span>
                                </span>
                                <ul class="articles" v-if="article.child.length">
                                    <li v-for="(art, index) in article.child">
                                        <a v-if="art.post.caps.edit" target="_blank" :href="editurl + art.post.id">{{ art.post.title }}<span v-if="art.post.status != 'publish'" class="doc-status"><?php _e('Pending', 'zaxu'); ?></span></a>
                                        <span v-else>{{ art.post.title }}</span>
                                        <span class="actions zaxudocs-row-actions">
                                            <a target="_blank" :href="article.post.url" title="<?php _e('Preview the article', 'zaxu'); ?>">
                                                <span class="dashicons dashicons-visibility"></span>
                                            </a>
                                            <span class="zaxudocs-btn-remove" v-if="art.post.caps.delete" v-on:click="removeArticle(index, article.child)" title="<?php _e('Delete this article', 'zaxu'); ?>">
                                                <span class="dashicons dashicons-trash"></span>
                                            </span>
                                        </span>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="add-section">
                <a class="button button-primary" href="javascript:;" v-on:click.prevent="addSection(doc)"><?php _e('Add Section', 'zaxu'); ?></a>
            </div>
        </li>
    </ul>
    <div class="no-docs not-loaded" v-show="!docs.length">
        <h2 class="tips"><?php _e('Ready to start editing something awesome?', 'zaxu'); ?></h2>
        <a href="javascript:;" v-on:click.prevent="addDoc" class="button-primary button"><?php _e('Create Documentation', 'zaxu'); ?></a>
    </div>
</div>
