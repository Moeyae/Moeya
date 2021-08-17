<?php
trait WPJAM_Register_Trait{
	protected $name;
	protected $args;

	public function __construct($name, $args=[]){
		$this->name	= $name;
		$this->args	= $args;
	}

	public function __get($key){
		return $this->args[$key] ?? null;
	}

	public function __set($key, $value){
		$this->args[$key]	= $value;
	}

	public function __isset($key){
		return isset($this->args[$key]);
	}

	public function __unset($key){
		unset($this->args[$key]);
	}

	public function to_array(){
		return $this->args;
	}

	protected static $_registereds	= [];

	public static function register($name, $args){
		$instance	= new static($name, $args);

		return self::register_instance($name, $instance);
	}

	protected static function register_instance($name, $instance){
		if(is_numeric($name)){
			trigger_error(self::class.'的注册 name「'.$name.'」'.'为纯数字');
			return;
		}

		self::$_registereds[$name]	= $instance;

		return $instance;
	}

	public static function unregister($name){
		unset(self::$_registereds[$name]);
	}

	public static function get_by($args=[], $output='objects', $operator='and'){
		return self::get_registereds($args, $output, $operator);
	}

	public static function get_registereds($args=[], $output='objects', $operator='and'){
		$registereds	= $args ? wp_filter_object_list(self::$_registereds, $args, $operator, false) : self::$_registereds;

		if($output == 'names'){
			return array_keys($registereds);
		}elseif(in_array($output, ['args', 'settings'])){
			return array_map(function($registered){ return $registered->to_array(); }, $registereds);
		}else{
			return $registereds;
		}
	}

	public static function get($name){
		return self::$_registereds[$name] ?? null;
	}

	public static function get_args($name){
		$object	= self::get($name);
		
		return $object ? $object->to_array() : null;
	}

	public static function exists($name){
		return isset(self::$_registereds[$name]);
	}
}

trait WPJAM_Type_Trait{
	use WPJAM_Register_Trait;
}

// 需要在使用的 CLASS 中设置 public static $meta_type
trait WPJAM_Meta_Trait{
	public static function get_meta_type_object(){
		return wpjam_get_meta_type_object(self::$meta_type);
	}

	public static function add_meta($id, $meta_key, $meta_value, $unique=false){
		return self::get_meta_type_object()->add_data($id, $meta_key, $meta_value, $unique);
	}

	public static function delete_meta($id, $meta_key, $meta_value=''){
		return self::get_meta_type_object()->delete_data($id, $meta_key, $meta_value);
	}

	public static function get_meta($id, $key = '', $single = false){
		return self::get_meta_type_object()->get_data($id, $key, $single);
	}

	public static function update_meta($id, $meta_key, $meta_value, $prev_value=''){
		return self::get_meta_type_object()->update_data($id, $meta_key, wp_slash($meta_value), $prev_value);
	}

	public static function delete_meta_by_key($meta_key){
		return self::get_meta_type_object()->delete_by_key($meta_key);
	}

	public static function update_meta_cache($object_ids){
		self::get_meta_type_object()->update_cache($object_ids);
	}

	public static function create_meta_table(){
		self::get_meta_type_object()->create_table();
	}

	public static function get_meta_table(){
		return self::get_meta_type_object()->get_table();
	}
}

trait WPJAM_Setting_Trait{
	private static $instance	= null;

	private $settings		= [];
	private $option_name	= '';
	private $site_default	= false;

	private function init($option_name, $site_default=false){
		$this->option_name	= $option_name;
		$this->site_default	= $site_default;

		if(is_null(get_option($option_name, null))){
			add_option($option_name, []);
		}

		$this->reset_settings();
	}

	public function __get($name){
		return $this->get_setting($name);
	}

	public function __set($name, $value){
		return $this->update_setting($name);
	}

	public function __isset($name){
		return isset($this->settings[$name]);
	}

	public function __unset($name){
		$this->delete_setting($name);
	}

	public function get_settings(){
		return $this->settings;
	}

	public function reset_settings(){
		$this->settings	= wpjam_get_option($this->option_name);

		if($this->site_default){
			$this->settings	+= wpjam_get_site_option($this->option_name);
		}
	}

	public function get_setting($name, $default=null){
		return $this->settings[$name] ?? $default;
	}

