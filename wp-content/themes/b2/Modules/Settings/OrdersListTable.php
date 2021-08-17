<?php 
namespace B2\Modules\Settings;

use \WP_List_Table;

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//邀请码表格
class OrdersListTable extends WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'id',
            'ajax' => false  
        ));
    }

    function get_shop_order($key,$val){

        if($key == 'order_type'){
            $arr = b2_order_type();
        }
    
        if($key == 'order_commodity'){
            $arr = array(
                '0'=>__('虚拟物品','b2'),
                '1'=>__('实物','b2'),
                '-1'=>__('合并付款','b2')
            );
        }
    
        if($key == 'order_state'){
            $arr = array(
                'w'=>'<span style="color:#333">'.__('等待付款','b2').'</span>',
                'f'=>'<span style="color:red">'.__('已付款未发货','b2').'</span>',
                'c'=>'<span style="color:blue">'.__('已发货','b2').'</span>',
                's'=>'<span style="color:#999">'.__('已删除','b2').'</span>',
                'q'=>'<span style="color:green">'.__('已签收','b2').'</span>',
                't'=>'<span style="color:#333">'.__('已退款','b2').'</span>',
            );
        }

        if($key == 'pay_type'){
            $arr = array(
                'credit'=>'<span>'.__('积分','b2').'</span>',
                'balance'=>'<span>'.__('余额支付','b2').'</span>',
                'alipay_normal'=>'<span>'.__('支付宝官方','b2').'</span>',
                'card'=>'<span>'.__('卡密支付','b2').'</span>',
                'xorpay'=>'<span>'.__('xorpay支付','b2').'</span>',
                'wecatpay_normal'=>'<span>'.__('微信官方','b2').'</span>',
                'xorpay'=>'<span>'.__('xorpay支付','b2').'</span>',
                'xunhu'=>'<span>'.__('迅虎支付','b2').'</span>',
                'alipay_hupijiao'=>'<span>'.__('虎皮椒-支付宝','b2').'</span>',
                'wecatpay_hupijiao'=>'<span>'.__('虎皮椒-微信','b2').'</span>',
                'payjs'=>'<span>'.__('payjs支付','b2').'</span>',
                'mapay'=>'<span>'.__('码支付','b2').'</span>',
                'yipay'=>'<span>'.__('易支付','b2').'</span>',
                'paypal'=>'<span>PayPal</span>',
                'pay020'=>'<span>020pay</span>',
                'suibian'=>__('随便付','b2')
            );
        }
    
        return isset($arr[$val]) ? $arr[$val] : '';
    }

    function column_default($item, $column_name) {

        switch ($column_name) {
            case 'user_id':
                $user_data = get_userdata($item->$column_name);
                if($user_data){
                    return '<a href="'.get_author_posts_url($item->$column_name).'" target="_blank">'.$user_data->display_name.'</a>';
                }else{
                    return __('游客','b2');
                }
            case 'post_id':

                if($item->$column_name == -1){
                    return __('合并付款临时订单','b2');
                }else{
                    $title = \B2\Modules\Common\Orders::get_order_name($item->order_type,(int)$item->$column_name);
                    return '<a href="'.$title['title']['link'].'" target="_blank">'.$title['title']['name'].'</a>';
                }
            case 'order_type':
                return $this->get_shop_order('order_type',$item->$column_name);
            case 'order_commodity':
                return $this->get_shop_order('order_commodity',$item->$column_name);
            case 'order_state':
                $item_type = $item->order_type;
                return $this->get_shop_order('order_state',$item->$column_name);
            case 'money_type':
                return (int)$item->$column_name === 0 ? __('货币','b2') : __('积分','b2');
            case 'pay_type':
                return  $this->get_shop_order('pay_type',$item->$column_name);
            case 'tracking_number':
                $track = maybe_unserialize($item->$column_name);
                $tk = b2_express_types();
                $tk = isset($track['type']) ? $tk[$track['type']] : '';
                $nb = isset($track['number']) ? $track['number'] : '';
                if($tk && $nb){
                    return '<p>'.$tk.'</p><p>'.$nb.'</p>';
                }

                return __('没有运单信息','b2');
            case 'order_address':
            case 'order_content':
            case 'order_value':
            case 'order_key':
            case 'order_date':
            case 'order_price':
            case 'order_total':
            case 'order_count':
            case 'id':
            case 'order_id':
                return $item->$column_name;
        }
    }
    
    function get_status_count($status){
        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';
        if($status === 'all'){
            $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        }elseif($status === 'payed'){
            $rowcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE order_state != %s",'w'));
        }elseif($status === 'wf'){
            $rowcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE order_state = %s",'w'));
        }else{
            $rowcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE order_type = %s",$status));
        }
        
        return $rowcount ? $rowcount : 0;
    }

    function delete_coupons($ids){
        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';
       
        if(is_array($ids)){
            foreach ($ids as $id) {
                $wpdb->query(
                    $wpdb->prepare( 
                        "DELETE FROM $table_name WHERE id = %d",
                        $id
                    )
                );
            }
        }
        
    }

    function column_id($item){

        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';
        $status = isset($_REQUEST['order_state']) ? $_REQUEST['order_state'] : '';

        $actions = array(
            'edit'    => sprintf('<a href="?page=%s&action=%s&id=%s&paged=%s&order_type=%s&order_state=%s">'.__('编辑','b2').'</a>','b2_orders_list','edit',$item->id,$paged,$type,$status),
            'delete'    => sprintf('<a onclick="return confirm(\'您确定删除该订单吗?\')" href="?page=%s&action=%s&id=%s&paged=%s">'.__('删除','b2').'</a>','b2_orders_list','delete',$item->id,$paged)
        );

        return sprintf('%1$s %2$s',
            $item->id,
            $this->row_actions($actions)
        );
    }

    function column_cb($item){

        return sprintf(
            '<input type="checkbox" name="id[]" value="%1$s" />',
            $item->id
        );
    }

    function get_columns() {
        return $columns = array(
            'cb' => '<input type="checkbox" />',
            'id'=>__('ID','b2'),
            'order_id' => __('订单号','b2'),
            'user_id' => __('买家','b2'),
            'post_id' => __('商品','b2'),
            'order_type' => __('订单类型','b2'),
            'order_commodity'=>__('商品类型','b2'),
            'order_state'=>__('订单状态','b2'),
            'order_date' => __('订单时间','b2'),
            'order_count' => __('订单数量','b2'),
            'order_price'=>__('产品单价','b2'),
            'order_total'=>__('订单总价'),
            'money_type'=>__('货币类型','b2'),
            'pay_type'=>__('支付渠道','b2'),
            'tracking_number'=>__('运单信息','b2'),
            'order_content'=>__('买家留言','b2'),
            'order_address'=>__('订单地址','b2')
        );
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'id'=>array('id',false),
            'order_id' => array('order_id',false),
            'user_id' => array('user_id',false),
            'post_id' => array('post_id',false),
            'order_type' => array('order_type',false),
            'order_commodity'=>array('order_commodity',false),
            'order_state'=>array('order_state',false),
            'order_date' => array('order_date',false),
            'order_count' => array('order_count',false),
            'order_price'=>array('order_price',false),
            'order_total'=>array('order_total',false),
            'money_type'=>array('money_type',false),
            'pay_type'=>array('pay_type',false),
            'tracking_number'=>array('tracking_number',false),
            'order_content'=>array('order_content',false),
            'order_address'=>array('order_address',false)
        );
        return $sortable_columns;
    }

    function display_tablenav( $which ) {

        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <?php if ( $this->has_items() ): ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
            <?php endif;
                $this->extra_tablenav( $which );
                $this->pagination( $which );
            ?>

            <br class="clear" />
        </div>
        <?php
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => __('删除','b2'),
            'edit'=>__('编辑','b2')
        );
        return $actions;
    }

    function prepare_items($val ='') {

        $this->process_bulk_action();

        global $wpdb; 
        $table_name = $wpdb->prefix . 'zrz_order';

        $query = "SELECT * FROM $table_name";

        //搜索
        $s = isset($_GET["s"]) ? esc_sql($_GET["s"]) : '';
        if(!empty($s)){
            $query.= $wpdb->prepare("
                WHERE order_id LIKE %s
                OR user_id LIKE %s
                ",
                '%'.$s.'%', '%'.$s.'%'
            );
        }

        //状态筛选
        $status = isset($_GET["order_state"]) ? esc_sql($_GET["order_state"]) : '';
        if ($status && $status != 'all') {
            $w = 'WHERE';
            if(strpos($query,'WHERE') !== false){
                $w = 'AND';
            }

            if($status === 'payed'){
                $query.= $wpdb->prepare(" $w `order_state` != %s",'w');
            }else{
                $query.= $wpdb->prepare(" $w `order_state` = %s",'w');
            }

        }

        //筛选商品类型
        $type = isset($_GET["order_type"]) ? esc_sql($_GET["order_type"]) : '';
        if ($type) {
            if(strpos($query,'WHERE') !== false){
                $query.= $wpdb->prepare(" AND `order_type`= %s",$type);
            }else{
                $query.= $wpdb->prepare(" WHERE `order_type`= %s",$type);
            }
        }

        //排序
        $orderby = isset($_GET["orderby"]) ? esc_sql($_GET["orderby"]) : 'id';
        $order = isset($_GET["order"]) ? esc_sql($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }

        $totalitems = $wpdb->query($query);

        $perpage = 20;

        $paged = isset($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';

        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        $totalpages = ceil($totalitems / $perpage);

        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $wpdb->get_results($query);
    }
}