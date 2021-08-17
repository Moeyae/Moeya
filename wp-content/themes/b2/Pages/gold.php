<?php
/**
 * 财富页面
 */
get_header();
$user_id = isset($_GET['u']) ? (int)$_GET['u'] : 0;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$type = get_query_var('b2_gold_type') ? get_query_var('b2_gold_type') : 'credit';

$tx_allow = b2_get_option('normal_gold','gold_tx');
$tx_gold_money = (float)b2_get_option('normal_gold','gold_money');
$tx_gold_tc = (float)b2_get_option('normal_gold','gold_tc');
?>
<div class="b2-single-content wrapper">
<div id="gold" class="content-area gold-page wrapper">
		<main id="main" class="site-main" ref="goldData" data-user="<?php echo $user_id; ?>" data-paged="<?php echo $paged; ?>" data-type="<?php echo $type; ?>" data-url="<?php echo b2_get_custom_page_url('gold'); ?>">
            <div class="custom-page-title box b2-radius b2-pd mg-b">
                <div class="gold-header">
                    <div class="gold-header-title">
                        <?php echo __('我的财富','b2'); ?>
                    </div>
                    <div class="gold-more">
                        <a href="<?php echo b2_get_custom_page_url('gold-top'); ?>" target="_blank"><?php echo __('财富排行 ❯','b2'); ?></a>
                    </div>
                </div>
                <div class="gold-info mg-t">
                    <div class="custom-page-row gold-row b2-radius">
                        <div><?php echo __('余额','b2'); ?><span class="user-money" v-cloak v-if="data.money || data.money == 0"><?php echo B2_MONEY_SYMBOL; ?><b v-text="data.money"></b></span></div>
                        <div>
                            <?php if($tx_allow) {?><button class="empty" @click="close"><?php echo __('提现','b2'); ?></button><?php } ?>
                            <button @click="pay()"><?php echo __('充值','b2'); ?></button>
                        </div>
                    </div>
                    <div class="custom-page-row gold-row b2-radius">
                        <div><span><?php echo __('积分','b2'); ?></span><span class="user-credit" v-cloak v-if="data.credit || data.credit == 0"><?php echo b2_get_icon('b2-coin-line'); ?><b v-text="data.credit"></b></span></div>
                        <div>
                            <span><button @click="buy()"><?php echo __('积分购买','b2'); ?></button></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-page-content box b2-radius">
                <div class="custom-page-row">
                    <div class="gold-page-table">
                        <div :class="['gold-credit gold-table',{'picked':opt.type === 'credit'}]" @click="change('credit')">
                            <?php echo __('积分记录','b2'); ?>
                        </div>
                        <div :class="['gold-money gold-table',{'picked':opt.type === 'money'}]" @click="change('money')">
                            <?php echo __('余额记录','b2'); ?>
                        </div>
                    </div>
                    <div class="button empty b2-loading empty-page text gold-bor" v-if="msg === ''"></div>
                    <div class="gold-bor" v-else-if="msg.length == 0" v-cloak>
                        <?php echo B2_EMPTY; ?>
                    </div>
                    <div class="gold-page-list" v-else v-cloak>
                        <div class="gold-header" style="color:#8590A6">
                            <div class="gold-list-row-1">
                                <?php echo __('时间','b2'); ?>
                            </div>
                            <div class="gold-list-row-2">
                                <?php echo __('类型','b2'); ?>
                            </div>
                            <div class="gold-list-row-3">
                                <?php echo __('数额','b2'); ?>
                            </div>
                            <div class="gold-list-row-4">
                                <?php echo __('总额','b2'); ?>
                            </div>
                            <div class="gold-list-row-5">
                                <?php echo __('描述','b2'); ?>
                            </div>
                        </div>
                        <ul>
                            <template v-for="item in msg">
                                <!-- 注册提示 -->
                                <li v-if="item.type == 4">
                                    <div class="gold-list-row-1">
                                    <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('注册奖励','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('感谢您的注册！','b2'); ?>
                                    </div>
                                </li>
                            
                                <!-- 邀请注册奖励 -->
                                <li v-if="item.type == 46">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('邀请码奖励','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您使用了%s的邀请码进行注册','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>
                            
                                <!-- 评论被回复奖励 -->
                                <li v-if="item.type == 1">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('评论被回复','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s等人回复了你在%s中的评论','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                            
                                <!-- 发表评论 -->
                                <li v-if="item.type == 2">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('发表评论','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您在%s中发表了评论','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                            
                                <!-- 文章被评论 -->
                                <li v-if="item.type == 3">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('文章被评论','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s在你的文章%s中发表了评论','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 文章被评论 -->
                                <li v-if="item.type == 5">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('文章发布','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您的文章已经发布：%s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                            
                                <!-- 评论点赞 -->
                                <li v-if="item.type == 10">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('评论被点赞','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s对你在%s中的评论%s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>','<span class="green"> '.__('表示赞同','b2').'</span>'); ?>
                                    </div>
                                </li>
                       
                                <!-- 评论点踩 -->
                                <li v-if="item.type == 8">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('评论点踩','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s对你在%s中的评论%s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>','<span class="red"> '.__('不太赞同','b2').'</span>'); ?>
                                    </div>
                                </li>

                                <!-- 被关注 -->
                                <li v-if="item.type == 11">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('被关注','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s关注了你','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 被关注 -->
                                <li v-if="item.type == 15">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('被取消关注','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s取消了对你的关注','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 关注 -->
                                <li v-if="item.type == 42">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('关注','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('你关注%s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 关注 -->
                                <li v-if="item.type == 43">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('关注取消','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('你取消了对%s的关注','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 积分变更 -->
                                <li v-if="item.type == 14">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('积分变更','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('管理员对你的积分进行了变更，变更原因：%s','b2'),'<p v-if="item.value"><code v-text="item.value"></code></p>'); ?>
                                    </div> 
                                </li>

                                <!-- 签到 -->
                                <li v-if="item.type == 16">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('签到','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('签到奖励','b2'); ?>
                                    </div>
                                </li>

                                <!-- 视频出售 -->
                                <li v-if="item.type == 49">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('视频出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了你发布的视频 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 视频出售 -->
                                <li v-if="item.type == 50">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('购买视频','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了视频 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 资源下载 -->
                                <li v-if="item.type == 53">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('资源出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了您发布的资源 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 视频出售 -->
                                <li v-if="item.type == 54">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('资源购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了资源 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 文章点赞 -->
                                <li v-if="item.type == 6">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('文章被点赞','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s%s你的文章 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span>'.__('喜欢','b2').'</span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>',''); ?>
                                    </div>
                                </li>

                                <!-- 文章点踩 -->
                                <li v-if="item.type == 7">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('文章被踩','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s觉得你的文章不太符合他的胃口 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 隐藏内容购买 -->
                                <li v-if="item.type == 33">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('内容购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了隐藏内容 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 隐藏内容出售 -->
                                <li v-if="item.type == 34">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('内容出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了您发布的隐藏内容 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 余额变更 -->
                                <li v-if="item.type == 37">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('余额变更','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('管理员对你的积分进行了变更，变更原因：%s','b2'),'<p v-if="item.value"><code v-text="item.value"></code></p>'); ?>
                                    </div>
                                </li>

                                <!-- 视频出售 -->
                                <li v-if="item.type == 47">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('视频出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了您发布的视频 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 视频购买 -->
                                <li v-if="item.type == 48">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('视频购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了%s发布的视频 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 资源出售 -->
                                <li v-if="item.type == 51">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('资源出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了您发布的资源 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 资源购买 -->
                                <li v-if="item.type == 52">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('资源购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了%s发布的资源 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 内容购买 -->
                                <li v-if="item.type == 31">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('内容购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了%s发布的隐藏内容 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 内容出售 -->
                                <li v-if="item.type == 32">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('内容出售','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了您发布的隐藏内容 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 被打赏 -->
                                <li v-if="item.type == 22">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('被打赏','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s打赏了你的文章 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 打赏 -->
                                <li v-if="item.type == 21">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('打赏','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您对文章作者进行了打赏 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 卡密充值 -->
                                <li v-if="item.type == 55">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('卡密充值','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您使用卡密进行了充值，卡号：%s','b2'),'<span class="card-number" v-text="item.content"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 积分购买 -->
                                <li v-if="item.type == 56">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('积分购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="parseInt(item.number)"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="parseInt(item.total)"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('您购买了积分','b2'); ?>
                                    </div>
                                </li>

                                <!-- 积分购买 -->
                                <li v-if="item.type == 38">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('积分购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('您购买了积分','b2'); ?>
                                    </div>
                                </li>

                                <!-- 账户充值 -->
                                <li v-if="item.type == 57">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('账户充值','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('您对账户进行了充值','b2'); ?>
                                    </div>
                                </li>

                                <!-- 会员购买 -->
                                <li v-if="item.type == 58">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('会员购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了%s','b2'),'<span v-text="item.content" class="red"></span>'); ?>
                                    </div>
                                </li>

                                <!-- 会员购买 -->
                                <li v-if="item.type == 59">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('付费认证','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('您购买了认证服务','b2'); ?>
                                    </div>
                                </li>

                                <!-- 认证积分奖励 -->
                                <li v-if="item.type == 60">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('认证积分奖励','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('您已认证成功','b2'); ?>
                                    </div>
                                </li>

                                <!-- 认证积分奖励 -->
                                <li v-if="item.type == 61">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('签到填坑','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo __('填坑成功','b2'); ?>
                                    </div>
                                </li>

                                <!-- 商品购买 -->
                                <li v-if="item.type == 63">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('商品购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>

                                <!-- 商品购买奖励积分 -->
                                <li v-if="item.type == 62">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('商品购买奖励','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您购买了 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 优惠劵使用 -->
                                <li v-if="item.type == 64">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('使用优惠劵','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您使用了优惠劵 › 优惠劵ID：%s','b2'),'<span class="gold-title">{{item.content}}</span>'); ?>
                                    </div>
                                </li>
                                <!-- 积分购买 -->
                                <li v-if="item.type == 28">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('积分购买','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您使用积分购买了 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 积分抽奖 -->
                                <li v-if="item.type == 29">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('积分抽奖','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您使用积分进行了抽奖 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 发布快讯 -->
                                <li v-if="item.type == 65">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('发布快讯','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您发布了快讯 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 一级分销 -->
                                <li v-if="item.type == 66">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('一级分销分红','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了%s › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span>[{{item.content.type}}]</span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 二级分销 -->
                                <li v-if="item.type == 67">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('二级分销分红','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了%s › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span>[{{item.content.type}}]</span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 三级分销 -->
                                <li v-if="item.type == 68">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('三级分销分红','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了%s › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span>[{{item.content.type}}]</span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 分销支出 -->
                                <li v-if="item.type == 69">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('分销支出','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s购买了%s › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span>[{{item.content.type}}]</span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <!-- 提现支出 -->
                                <li v-if="item.type == 41">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('提现支出','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('申请提现 › 手续费%s','b2'),'<b v-text="item.content"></b>'); ?>
                                    </div>
                                </li>
                                <!-- 付费入圈 -->
                                <li v-if="item.type == 70">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('付费入圈','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('付费入圈（%s） › %s','b2'),'<b v-text="circleText(item.value)"></b>','<a :href="item.title.link" target="_blank" v-html="item.title.name"></a>'); ?>
                                    </div>
                                </li>
                                <!-- 付费入圈 -->
                                <li v-if="item.type == 71">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('加入圈子','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s加入了您的付费圈（%s） › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<b v-text="circleText(item.value)"></b>','<a :href="item.title.link" target="_blank" v-html="item.title.name"></a>'); ?>
                                    </div>
                                </li>
                                <!-- 付费提问 -->
                                <li v-if="item.type == 73 || item.type == 72">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('圈子付费提问','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您在圈子发起了付费提问 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <li v-if="item.type == 76 || item.type == 78">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('偷瞄答案','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <div v-if="item.type == 76">
                                            <?php echo sprintf(__('%s偷瞄了你的答案 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                        </div>
                                        <div v-else>
                                            <?php echo sprintf(__('你偷瞄了 %s的答案 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                        </div>
                                    </div>
                                </li>
                                <li v-if="item.type == 79 || item.type == 80">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('答案被采纳','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s采纳了您的回答 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <li v-if="item.type == 81 || item.type == 82">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('问答分红','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('提问者没有采纳最佳答案，过期分红奖励 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <li v-if="item.type == 83 || item.type == 84">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('提问返还','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您提交的问题没有人回答，返还提问奖励 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <li v-if="item.type == 85 || item.type == 86">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('付费帖子被查看','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('%s查看了您的付费帖子 › %s','b2'),'<span class="gold-users" v-html="users(item.users)"></span>','<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                                <li v-if="item.type == 87 || item.type == 88">
                                    <div class="gold-list-row-1">
                                        <span v-html="item.date"></span>
                                    </div>
                                    <div class="gold-list-row-2">
                                        <?php echo __('查看了付费帖子','b2'); ?>
                                    </div>
                                    <div class="gold-list-row-3">
                                        <span v-text="item.number"></span>
                                    </div>
                                    <div class="gold-list-row-4">
                                        <span v-text="item.total"></span>
                                    </div>
                                    <div class="gold-list-row-5">
                                        <?php echo sprintf(__('您付费查看了隐藏帖子 › %s','b2'),'<span class="gold-title"><a :href="item.title.link" target="_blank" v-html="item.title.name"></a></span>'); ?>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <page-nav ref="goldNav" paged="<?php echo $paged; ?>" navtype="json" :pages="pages" type="p" :box="selecter" :opt="opt" :api="api" :url="url" title="<?php echo __('财富管理','b2'); ?>" @return="get"></page-nav>
                </div>
            </div>
            <div :class="['modal','ds-box gold-box',{'show-modal':show}]" v-cloak>
                <div class="modal-content b2-radius">
                    <div class="pay-box-title">
                        <div class="pay-box-left ds-pay-title">
                            <?php echo __('提现','b2'); ?>
                        </div>
                        <div class="pay-box-right">
                            <span class="pay-close" @click="close()">×</span>
                        </div>
                    </div>
                    <div class="pay-content" v-if="!success">
                        <p class="tx-title"><?php echo sprintf(__('提现时本站会扣除%s的手续费','b2'),($tx_gold_tc*100).'%'); ?></p>
                        <p class="tx-ye"><span><?php echo sprintf(__('当前余额%s','b2'),B2_MONEY_SYMBOL.'<b v-text="data.money"></b>'); ?></span></p>
                        <input type="text" placeholder="请输入提现金额" onkeypress="validate(event)" v-model="money">
                        <p class="tx-desc"><?php echo sprintf(__('最小提现金额%s','b2'),B2_MONEY_SYMBOL.$tx_gold_money); ?></p>
                        <p class="tx-submit"><button @click="tx" :disabled="locked" :class="locked ? 'b2-loading' : ''"><?php echo __('提交申请','b2'); ?></button></p>
                    </div>
                    <div class="pay-box-content" v-else>
                        <div class="pay-check">
                            <div class="green"><?php echo b2_get_icon('b2-check-double-line'); ?></div>
                            <h2>....<?php echo __('申请成功','b2'); ?>....</h2>
                            <p><?php echo __('请确保前端个人中心已经上传了收款码，否则不能到账！','b2'); ?></p>
                            <div class="pay-check-button"><button @click="refresh()"><?php echo __('确定','b2'); ?></button></div>
                        </div>
                    <div>
                </div>
            </div>
		</main>
    </div>
    <?php get_template_part( 'Sidebars/sidebar'); ?>
</div>
<?php
get_footer();