	public function update_setting($name, $value){
		$this->settings[$name]	= $value;
		return $this->save();
	}

	public function delete_setting($name){
		$this->settings	= wpjam_array_except($this->settings, $name);

		return $this->save();
	}

	private function save($settings=[]){
		if($settings){
			$this->settings	= array_merge($this->settings, $settings);
		}

		return update_option($this->option_name, $this->settings);
	}

	public static function get_instance(){
		if(is_null(self::$instance)){
			self::$instance	= new self();
		}

		return self::$instance;
	}
}

class WPJAM_Meta_Type{
	use WPJAM_Register_Trait;

	public $lazyloader	= null;

	public function get_lazyloader(){
		if(is_null($this->lazyloader)){
			$this->lazyloader	= new WPJAM_Lazyloader($this->name, 'get_'.$this->name.'_metadata', [$this, 'lazyload_callback']);
		}

		return $this->lazyloader;
	}

	public function lazyload_callback($check) {
		if($pending_objects = $this->get_lazyloader()->get_pending_objects()){
			update_meta_cache($this->name, $pending_objects);
		}

		return $check;
	}

	public function lazyload($ids){
		$this->get_lazyloader()->queue_objects($ids);
	}

	public function add_data($id, $meta_key, $meta_value, $unique=false){
		return add_metadata($this->name, $id, $meta_key, wp_slash($meta_value), $unique);
	}

	public function delete_data($id, $meta_key, $meta_value=''){
		return delete_metadata($this->name, $id, $meta_key, $meta_value);
	}

	public function get_data($id, $key = '', $single = false){
		return get_metadata($this->name, $id, $key, $single);
	}

	public function update_data($id, $meta_key, $meta_value, $prev_value=''){
		if($meta_value){
			return update_metadata($this->name, $id, $meta_key, wp_slash($meta_value), $prev_value);
		}else{
			return delete_metadata($this->name, $id, $meta_key, $prev_value);
		}
	}

	public function delete_by_key($meta_key){
		return delete_metadata($this->name, null, $meta_key, '', true);
	}

	public function update_cache($object_ids){
		if($object_ids){
			update_meta_cache($this->name, $object_ids);
		}
	}

	public function get_table(){
		return $this->table ?: $GLOBALS['wpdb']->prefix.sanitize_key($this->name).'meta';
	}

	public function create_table(){
		global $wpdb;

		$column	= sanitize_key($this->name).'_id';
		$table	= $this->get_table();

		if($wpdb->get_var("show tables like '{$table}'") != $table) {
			$wpdb->query("CREATE TABLE {$table} (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				{$column} bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (meta_id),
				KEY {$column} ({$column}),
				KEY meta_key (meta_key(191))
			)");
		}
	}

	public static function register($name, $args){
		$mt_obj 	= new self($name, $args);
		$table_name	= sanitize_key($name).'meta';

		$GLOBALS['wpdb']->$table_name = $mt_obj->get_table();

		self::register_instance($name, $mt_obj);
	}
}

class WPJAM_Lazyloader{
	private $pending_objects	= [];
	private $object_type;
	private $filter;
	private $callback;

	public function __construct($object_type, $filter, $callback) {
		$this->object_type	= $object_type;
		$this->filter		= $filter;
		$this->callback		= $callback;
	}

	public function queue_objects($object_ids){
		foreach($object_ids as $object_id){
			if(!isset($this->pending_objects[$object_id])){
				$this->pending_objects[$object_id]	= 1;
			}
		}

		add_filter($this->filter, $this->callback);
	}

	public function get_pending_objects($reset=true){
		$pending_objects	= $this->pending_objects ? array_keys($this->pending_objects) : [];

		if($reset){
			$this->pending_objects	= [];
			remove_filter($this->filter, $this->callback);
		}

		return $pending_objects;
	}
}

class WPJAM_Option_Setting{
	use WPJAM_Register_Trait;

