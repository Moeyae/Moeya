<?php
/**
 * 消息页面
 */
get_header();
$user_id = isset($_GET['u']) ? (int)$_GET['u'] : 0;
$paged = get_query_var('paged');
$paged = $paged ? $paged : 1;
?>
<div class="b2-single-content wrapper">
    <div id="message-page" class="content-area message-page">
        <main class="site-main box b2-radius" ref="goldData" data-paged="<?php echo $paged; ?>" data-user="<?php echo $user_id; ?>" data-avatar="<?php echo B2_THEME_URI.'/Assets/fontend/images/default-avatar.png'; ?>">
            <div class="dmsg-header">
                <h2>
                    <?php echo __('通知','b2'); ?>
                </h2>
                <div class="message-header-right">
                    <?php echo sprintf(__('您有%s条新消息','b2'),'<span v-text="count"></span>'); ?>
                </div>
            </div>
            <div class="message-list">
                <div class="button empty b2-loading empty-page text" v-if="data === ''"></div>
                <div v-else-if="data.length == 0" v-cloak>
                    <?php echo B2_EMPTY; ?>
                </div>
                <ul v-else>
                <template v-for="item in data">
                    <!-- 注册提示 -->
                    <li v-if="item.type == 4">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo __('系统消息','b2'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><?php echo b2_get_option('normal_login','register_msg'); ?></p>
                        </div>
                    </li>
                
                    <!-- 邀请注册奖励 -->
                    <li v-if="item.type == 46">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo __('系统消息','b2'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><?php echo sprintf(__('您使用了%s的邀请码进行注册','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?></p>
                        </div>
                    </li>
                
                    <!-- 评论被回复奖励 -->
                    <li v-if="item.type == 1">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s等人回复了你的评论','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                            <p class="comment">
                                <span v-html="item.content"></span>
                            </p>
                        </div>
                    </li>
                
                    <!-- 文章被评论 -->
                    <li v-if="item.type == 3">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s在你的文章中发表了评论','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                            <p class="comment">
                                <span v-html="item.content"></span>
                            </p>
                        </div>
                    </li>

                    <!-- 文章发布 -->
                    <li v-if="item.type == 5">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo __('您的文章已经发布','b2'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>
                
                    <!-- 评论点赞 -->
                    <li v-if="item.type == 10">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s对你的评论表示赞同','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                            <p class="comment">
                                <span v-html="item.content"></span>
                            </p>
                        </div>
                    </li>
            
                    <!-- 评论点踩 -->
                    <li v-if="item.type == 8">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s不赞同你的评论','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                            <p class="comment">
                                <span v-html="item.content"></span>
                            </p>
                        </div>
                    </li>

                    <!-- 被关注 -->
                    <li v-if="item.type == 11">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s关注了你','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                        </div>
                    </li>

                    <!-- 被关注 -->
                    <li v-if="item.type == 15">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s取消了对你的关注','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                        </div>
                    </li>

                    <!-- 积分变更 -->
                    <li v-if="item.type == 14">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo __('系统消息','b2'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><?php echo __('管理员对你的积分进行了变更','b2'); ?></p>
                        </div>
                    </li>

                    <!-- 视频出售 -->
                    <li v-if="item.type == 49">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s购买了你发布的视频','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 资源下载 -->
                    <li v-if="item.type == 53">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s购买了您发布的资源','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 文章点赞 -->
                    <li v-if="item.type == 6">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s喜欢你的文章','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 文章点踩 -->
                    <li v-if="item.type == 7">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s不喜欢你的文章','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 余额变更 -->
                    <li v-if="item.type == 37">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo __('系统消息','b2'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><?php echo __('管理员对您的余额进行了变更','b2'); ?></p>
                        </div>
                    </li>

                    <!-- 视频出售 -->
                    <li v-if="item.type == 47">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s购买了您发布的视频','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 资源出售 -->
                    <li v-if="item.type == 51">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s购买了您发布的资源','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 隐藏内容购买 -->
                    <li v-if="item.type == 31 || item.type == 33">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('您购买了%s发布的隐藏内容','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 隐藏内容出售 -->
                    <li v-if="item.type == 32 || item.type == 34">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s购买了您发布的隐藏内容','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 被打赏 -->
                    <li v-if="item.type == 22">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s打赏了你的文章','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 认证成功通知 -->
                    <li v-if="item.type == 60">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('系统消息','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><?php echo __('您已认证成功','b2'); ?></span></p>
                        </div>
                    </li>

                    <!-- 加入圈子通知 -->
                    <li v-if="item.type == 71">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s加入了您的圈子','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 邀请回答 -->
                    <li v-if="item.type == 74">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s邀请您回答圈子中的提问','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 偷瞄 -->
                    <li v-if="item.type == 79 || item.type == 80">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('%s采纳了您在提问中的回答','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title"><a :href="item.title.link" v-html="item.title.name"></a></span></p>
                        </div>
                    </li>

                    <!-- 小黑屋 -->
                    <li v-if="item.type == 89">
                        <div class="message-icon"><img class="avatar" :src="getAvatar(item.users)" /></div>
                        <div class="message-content">
                            <h2><?php echo sprintf(__('被关小黑屋','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?> · <span v-html="item.date"></span><span class="new" v-if="item.read === '0'">NEW</span></h2>
                            <p><span class="gold-title">{{item.content}}</span></p>
                            <p><span class="red">({{item.title.name}})</span></p>
                        </div>
                    </li>
                    </template>
                </ul>
                <page-nav ref="goldNav" paged="<?php echo $paged; ?>" navtype="json" :pages="pages" type="p" :box="selecter" :opt="opt" :api="api" url="<?php echo b2_get_custom_page_url('message'); ?>" title="<?php echo __('通知','b2'); ?>" @return="get"></page-nav>
            </div>
        </main>
    </div>
    <?php get_template_part( 'Sidebars/sidebar'); ?>
</div>
<?php
get_footer();