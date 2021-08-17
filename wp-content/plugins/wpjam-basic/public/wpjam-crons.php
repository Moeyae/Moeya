<?php
if(is_admin() && did_action('wpjam_plugin_page_load') && $GLOBALS['plugin_page'] = 'wpjam-crons'){
	class WPJAM_Crons_Admin{
		public static function get_primary_key(){
			return 'cron_id';
		}

		public static function get($id){
			list($timestamp, $hook, $key)	= explode('--', $id);

			$wp_crons = _get_cron_array();

			if(isset($wp_crons[$timestamp][$hook][$key])){
				$data	= $wp_crons[$timestamp][$hook][$key];

				$data['hook']		= $hook;
				$data['timestamp']	= $timestamp;
				$data['time']		= get_date_from_gmt(date('Y-m-d H:i:s', $timestamp));
				$data['cron_id']	= $id;
				$data['interval']	= $data['interval'] ?? 0;

				return $data;
			}else{
				return new WP_Error('cron_not_exist', '该定时作业不存在');
			}
		}

		public static function insert($data){
			if(!has_filter($data['hook'])){
				return new WP_Error('invalid_hook', '非法 hook');
			}

			$timestamp	= strtotime(get_gmt_from_date($data['time']));

			if($data['interval']){
				wp_schedule_event($timestamp, $data['interval'], $data['hook'], $data['_args']);
			}else{
				wp_schedule_single_event($timestamp, $data['hook'], $data['_args']);
			}

			return true;
		}

		public static function do($id){
			$data = self::get($id);

			if(is_wp_error($data)){
				return $data;
			}

			$result	= do_action_ref_array($data['hook'], $data['args']);

			if(is_wp_error($result)){
				return $result;
			}else{
				return true;
			}
		}

		public static function delete($id){
			$data = self::get($id);

			if(is_wp_error($data)){
				return $data;
			}

			return wp_unschedule_event($data['timestamp'], $data['hook'], $data['args']);
		}

		public static function query_items($limit, $offset){
			$items	= [];

			foreach (_get_cron_array() as $timestamp => $wp_cron) {
				foreach ($wp_cron as $hook => $dings) {
					foreach($dings as $key=>$data) {
						if(!has_filter($hook)){
							wp_unschedule_event($timestamp, $hook, $data['args']);	// 系统不存在的定时作业，自动清理
							continue;
						}

						$schedule	= $schedules[$data['schedule']] ?? $data['interval']??'';
						// $args	= $data['args'] ? '('.implode(',', $data['args']).')' : '';

						$items[] = [
							'cron_id'	=> $timestamp.'--'.$hook.'--'.$key,
							'time'		=> get_date_from_gmt( date('Y-m-d H:i:s', $timestamp) ),
							// 'hook'		=> $hook.$args,
							'hook'		=> $hook,
							'interval'	=> $data['interval'] ?? 0
						];
					}
				}
			}

			return ['items'=>$items, 'total'=>count($items)];
		}

		public static function get_actions(){
			return [
				'add'		=> ['title'=>'新建',		'response'=>'list'],
				'do'		=> ['title'=>'立即执行',	'direct'=>true,	'response'=>'list'],
				'delete'	=> ['title'=>'删除',		'direct'=>true,	'response'=>'list']
			];
		}

		public static function get_fields($action_key='', $id=0){
			$schedule_options	= [0=>'只执行一次']+wp_list_pluck(wp_get_schedules(), 'display', 'interval');

			return [
				'hook'		=> ['title'=>'Hook',	'type'=>'text',		'show_admin_column'=>true],
				// '_args'		=> ['title'=>'参数',		'type'=>'mu-text',	'show_admin_column'=>true],
				'time'		=> ['title'=>'运行时间',	'type'=>'text',		'show_admin_column'=>true,	'value'=>current_time('mysql')],
				'interval'	=> ['title'=>'频率',		'type'=>'select',	'show_admin_column'=>true,	'options'=>$schedule_options],
			];
		}
	}
}else{
	class WPJAM_Cron{
		private $hook;
		private $args;

		public function __construct($hook, $args){
			$this->hook	= $hook;
			$this->args	= wp_parse_args($args, ['recurrence'=>'', 'time'=>time(), 'args'=>[]]);
		}

		public function schedule(){
			$callback	= $this->args['callback'] ?? [$this, 'callback'];

			if(is_callable($callback)){
				add_action($this->hook, $callback);

				if(!$this->is_scheduled($this->hook)){
					if($this->args['recurrence']){
						wp_schedule_event($this->args['time'], $this->args['recurrence'], $this->hook, $this->args['args']);
					}else{
						wp_schedule_single_event($this->args['time'], $this->hook, $this->args['args']);
					}
				}
			}
		}

		public function callback(){
			if(!$this->is_lock() && ($callback = $this->get_callback())){
				$this->inc_counter();

				if(is_callable($callback)){
					call_user_func($callback);
				}else{
					trigger_error('invalid_job_callback'.var_export($callback, true));
				}
			}
		}

		public function get_callback(){
			$callbacks	= $this->get_callbacks();

			if($callbacks){
				$total	= count($callbacks);
				$index	= get_transient($this->hook.'_index') ?: 0;
				$next	= $index >= $total ? 0 : ($index + 1);

				set_transient($this->hook.'_index', $next, DAY_IN_SECONDS);

				return $callbacks[$index] ?? '';
			}else{
				return '';
			}
		}

		public function get_callbacks($jobs=null){
			if(is_null($jobs)){
				$jobs	= $this->args['jobs'];

				if($jobs && is_callable($jobs)){
					$jobs	= call_user_func($jobs);
				}
			}

			if(!empty($this->args['weight'])){
				$callbacks	= [];

				foreach($jobs as $i=> &$job){
					$job['weight']	= $job['weight'] ?? 1;
	 
					if($job['weight']){
						$callbacks[]	= $job['callback'];

						if($job['weight'] <= 1){
							unset($jobs[$i]);
						}else{
							$job['weight'] --;
						}
					}
				}

				if($jobs){
					$callbacks	= array_merge($callbacks, $this->get_callbacks($jobs)); 
				}

				return $callbacks;
			}else{
				return wp_list_pluck($jobs, 'callback');
			}
		}

		public function is_lock(){
			if(get_site_transient($this->hook.'_lock')){
				return true;
			}

			set_site_transient($this->hook.'_lock', 1, 5);

			return false;
		}

		public function get_counter(){
			$today	= date('Y-m-d', current_time('timestamp'));

			return get_transient($this->hook.'_counter:'.$today) ?: 0;
		}

		public function inc_counter(){
			$today		= date('Y-m-d', current_time('timestamp'));
			$counter	= get_transient($this->hook.'_counter:'.$today) ?: 0;
			set_transient($this->hook.'_counter:'.$today, ($counter+1), DAY_IN_SECONDS);
		}

		public static function is_scheduled($hook){
			if($crons = _get_cron_array()){
				foreach($crons as $timestamp => $cron){
					if(isset($cron[$hook])){
						return true;
					}
				}
			}

			return false;
		}

		public static function filter_schedules($schedules){
			return array_merge($schedules, [
				'five_minutes'		=> ['interval'=>300,	'display'=>'每5分钟一次'],
				'fifteen_minutes'	=> ['interval'=>900,	'display'=>'每15分钟一次'],
			]);
		}

		public static function filter_pre_option($pre){
			return get_transient('wpjam_crons') ?: $pre;
		}

		public static function filter_pre_update_option($value, $old_value){
			if(wp_doing_cron()){
				set_transient('wpjam_crons', $value, HOUR_IN_SECONDS*6);

				return $old_value;
			}else{
				delete_transient('wpjam_crons');

				return $value;
			}
		}

		public static function load_plugin_page(){
			wpjam_set_plugin_page_summary('定时作业让你可以可视化管理 WordPress 的定时作业，详细介绍请点击：<a href="https://blog.wpjam.com/m/wpjam-basic-cron-jobs/" target="_blank">定时作业</a>。');

			wpjam_register_plugin_page_tab('crons', [
				'title'		=> '定时作业',
				'function'	=> 'list',
				'plural'	=> 'crons',
				'singular'	=> 'cron',
				'model'		=> 'WPJAM_Crons_Admin',
				'order'		=> 20,
				'tab_file'	=> __FILE__
			]);
		}
	}

	class WPJAM_Job{
		private static $jobs	= [];
		private static $cron	= null;

		public static function init(){
			if(is_null(self::$cron)){
				self::$cron	= new WPJAM_Cron('wpjam_scheduled', [
					'recurrence'	=> wp_using_ext_object_cache() ? 'five_minutes' : 'fifteen_minutes',
					'jobs'			=> ['WPJAM_Job', 'get_jobs'],
					'weight'		=> true
				]);

				self::$cron->schedule();
			}
		}

		public static function register($callback, $args=[]){
			$args	= is_numeric($args) ? ['weight'=>$args] : $args;

			self::$jobs[]	= wp_parse_args($args, ['callback'=>$callback,	'weight'=>1,	'day'=>-1]);
		}

		public static function get_jobs($raw=false){
			if($raw){
				return self::$jobs;
			}

			return self::$jobs ? array_filter(self::$jobs, function($job){
				if($job['day'] == -1){
					return true;
				}else{
					$day	= (current_time('H') > 2 && current_time('H') < 6) ? 0 : 1;
					return $job['day']	== $day;
				}
			}) : [];
		}

		public static function get_counter(){
			return self::$cron->get_counter();
		}
	}

	function wpjam_register_cron($hook, $args=[]){
		if(is_callable($hook)){
			wpjam_register_job($hook, $args);
		}else{
			(new WPJAM_Cron($hook, $args))->schedule();
		}
	}

	function wpjam_register_job($callback, $args=[]){
		WPJAM_Job::register($callback, $args);
	}

	function wpjam_is_scheduled_event($hook) {	// 不用判断参数
		return WPJAM_Cron::is_scheduled($hook);
	}

	add_filter('cron_schedules',	['WPJAM_Cron', 'filter_schedules']);

	if(wp_using_ext_object_cache()){
		add_filter('pre_option_cron',			['WPJAM_Cron', 'filter_pre_option']);
		add_filter('pre_update_option_cron',	['WPJAM_Cron', 'filter_pre_update_option'], 10, 2);
	}

	add_action('init',	['WPJAM_Job', 'init']);
}