	public static function register($name, $args){
		if(is_callable($args)){
			$args	= call_user_func($args, $name);
		}

		if(empty($args['sections'])){	// 支持简写
			if(isset($args['fields'])){
				$args['sections']	= [$name => ['title'=>($args['title'] ?? ''), 'fields'=>wpjam_array_pull($args, 'fields')]];
			}else{
				$args['sections']	= $args;
			}
		}

		foreach ($args['sections'] as $section_id => &$section) {
			if(is_callable($section['fields'])){
				$section['fields']	= call_user_func($section['fields'], $name, $section_id);
			}
		}

		$instance 	= new self($name, wp_parse_args($args, [
			'option_group'		=> $name, 
			'option_page'		=> $name, 
			'option_type'		=> 'array',	// array：设置页面所有的选项作为一个数组存到 options 表， single：每个选项单独存到 options 表。
			'capability'		=> 'manage_options',
			'ajax'				=> true,
			'sections'			=> []
		]));

		return self::register_instance($name, $instance);
	}

	public function get_fields(){
		return array_merge(...array_values(wp_list_pluck($this->sections, 'fields')));
	}

	public function update_callback($name, $value, $is_network_admin=false){
		if($this->update_callback && $this->update_callback != 'update_option' && is_callable($this->update_callback)){
			return call_user_func($this->update_callback, $name, $value, $is_network_admin);
		}

		if($this->option_type == 'array'){
			$callback	= $is_network_admin ? 'wpjam_update_site_option' : 'wpjam_update_option';
		}else{
			$value		= is_wp_error($value) ? null : $value;
			$callback	= $is_network_admin ? 'update_site_option' : 'update_option';
		}

		return call_user_func($callback, $name, $value);
	}

	public function get_value($name='', $is_network_admin=false){
		if($this->option_type == 'array'){
			if($is_network_admin){
				$value	= wpjam_get_site_option($this->name);
			}else{
				$value	= wpjam_get_option($this->name);

				if($this->site_default){
					$value	+= wpjam_get_site_option($this->name);
				}
			}	

			return $name ? ($value[$name] ?? null) : $value;
		}else{
			if($name){
				$callback	= $is_network_admin ? 'get_site_option' : 'get_option';
				$value		= call_user_func($callback, $name, null);

				return is_wp_error($value) ? null : $value;
			}else{
				return null;
			}
		}
	}

	public static function value_callback($name, $args){
		$instance	= self::get($args['option_name']);

		return $instance->get_value($name, $args['is_network_admin']);
	}

	public static function sanitize_callback($value){
		$option		= str_replace('sanitize_option_', '', current_filter());
		$registered	= get_registered_settings();

		if(!isset($registered[$option])){
			return $value;
		}

		$option_type	= $registered[$option]['option_type'];

		if($option_type == 'array'){
			$fields	= $registered[$option]['fields'];
			$value	= wpjam_validate_fields_value($fields, $value) ?: [];

			if(!is_wp_error($value)){
				$object	= self::get($option);
				$value	= array_merge($object->get_value(), $value);
				$value	= wpjam_array_filter($value, function($item){ return !is_null($item); });

				if($object->sanitize_callback && is_callable($object->sanitize_callback)){
					$value	= call_user_func($object->sanitize_callback, $value, $option);
				}
			}
		}else{
			$fields	= [$option=>$registered[$option]['field']];
			$value	= wpjam_validate_fields_value($fields, [$option=>$value]);

			if(!is_wp_error($value)){
				$value	= $value[$option] ?? null;
			}
		}

		if(is_wp_error($value)){
			add_settings_error($option, $value->get_error_code(), $value->get_error_message());

			return get_option($option);
		}

		return $value;
	}

	public static function get_args($name){
		if(!self::get($name)){
			$setting	= apply_filters(wpjam_get_filter_name($name, 'setting'), []);

			if(!$setting){
				$settings	= apply_filters_deprecated('wpjam_settings', [[], $name], 'WPJAM Basic 4.6', 'wpjam_register_option');

				if(!$settings || empty($settings[$name])) {
					return [];
				}

				$setting	= $settings[$name];
			}

			self::register($name, $setting);
		}

		return self::get($name)->to_array();
	}
}

class WPJAM_Setting{
	public static function get_option($name, $blog_id=0){
		$value	= (is_multisite() && $blog_id) ? get_blog_option($blog_id, $name) : get_option($name);

		return self::sanitize_value($value);
	}

	public static function update_option($name, $value, $blog_id=0){
		$value	= self::sanitize_value($value);

		return (is_multisite() && $blog_id) ? update_blog_option($blog_id, $name, $value) : update_option($name, $value);
	}

	public static function get_site_option($name){
		return is_multisite() ? self::sanitize_value(get_site_option($name, [])) : [];
	}

