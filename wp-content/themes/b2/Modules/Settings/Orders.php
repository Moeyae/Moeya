<?php
namespace B2\Modules\Settings;

use B2\Modules\Settings\OrdersListTable;

class Orders{
    public function init(){
        add_action('cmb2_admin_init',array($this,'orders_options_page'));
    }

    public function orders_options_page(){
        if(!current_user_can('administrator')) return;

        // $orders = new_cmb2_box( array(
        //     'id'           => 'b2_orders_main_options_page',
        //     'object_types' => array( 'options-page' ),
        //     'option_key'      => 'b2_orders_main',
        //     'tab_group'    => 'b2_orders_options',
        //     'parent_slug'     => 'b2_main_options',
        //     //'tab_title'    => __('订单统计','b2'),
        //     'menu_title'   => __('订单管理','b2'),
        //     //'display_cb'      => array($this,'orders_statistics')
        // ) );

        $order_list = new_cmb2_box(array(
            'id'           => 'b2_orders_list_options_page',
            'title'   => __('订单管理','b2'), 
            'tab_title'    => __('订单管理','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_orders_list',
            'parent_slug'     => 'b2_main_options',
            'tab_group'    => 'b2_orders_options',
            'display_cb'=>array($this,'list_option_page_cb')
        ));

        $order_express = new_cmb2_box(array(
            'id'           => 'b2_orders_express_options_page',
            'title'   => __('快递接口信息','b2'), 
            'tab_title'    => __('快递接口信息','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_orders_express',
            'parent_slug'     => '/admin.php?page=b2_orders_main',
            'tab_group'    => 'b2_orders_options',
        ));

        $order_express->add_field( array(
            'before'=>'<p>'.sprintf(__('目前我们集成了易源数据的快递查询业务，请前往阿里云[%s全球物流快递查询_易源数据%s]然后购买（可0元购买试用），并将生成的key填写到下面设置项中（如果已经购买过，请前往阿里云市场买家中心查看）。'),'<a target="_blank" href="https://market.aliyun.com/products/56928004/cmapi025388.html?spm=5176.10695662.1996646101.searchclickresult.936a284anemB9f&aly_as=txv2-Uu5#sku=yuncode1938800000">','</a>').'<p>',
            'name'    => __( 'AppCode', 'b2' ),
            'id'      => 'express_appcode',
            'desc'=> __( '购买之后，请前往阿里云市场买家中心AppCode', 'b2' ),
            'type'    => 'text'
        ) );
    }

    public function orders_statistics($cmb_options){
        $tabs = $this->cb_options_page_tabs( $cmb_options );
        ?>
        <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
            <h2><?php echo __('订单统计','b2'); ?></h2>
            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                    <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
                <?php endforeach; ?>
            </h2>
            <div class="wrap">
                敬请期待
            </div>
        </div>
        <?php
    }

    public function cb_options_page_tabs( $cmb_options ) {
        $tab_group = $cmb_options->cmb->prop( 'tab_group' );
        $tabs      = array();
        foreach ( \CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
            if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
                $tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
                    ? $cmb->prop( 'tab_title' )
                    : $cmb->prop( 'title' );
            }
        }
        return $tabs;
    }

    public function list_option_page_cb($cmb_options){
        $this->delete_expired_orders();
        $tabs = $this->cb_options_page_tabs( $cmb_options );
        $order_code = new OrdersListTable();
        $order_code->prepare_items();
        $status = isset($_GET["order_state"]) ? esc_sql($_GET["order_state"]) : 'all';
        $ref_url = admin_url('admin.php?'.$_SERVER['QUERY_STRING']);

        if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'delete')){
            
            $order_ids = isset($_REQUEST['id']) ? (array)$_REQUEST['id'] : '';

            if($order_ids){
                $order_code->delete_coupons($order_ids);
                $ref_url = wp_get_referer();
                $ref_url = remove_query_arg(array('id', 'action','action2','s'), $ref_url);
                exit(header("Location: ".$ref_url));
                echo '<script> location.replace("'.$ref_url.'"); </script>';
            }
        }

        $type = b2_order_type();

        $type_get = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';


