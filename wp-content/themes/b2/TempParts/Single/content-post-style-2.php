<?php
use B2\Modules\Common\Post;
/**
 * 文章内容页样式 post-style-2
 */

$post_id = get_the_id();

//获取post meta
$post_meta = Post::post_meta($post_id);

//获取缩略图
$thumb = Post::get_post_thumb($post_id);

//计算缩略图宽高
$w = b2_get_option('template_main','wrapper_width');
$w = preg_replace('/\D/s','',$w);

$w = ($w*0.7)+60;
$h = $w*0.7*0.618;
$w = ceil($w);
$h = ceil($h);

$thumb = b2_get_thumb(array(
    'thumb'=>$thumb,
    'width'=>$w,
    'height'=>$h
));

$excerpt = get_post_field('post_excerpt');
$down_open = get_post_meta($post_id,'b2_open_download',true);
?>
<div class="post-style-2-top">
    <div class="post-style-2-top-img" style="background-image:url(<?php echo $thumb; ?>);"></div>
</div>
<article class="single-article b2-radius box">
    <?php do_action('b2_single_article_before'); ?>
    <header class="entry-header">
        <div class="post-style-2-top-header" style="background-image:url(<?php echo $thumb; ?>);height:<?php echo $h; ?>px">
            <div class="entry-header-meta">
                <h1><?php echo get_the_title(); ?></h1>
            </div>
        </div>
        <div id="post-meta">
            <div class="post-meta-row">
                <ul class="post-meta">
                    <li>
                        <?php echo B2\Modules\Templates\Modules\Posts::get_post_cats('target="__blank"',$post_meta,array('cats'),'post_3'); ?>
                    </li>
                    <li class="single-date">
                        <span><?php echo $post_meta['date']; ?></span>
                    </li>
                    <li class="single-like">
                        <span><?php echo b2_get_icon('b2-heart-fill'); ?><b v-text="postData.up"></b></span>
                    </li>
                    <li class="single-eye">
                        <span><?php echo b2_get_icon('b2-eye-fill'); ?><b v-text="postData.views"></b></span>
                    </li>
                </ul>
                <?php if($down_open){ ?>
                    <div class="single-button-download"><button class="empty b2-radius" @click="scroll"><?php echo b2_get_icon('b2-download-cloud-line').__('前往下载','b2'); ?></button></div>
                <?php } ?>
            </div>
            <?php if(!is_audit_mode()){ ?>
                <div class="post-user-info">
                    <div class="post-meta-left">
                        <a class="link-block" href="<?php echo $post_meta['user_link']; ?>"></a>
                        <div class="avatar-parent"><img class="avatar b2-radius" src="<?php echo $post_meta['user_avatar']; ?>" /><?php echo $post_meta['user_title'] ? $post_meta['verify_icon'] : ''; ?></div>
                        <div class="post-user-name"><b><?php echo $post_meta['user_name']; ?></b><span class="user-title"><?php echo $post_meta['user_title']; ?></span></div>
                    </div>
                    
                    <div class="post-meta-right">
                        <div class="" v-if="self == false" v-cloak>
                            <button @click="followingAc" class="author-has-follow" v-if="following"><?php echo __('取消关注','b2'); ?></button>
                            <button @click="followingAc" v-else><?php echo b2_get_icon('b2-add-line').__('关注','b2'); ?></button>
                            <button class="empty" @click="dmsg()"><?php echo __('私信','b2'); ?></button>
                        </div>
                    </div>
                    
                </div>
            <?php } ?>
        </div>
    </header>
    <div class="entry-content">
        <?php do_action('b2_single_post_content_before'); ?>
        
        <?php if($excerpt){ ?>
            <div class="content-excerpt">
                <?php echo get_the_excerpt(); ?>
            </div>
        <?php } ?>
        
        <?php the_content(); ?>
        <?php
            global $page, $numpages, $multipage, $more;
            echo b2_pagenav(array('pages'=>$numpages,'paged'=>$page),true);
		?>
        <?php do_action('b2_single_post_content_after'); ?>
    </div>

    <?php do_action('b2_single_article_after'); ?>

</article>