	public static function update_site_option($name, $value){
		return is_multisite() ? update_site_option($name, self::sanitize_value($value)) : true;
	}

	private static function sanitize_value($value){
		return (is_wp_error($value) || empty($value) || !is_array($value)) ? [] : $value;
	}

	public static function get_setting($option_name, $setting_name, $blog_id=0){
		$option_value	= is_string($option_name) ? self::get_option($option_name, $blog_id) : $option_name;

		if($option_value && !is_wp_error($option_value) && isset($option_value[$setting_name])){
			$value	= $option_value[$setting_name];

			if(is_wp_error($value)){
				return null;
			}elseif($value && is_string($value)){
				return  str_replace("\r\n", "\n", trim($value));
			}else{
				return $value;
			}
		}else{
			return null;
		}
	}

	public static function update_setting($option_name, $setting_name, $setting_value, $blog_id=0){
		$option_value	= self::get_option($option_name, $blog_id);

		$option_value[$setting_name]	= $setting_value;

		return self::update_option($option_name, $option_value, $blog_id);
	}

	public static function delete_setting($option_name, $setting_name, $blog_id=0){
		if($option_value = self::get_option($option_name, $blog_id)){
			$option_value	= wpjam_array_except($option_value, $setting_name);
		}

		return self::update_option($option_name, $option_value, $blog_id);
	}

	public static function get_option_settings(){	// 兼容代码
		return WPJAM_Option_Setting::get_registereds([], 'settings');
	}
}

class WPJAM_Cache_Group{
	private $group;

	public function __construct($group){
		$this->group	= $group;
	}

	public function cache_get($key){
		return wp_cache_get($key, $this->group);
	}

	public function cache_add($key, $value, $time=DAY_IN_SECONDS){
		return wp_cache_add($key, $value, $this->group, $time);
	}

	public function cache_set($key, $value, $time=DAY_IN_SECONDS){
		return wp_cache_set($key, $value, $this->group, $time);
	}

	public function cache_delete($key){
		return wp_cache_delete($key, $this->group);
	}

	private static $instances	= [];

	public static function get_instance($group){
		if(!isset(self::$instances[$group])){
			self::$instances[$group]	= new self($group);
		}

		return self::$instances[$group];
	}
}

class WPJAM_AJAX{
	use WPJAM_Register_Trait;

	public function create_nonce($args=[]){
		$nonce_action	= $this->name;

		if($this->nonce_keys){
			foreach($this->nonce_keys as $key){
				if(!empty($args[$key])){
					$nonce_action	.= ':'.$args[$key];
				}
			}
		}

		return wp_create_nonce($nonce_action);
	}

	public function verify_nonce($nonce){
		$nonce_action	= $this->name;

		if($this->nonce_keys){
			foreach($this->nonce_keys as $key){
				if($value = wpjam_get_data_parameter($key)){
					$nonce_action	.= ':'.$value;
				}
			}
		}

		return wp_verify_nonce($nonce, $nonce_action);
	}

	public function callback(){
		if(!$this->callback || !is_callable($this->callback)){
			wp_die('0', 400);
		}
		
		$nonce	= wpjam_get_parameter('_ajax_nonce', ['method'=>'POST']);

		if(!$this->verify_nonce($nonce)){
			wpjam_send_json(['errcode'=>'invalid_nonce', 'errmsg'=>'非法操作']);
		}

		$result	= call_user_func($this->callback);

		wpjam_send_json($result);
	}

	public static function register($name, $args){
		$obj	= new self($name, $args);

		add_action('wp_ajax_'.$name, [$obj, 'callback']);

		if($obj->nopriv){
			add_action('wp_ajax_nopriv_'.$name, [$obj, 'callback']);
		}

		self::register_instance($name, $obj);
	}

	public  static function get_screen_id(){
		return self::$screen_id;
	}