    ?>
        <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
            <?php if ( get_admin_page_title() ) : ?>
                <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
            <?php endif; ?>

            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                    <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
                <?php endforeach; ?>
            </h2>
            <div class="wrap">
                <?php if(isset($_GET['action']) && $_GET['action'] === 'edit'){ ?>
                    <?php 
                        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

                        $update = isset($_GET['order_update']) ? (int)$_GET['order_update'] : 0;

                        global $wpdb;
                        $table_name = $wpdb->prefix . 'zrz_order';

                        if($update){
                            $address = isset($_GET['order_address']) ? $_GET['order_address'] : '';
                            $content = isset($_GET['order_content']) ? $_GET['order_content'] : '';
                            $kd = isset($_GET['kuaidi']) ? $_GET['kuaidi'] : '';
                            $number = isset($_GET['express_number']) ? $_GET['express_number'] : '';
                            $state = isset($_GET['edit_state']) ? $_GET['edit_state'] : '';
                            $res = $wpdb->update(
                                $table_name, 
                                array(
                                    'order_state'=>$state,
                                    'order_address'=>$address,
                                    'order_content'=>$content,
                                    'tracking_number'=>maybe_serialize(array(
                                        'type'=>$kd,
                                        'number'=>$number
                                    ))
                                )
                                , array('id'=>$id)
                            );

                            if($res){
                                b2_settings_error('updated',__('更新成功','b2'));
                            }
                        }

                        $res = $wpdb->get_row($wpdb->prepare("
                                SELECT * FROM $table_name
                                WHERE id = %d
                            ",
                            $id
                        ),ARRAY_A);

                        if(empty($res)) {
                            echo __('没有找到此订单','b2').'</div>
                            </div>';
                            return;
                        }

                        $kuaidi = maybe_unserialize($res['tracking_number']);
                    ?>
                    <div id="profile-page">
                        <form id="order-edit" method="get">
                            <?php 
                                // global $wp;
                                // $url = home_url( $wp->request );
                                // $url = preg_replace('#page/([^/]*)$#','', $url);
                            ?>
                            <a href="<?php echo remove_query_arg(array('id','kuaidi','express_number','order_address','order_content','action','order_update','submit-update-order'),$ref_url); ?>">返回到订单列表</a>
                            <table class="form-table" role="presentation">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单ID：'); ?></label></th>
                                        <td><?php echo $id; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单号：'); ?></label></th>
                                        <td><?php echo $res['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单日期：'); ?></label></th>
                                        <td><?php echo $res['order_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单类型：'); ?></label></th>
                                        <td><?php echo $order_code->get_shop_order('order_type',$res['order_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('商品类型：'); ?></label></th>
                                        <td><?php echo $order_code->get_shop_order('order_commodity',$res['order_commodity']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('支付渠道：'); ?></label></th>
                                        <td><?php echo $order_code->get_shop_order('pay_type',$res['pay_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('商品名称：'); ?></label></th>
                                        <td>
                                            <?php 
                                                if($res['post_id'] == -1){
                                                    echo __('合并付款临时订单','b2');
                                                }else{
                                                    $title = \B2\Modules\Common\Orders::get_order_name($res['order_type'],$res['post_id']);
                                                    echo '<a href="'.$title['title']['link'].'" target="_blank">'.$title['title']['name'].'</a>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('产品单价：'); ?></label></th>
                                        <td><?php echo ($res['money_type'] == 1 ? __('积分：','b2') : B2_MONEY_SYMBOL).$res['order_price']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单总价：'); ?></label></th>
                                        <td><?php echo ($res['money_type'] == 1 ? __('积分：','b2') : B2_MONEY_SYMBOL).$res['order_total']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单数量：'); ?></label></th>
                                        <td><?php echo $res['order_count']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('订单状态：'); ?></label></th>
                                        <td>
                                            <?php
                                                $arr = array(
                                                    'w'=>__('等待付款','b2'),
                                                    'f'=>__('已付款未发货','b2'),
                                                    'c'=>__('已发货','b2'),
                                                    's'=>__('已删除','b2'),
                                                    'q'=>__('已签收','b2'),
                                                    't'=>__('已退款','b2'),
                                                );
                                            ?>
                                            <select name="edit_state" id="">
                                                <?php 
                                                    foreach($arr as $k => $v){
                                                        echo '<option value="'.$k.'" '.($res['order_state'] === $k ? 'selected' : false).'>'.$v.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="blogname"><?php echo __('快递公司：'); ?></label></th>
                                        <td>
                                            <?php $kds = b2_express_types(); 
                                                $kd_type = isset($kuaidi['type']) ? $kuaidi['type'] : 'shunfeng';
                                            ?>
                                            <select name="kuaidi" id="">
                                                <?php 
                                                    foreach($kds as $k => $v){
                                                        echo '<option value="'.$k.'" '.($kd_type === $k ? 'selected' : false).'>'.$v.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="user-email-wrap">
                                        <th scope="row"><label for="blogname"><?php echo __('运单号：'); ?></label></th>
                                        <td><input type="text" name="express_number" class="regular-text ltr" value="<?php echo isset($kuaidi['number']) ? $kuaidi['number'] : ''; ?>"></td>
                                    </tr>
                                    <tr class="user-description-wrap">
                                        <th scope="row"><label for="blogname"><?php echo __('订单地址：'); ?></label></th>
                                        <td><textarea rows="5" cols="30" name="order_address"><?php echo $res['order_address']; ?></textarea></td>
                                    </tr>
                                    <tr class="user-description-wrap">
                                        <th scope="row"><label for="blogname"><?php echo __('买家留言：'); ?></label></th>
                                        <td><textarea rows="5" cols="30" name="order_content"><?php echo $res['order_content']; ?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" name="page" value="b2_orders_list">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="order_update" value="1">
                            <input type="hidden" name="order_type" value="<?php echo isset($_GET['order_type']) ? $_GET['order_type'] : 0;?>">
                            <input type="hidden" name="order_state" value="<?php echo isset($_GET['order_state']) ? $_GET['order_state'] : 0;?>">
                            <input type="hidden" name="paged" value="<?php echo isset($_GET['paged']) ? (int)$_GET['paged'] : 0;?>">
                            <p class="submit"><input type="submit" name="submit-update-order" id="submit-cmb" class="button button-primary" value="保存"></p>
                        </form>
                    </div>
                <?php }else{ ?>
                    <div class="filter-row1">
                        <a href="<?php echo remove_query_arg(array('order_state','s'),$ref_url); ?>" class="<?php echo $status === 'all' ? 'current' : ''; ?>"><?php echo __('所有','b2'); ?><span class="count">（<?php echo $order_code->get_status_count('all'); ?>）</span></a>
                        <a href="<?php echo add_query_arg('order_state','payed',$ref_url); ?>" class="<?php echo $status === 'payed' ? 'current' : ''; ?>"><?php echo __('已付款','b2'); ?><span class="count">（<?php echo $order_code->get_status_count('payed'); ?>）</span></a>
                        <a href="<?php echo add_query_arg('order_state','wf',$ref_url); ?>" class="<?php echo $status === 'wf' ? 'current' : ''; ?>"><?php echo __('等待付款','b2'); ?><span class="count">（<?php echo $order_code->get_status_count('wf'); ?>）</span></a>
                    </div>
                    <ul class="subsubsub">
                        <li><a href="<?php echo remove_query_arg(array('order_type','s'),$ref_url); ?>" class="<?php echo $type_get === '' ? 'current' : ''; ?>"><?php echo __('所有','b2'); ?><span class="count">（<?php echo $order_code->get_status_count('all'); ?>）</span></a></li>
                        <?php
                            foreach ($type as $k => $v) {
                        ?>
                            <li>| <a href="<?php echo add_query_arg('order_type',$k,$ref_url); ?>" class="<?php echo $type_get === $k ? 'current' : ''; ?>"><?php echo $v; ?><span class="count">（<?php echo $order_code->get_status_count($k); ?>）</span></a></li>
                        <?php
                            }
                        ?>
                    </ul>
                    <div id="icon-users" class="icon32"><br/></div>  
                    <form id="coupon-filter" method="get">
                        <input type="hidden" name="order_state" value="<?php echo isset($_REQUEST['order_state']) ? $_REQUEST['order_state'] : ''; ?>">
                        <input type="hidden" name="order_type" value="<?php echo isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : ''; ?>">
                        <?php
                            $order_code->search_box( __('搜索订单','b2'), 'search_id' );
                        ?>
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

                        <?php $order_code->display() ?>
                    </form>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    public function delete_expired_orders(){
        global $wpdb; 
        $table_name = $wpdb->prefix . 'zrz_order';

        $res = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE order_state = %s AND order_date < date_sub(now(), interval 60 minute)", 'w'));

        return $res;
    }
}
?>