<?php
class WPJAM_Basic{
	use WPJAM_Setting_Trait;

	private $extends	= [];

	private function __construct(){
		$this->init('wpjam-basic', true);

		$extends	= wpjam_get_option('wpjam-extends');
		$extends	= array_filter($extends);

		if(is_multisite()){
			$sitewide_extends	= wpjam_get_site_option('wpjam-extends');
			$sitewide_extends	= array_filter($sitewide_extends);

			$extends	= array_merge($extends, $sitewide_extends);
		}

		$this->extends	= $extends;
	}

	public function get_extends(){
		return $this->extends;
	}

	public function has_extend($extend){
		$extend	= rtrim($extend, '.php').'.php';

		return isset($this->extends[$extend]);
	}

	public static $sub_pages	= [];

	public static function add_sub_page($sub_slug, $args=[]){
		self::$sub_pages[$sub_slug]	= $args;
	}

	public static function get_defaults(){
		return [
			'disable_revision'			=> 1,
			'disable_trackbacks'		=> 1,
			'disable_emoji'				=> 1,
			'disable_texturize'			=> 1,
			'disable_privacy'			=> 1,

			'remove_head_links'			=> 1,
			'remove_capital_P_dangit'	=> 1,

			'admin_footer'				=> '<span id="footer-thankyou">感谢使用<a href="https://cn.wordpress.org/" target="_blank">WordPress</a>进行创作。</span> | <a href="https://wpjam.com/" title="WordPress JAM" target="_blank">WordPress JAM</a>'
		];
	}

	public static function load_extends(){
		foreach(self::get_instance()->get_extends() as $extend_file => $dummy){
			if(is_file(WPJAM_BASIC_PLUGIN_DIR.'extends/'.$extend_file)){
				include WPJAM_BASIC_PLUGIN_DIR.'extends/'.$extend_file;
			}
		}
	}

	public static function load_template_extends(){
		$extend_dir	= get_template_directory().'/extends';

		if(is_dir($extend_dir) && ($extend_handle = opendir($extend_dir))){
			while(($extend = readdir($extend_handle)) !== false){
				if ($extend == '.' || $extend == '..' || is_file($extend_dir.'/'.$extend)) {
					continue;
				}

				if(is_file($extend_dir.'/'.$extend.'/'.$extend.'.php')){
					include $extend_dir.'/'.$extend.'/'.$extend.'.php';
				}
			}

			closedir($extend_handle);
		}
	}

	public static function do_active_callbacks(){
		if($actives = get_option('wpjam-actives', [])){
			foreach($actives as $active){
				if(is_array($active) && isset($active['hook'])){
					add_action($active['hook'], $active['callback']);
				}else{
					add_action('wp_loaded', $active);
				}
			}

			update_option('wpjam-actives', []);
		}
	}
}

class WPJAM_Gravatar_Service{
	use WPJAM_Register_Trait;

	public static function get_options(){
		$options	= wp_list_pluck(self::get_registereds(), 'title');

		return [''=>'默认服务']+preg_filter('/$/', '加速服务', $options)+['custom'=>'自定义加速服务'];
	}

	public static function filter_pre_data($args, $id_or_email){
		$email_hash	= $email = $user = false;
		
		if(is_object($id_or_email) && isset($id_or_email->comment_ID)){
			$id_or_email	= get_comment($id_or_email);
		}

		if(is_numeric($id_or_email)){
			$user	= get_user_by('id', absint($id_or_email));
		}elseif($id_or_email instanceof WP_User){
			$user	= $id_or_email;
		}elseif($id_or_email instanceof WP_Post){
			if($id_or_email->post_author){
				$user	= get_user_by('id', (int)$id_or_email->post_author);
			}
		}elseif($id_or_email instanceof WP_Comment){
			if($avatar = get_comment_meta($id_or_email->comment_ID, 'avatarurl', true)){
				return array_merge($args, [
					'url'			=> wpjam_get_thumbnail($avatar, [$args['width'], $args['height']]),
					'found_avatar'	=> true
				]);
			}

			if($id_or_email->user_id){
				$user	= get_user_by('id', (int)$id_or_email->user_id);
			}elseif($id_or_email->comment_author_email){
				$email	= $id_or_email->comment_author_email;
			}
		}elseif(is_string($id_or_email)){
			if(strpos($id_or_email, '@md5.gravatar.com')){
				list($email_hash)	= explode('@', $id_or_email);
			}else{
				$email	= $id_or_email;
			}
		}

		if($user){
			if($avatar = get_user_meta($user->ID, 'avatarurl', true)){
				return array_merge($args, [
					'url'			=> wpjam_get_thumbnail($avatar, [$args['width'], $args['height']]),
					'found_avatar'	=> true
				]);
			}

			$args	= apply_filters('wpjam_default_avatar_data', $args, $user->ID);

			if($args['found_avatar']){
				return $args;
			}

			$email = $user->user_email;
		}

		if(!$email_hash && $email){
			$email_hash = md5(strtolower(trim($email)));
		}

		if($email_hash){
			$url	= 'https://cn.gravatar.com/avatar/';

			if($name = wpjam_basic_get_setting('gravatar')){
				if($name == 'custom'){
					if($custom = wpjam_basic_get_setting('gravatar_custom')){
						$url	= $custom;
					}
				}else{
					if($object = self::get($name)){
						$url	= $object->url;
					}
				}
			}

			$url	= set_url_scheme($url.$email_hash, $args['scheme']);
			$url	= add_query_arg(rawurlencode_deep(array_filter([
				's'	=> $args['size'],
				'd'	=> $args['default'],
				'f'	=> $args['force_default'] ? 'y' : false,
				'r'	=> $args['rating'],
			])), $url);

			return array_merge($args, [
				'url'			=> apply_filters('get_avatar_url', $url, $id_or_email, $args),
				'found_avatar'	=> true
			]);
		}

		return $args;
	}
}