	public static function set_screen_id(){
		if(isset($_POST['screen_id'])){
			$screen_id	= $_POST['screen_id'];
		}elseif(isset($_POST['screen'])){
			$screen_id	= $_POST['screen'];	
		}else{
			$ajax_action	= $_REQUEST['action'] ?? '';

			if($ajax_action == 'fetch-list'){
				$screen_id	= $_GET['list_args']['screen']['id'];
			}elseif($ajax_action == 'inline-save-tax'){
				$screen_id	= 'edit-'.sanitize_key($_POST['taxonomy']);
			}elseif($ajax_action == 'get-comments'){
				$screen_id	= 'edit-comments';
			}else{
				$screen_id	= false;
			}
		}

		if($screen_id){
			if('-network' === substr($screen_id, -8)){
				if(!defined('WP_NETWORK_ADMIN')){
					define('WP_NETWORK_ADMIN', true);
				}
			}elseif('-user' === substr($screen_id, -5)){
				if(!defined('WP_USER_ADMIN')){
					define('WP_USER_ADMIN', true);
				}
			}
		}

		self::$screen_id	= $screen_id;
	}

	public static $screen_id		= null;
	public static $enqueue_scripts	= null;

	public static function enqueue_scripts(){
		if(isset(self::$enqueue_scripts)){
			return;
		}

		self::$enqueue_scripts	= true;

		$scripts	= '
	if (typeof ajaxurl == "undefined")  var ajaxurl	= "'.admin_url('admin-ajax.php').'";
	jQuery(function($){
		$.fn.extend({
			wpjam_submit: function(callback){
				let _this	= $(this);
				
				$.post(ajaxurl, {
					action:			$(this).data(\'action\'),
					_ajax_nonce:	$(this).data(\'nonce\'),
					data:			$(this).serialize()
				},function(data, status){
					callback.call(_this, data);
				});
			},
			wpjam_action: function(callback){
				let _this	= $(this);
				
				$.post(ajaxurl, {
					action:			$(this).data(\'action\'),
					_ajax_nonce:	$(this).data(\'nonce\'),
					data:			$(this).data(\'data\')
				},function(data, status){
					callback.call(_this, data);
				});
			}
		});
	});';

		if(did_action('wpjam_static') && !is_login()){
			wpjam_register_static('wpjam-script',	['type'=>'script',	'source'=>'value',	'value'=>$scripts]);
		}else{
			wp_enqueue_script('jquery');
			wp_add_inline_script('jquery', $scripts);
		}
	}
}

class WPJAM_Verify_TXT{
	use WPJAM_Register_Trait;

	public static function __callStatic($method, $args){
		$name	= $args[0];

		if(self::get($name)){
			if(in_array($method, ['get_name', 'get_value'])){
				$item	= wpjam_get_setting('wpjam_verify_txts', $name);
				$key	= $method == 'get_name' ? 'name' : 'value';

				return $item[$key] ?? '';
			}elseif($method == 'set'){
				$item	= ['name'=>$args[1], 'value'=>$args[2]];

				wpjam_update_setting('wpjam_verify_txts', $name, $item);

				return true;
			}
		}	
	}

	public static function filter_root_rewrite_rules($root_rewrite){
		if(empty($GLOBALS['wp_rewrite']->root)){
			$home_path	= parse_url(home_url());

			if(empty($home_path['path']) || '/' == $home_path['path']){
				$root_rewrite	= array_merge(['([^/]+)\.txt?$'=>'index.php?module=txt&action=$matches[1]'], $root_rewrite);
			}
		}
		
		return $root_rewrite;
	}

	public static function module($action){
		if($values = wpjam_get_option('wpjam_verify_txts')){
			$name	= str_replace('.txt', '', $action).'.txt';
			
			foreach($values as $key => $value) {
				if($value['name'] == $name){
					header('Content-Type: text/plain');
					echo $value['value'];

					exit;
				}
			}
		}

		wp_die('错误');
	}
}

class WPJAM_Theme_Upgrader{
	use WPJAM_Register_Trait;

	public function filter_site_transient($transient){
		if($this->upgrader_url){
			$theme	= $this->name;
	
			if(empty($transient->checked[$theme])){
				return $transient;
			}
			
			$remote	= get_transient('wpjam_theme_upgrade_'.$theme);

			if(false == $remote){
				$remote = wpjam_remote_request($this->upgrader_url);
		 
				if(!is_wp_error($remote)){
					set_transient('wpjam_theme_upgrade_'.$theme, $remote, HOUR_IN_SECONDS*12);
				}
			}

			if($remote && !is_wp_error($remote)){
				if(version_compare($transient->checked[$theme], $remote['new_version'], '<')){
					$transient->response[$theme]	= $remote;
				}
			}
		}
		
		return $transient;
	}
}

class_alias('WPJAM_Verify_TXT', 'WPJAM_VerifyTXT');