class WPJAM_Google_Font_Service{
	use WPJAM_Register_Trait;

	public static function get_options(){
		$options	= wp_list_pluck(self::get_registereds(), 'title');

		return [''=>'默认服务']+preg_filter('/$/', '加速服务', $options)+['custom'=>'自定义加速服务'];
	}

	public static function get_search(){
		return [
			'googleapis_fonts'			=> 'fonts.googleapis.com', 
			'googleapis_ajax'			=> 'ajax.googleapis.com',
			'googleusercontent_themes'	=> 'themes.googleusercontent.com',
			'gstatic_fonts'				=> 'fonts.gstatic.com'
		];
	}

	public static function filter_html($html){
		if($name = wpjam_basic_get_setting('google_fonts')){
			$search	= $replace = [];

			if($name == 'custom'){
				foreach(self::get_search() as $font_key => $domain){
					if($mirror = wpjam_basic_get_setting($font_key)){
						$search[]	= '//'.$domain;
						$replace[]	= str_replace(['http://','https://'], '//', $mirror);
					}
				}
			}elseif($object	= self::get($name)){
				$search		= preg_filter('/^/', '//', array_values(self::get_search()));
				$replace	= $object->replace;
			}

			$html	= $search ? str_replace($search, $replace, $html) : $html;
		}

		return $html;
	}
}

function wpjam_basic_get_setting($name, $default=null){
	return WPJAM_Basic::get_instance()->get_setting($name, $default);
}

function wpjam_basic_update_setting($name, $value){
	return WPJAM_Basic::get_instance()->update_setting($name, $value);
}

function wpjam_basic_delete_setting($name){
	return WPJAM_Basic::get_instance()->delete_setting($name);
}

function wpjam_basic_get_default_settings(){
	return WPJAM_Basic::get_defaults();
}

function wpjam_has_extend($extend){
	return WPJAM_Basic::get_instance()->has_extend($extend);
}

function wpjam_add_basic_sub_page($sub_slug, $args=[]){
	WPJAM_Basic::add_sub_page($sub_slug, $args);
}

function wpjam_register_google_font_services($name, $args){
	return WPJAM_Google_Font_Service::register($name, $args);
}

function wpjam_register_gravatar_services($name, $args){
	return WPJAM_Gravatar_Service::register($name, $args);
}

add_action('plugins_loaded', function(){
	WPJAM_Basic::load_template_extends();
	WPJAM_Basic::do_active_callbacks();
}, 0);

add_action('wpjam_loaded',	function(){
	WPJAM_Basic::load_extends();

	wpjam_register_gravatar_services('cravatar',	['title'=>'Cravatar',	'url'=>'https://cravatar.cn/avatar/']);
	wpjam_register_gravatar_services('geekzu',		['title'=>'极客族',		'url'=>'https://sdn.geekzu.org/avatar/']);
	wpjam_register_gravatar_services('loli',		['title'=>'loli',		'url'=>'https://gravatar.loli.net/avatar/']);
	wpjam_register_gravatar_services('v2ex',		['title'=>'v2ex',		'url'=>'https://cdn.v2ex.com/gravatar/']);

	wpjam_register_google_font_services('geekzu',	[
		'title'		=> '极客族',	
		'replace'	=> [
			'//fonts.geekzu.org',
			'//gapis.geekzu.org/ajax',
			'//gapis.geekzu.org/g-themes',
			'//gapis.geekzu.org/g-fonts'
		]
	]);

	wpjam_register_google_font_services('loli',	[
		'title'		=> 'loli',
		'replace'	=> [
			'//fonts.loli.net',
			'//ajax.loli.net',
			'//themes.loli.net',
			'//gstatic.loli.net'
		]
	]);

	wpjam_register_google_font_services('ustc',	[
		'title'		=> '中科大',
		'replace'	=> [
			'//fonts.lug.ustc.edu.cn',
			'//ajax.lug.ustc.edu.cn',
			'//google-themes.lug.ustc.edu.cn',
			'//fonts-gstatic.lug.ustc.edu.cn'
		]
	]);

	add_filter('default_option_wpjam-basic',	['WPJAM_Basic', 'get_defaults']);

	add_filter('pre_get_avatar_data',	['WPJAM_Gravatar_Service', 'filter_pre_data'], 10, 2);
	add_filter('wpjam_html',			['WPJAM_Google_Font_Service', 'filter_html'], 10, 2);
});