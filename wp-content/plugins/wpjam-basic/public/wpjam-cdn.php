<?php
class WPJAM_CDN{
	use WPJAM_Setting_Trait;

	private $cdn_name	= '';
	private $cdn_host	= '';
	private $local_host	= '';

	private function __construct(){
		$this->init('wpjam-cdn', true);

		$local	= $this->get_setting('local');

		$this->cdn_name		= $this->get_setting('cdn_name');
		$this->cdn_host		= untrailingslashit($this->get_setting('host') ?: site_url());
		$this->local_host	= untrailingslashit($local ? set_url_scheme($local): site_url());

		// 兼容代码，以后可以去掉
		define('CDN_NAME',		$this->cdn_name);
		define('CDN_HOST',		$this->cdn_host);
		define('LOCAL_HOST',	$this->local_host);
	}

	public function host_replace($html, $to_cdn=true){
		$local_hosts	= $this->get_setting('locals') ?: [];

		if($to_cdn){
			$local_hosts[]	= str_replace('https://', 'http://', $this->local_host);
			$local_hosts[]	= str_replace('http://', 'https://', $this->local_host);

			if(strpos($this->cdn_host, 'http://') === 0){
				$local_hosts[]	= str_replace('http://', 'https://', $this->cdn_host);
			}
		}else{
			if(strpos($this->local_host, 'https://') !== false){
				$local_hosts[]	= str_replace('https://', 'http://', $this->local_host);
			}else{
				$local_hosts[]	= str_replace('http://', 'https://', $this->local_host);
			}
		}

		$local_hosts	= apply_filters('wpjam_cdn_local_hosts', $local_hosts);
		$local_hosts	= array_map('untrailingslashit', array_unique($local_hosts));

		if($to_cdn){
			return str_replace($local_hosts, $this->cdn_host, $html);
		}else{
			return str_replace($local_hosts, $this->local_host, $html);
		}
	}

	public function html_replace($html){
		if($exts = $this->get_setting('exts')){
			$html	= $this->host_replace($html, false);

			$exts	= is_array($exts) ? $exts : explode('|', $exts);
			$exts	= array_unique(array_filter(array_map('trim', $exts)));

			if(is_login()){
				$exts	= array_diff($exts, ['js','css']);
			}

			$exts	= implode('|', $exts);

			if($dirs = $this->get_setting('dirs')){
				$dirs	= is_array($dirs) ? $dirs : explode('|', $dirs);
				$dirs	= array_unique(array_filter(array_map('trim', $dirs)));
				$dirs	= implode('|', $dirs);
			}

			if($no_http = $this->get_setting('no_http')){
				$local_host_no_http	= str_replace(['http://', 'https://'], '//', $this->local_host);
			}

			if($dirs){
				$dirs	= str_replace(['-','/'],['\-','\/'], $dirs);
				$regex	= '/'.str_replace('/','\/',$this->local_host).'\/(('.$dirs.')\/[^\s\?\\\'\"\;\>\<]{1,}.('.$exts.'))([\"\\\'\)\s\]\?]{1})/';
				$html	= preg_replace($regex, $this->cdn_host.'/$1$4', $html);

				if($no_http){
					$regex	= '/'.str_replace('/','\/',$local_host_no_http).'\/(('.$dirs.')\/[^\s\?\\\'\"\;\>\<]{1,}.('.$exts.'))([\"\\\'\)\s\]\?]{1})/';
					$html	= preg_replace($regex, $this->cdn_host.'/$1$4', $html);
				}
			}else{
				$regex	= '/'.str_replace('/','\/',$this->local_host).'\/([^\s\?\\\'\"\;\>\<]{1,}.('.$exts.'))([\"\\\'\)\s\]\?]{1})/';
				$html	= preg_replace($regex, $this->cdn_host.'/$1$3', $html);

				if($no_http){
					$regex	= '/'.str_replace('/','\/',$local_host_no_http).'\/([^\s\?\\\'\"\;\>\<]{1,}.('.$exts.'))([\"\\\'\)\s\]\?]{1})/';
					$html	= preg_replace($regex, $this->cdn_host.'/$1$3', $html);
				}
			}
		}

		return $html;
	}

	public function content_images($content, $max_width=null){
		if(false === strpos($content, '<img')){
			return $content;
		}

		if(!wpjam_is_json_request()){
			$content	= $this->host_replace($content, false);
		}

		if(is_null($max_width)){
			$max_width	= $this->get_setting('max_width', ($GLOBALS['content_width'] ?? 0));
			$max_width	= (int)apply_filters('wpjam_content_image_width', $max_width);
		}

		if($max_width){
			add_filter('wp_img_tag_add_srcset_and_sizes_attr', '__return_false');
			remove_filter('the_content', 'wp_filter_content_tags');
		}

		if(!preg_match_all('/<img.*?src=[\'"](.*?)[\'"].*?>/i', $content, $matches)){
			return $content;
		}

		$ratio	= 2;
		$search	= $replace = [];

		foreach($matches[0] as $i => $img_tag){
			$img_url	= $matches[1][$i];

		 	if(empty($img_url) || $this->is_remote_image($img_url, false)){
		 		continue;
		 	}

			$size	= ['width'=>0, 'height'=>0, 'content'=>true];

			if(preg_match_all('/(width|height)=[\'"]([0-9]+)[\'"]/i', $img_tag, $hw_matches)){
				$hw_arr	= array_flip($hw_matches[1]);
				$size	= array_merge($size, array_combine($hw_matches[1], $hw_matches[2]));
			}

			$width	= $size['width'];

			$img_serach	= $img_replace = [];

			if($max_width){
				if($size['width'] >= $max_width){
					if($size['height']){
						$size['height']	= (int)(($max_width/$size['width'])*$size['height']);

						$img_serach[]	= $hw_matches[0][$hw_arr['height']];
						$img_replace[]	= 'height="'.$size['height'].'"';
					}

					$size['width']	= $max_width;

					$img_serach[]	= $hw_matches[0][$hw_arr['width']];
					$img_replace[]	= 'width="'.$size['width'].'"';
				}elseif($size['width'] == 0){
					if($size['height'] == 0){
						$size['width']	= $max_width;
					}
				}
			}

			$img_serach[]	= $img_url;

			if(strpos($img_tag, 'size-full ') && (empty($max_width) || $max_width*$ratio >= $width)){
				$img_replace[]	= wpjam_get_thumbnail($img_url, ['content'=>true]);
			}else{
				$size			= wpjam_parse_size($size, $ratio);
				$img_replace[]	= wpjam_get_thumbnail($img_url, $size);
			}

			if(function_exists('wp_lazy_loading_enabled')){
				$add_loading_attr	= wp_lazy_loading_enabled('img', current_filter());

				if($add_loading_attr && false === strpos($img_tag, ' loading=')) {
					$img_serach[]	= '<img';
					$img_replace[]	= '<img loading="lazy"';
				}
			}

			$search[]	= $img_tag;
			$replace[]	= str_replace($img_serach, $img_replace, $img_tag);
		}

		if(!$search){
			return $content;
		}

		return str_replace($search, $replace, $content);
	}

	public function fetch_remote_images($content){
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $content;
		}

		if(get_current_screen()->base != 'post'){
			return $content;
		}

		if(!preg_match_all('/<img.*?src=\\\\[\'"](.*?)\\\\[\'"].*?>/i', $content, $matches)){
			return $content;
		}

		$update		= false;
		$search		= $replace	= [];
		$img_urls	= array_unique($matches[1]);
		$img_tags	= $matches[0];
		$exceptions	= $this->get_setting('exceptions');
		$exceptions	= $exceptions ? explode("\n", $exceptions) : [];
		$exceptions	= array_unique(array_filter(array_map('trim', $exceptions)));

		foreach($img_urls as $i => $img_url){
			if(empty($img_url) || !$this->is_remote_image($img_url, false)){
				continue;
			}

			foreach($exceptions as $exception){
				if(strpos($img_url, trim($exception)) !== false){
					continue 2;
				}
			}

			$attachment_id	= wpjam_download_image($img_url, '', true);

			if(!is_wp_error($attachment_id)){
				$search[]	= $img_url;
				$replace[]	= wp_get_attachment_url($attachment_id);
				$update		= true;
			}
		}

		if($update){
			if(is_multisite()){
				setcookie('wp-saving-post', $_POST['post_ID'].'-saved', time()+DAY_IN_SECONDS, ADMIN_COOKIE_PATH, false, is_ssl());
			}

			$content	= str_replace($search, $replace, $content);
		}

		return $content;
	}

	public function is_remote_image($img_url, $strict=true){
		if($strict){
			$status	= strpos($img_url, $this->cdn_host) === false;	
		}else{
			$status	= strpos($this->host_replace($img_url), $this->cdn_host) === false;
		}

		return apply_filters('wpjam_is_remote_image', $status, $img_url);
	}

	public function on_current_screen($current_screen){
		if(!$current_screen->is_block_editor()){
			add_filter('content_save_pre', [$this, 'fetch_remote_images']);
		}
	}

	public function filter_html($html){
		if(empty($this->cdn_name) && $this->get_setting('disabled')){
			return $this->host_replace($html, false);
		}else{
			return $this->html_replace($html);
		}
	}

	public function filter_content($content){
		if(doing_filter('get_the_excerpt')){
			return $content;
		}

		return $this->content_images($content);
	}

	public function filter_thumbnail($url){
		return $this->host_replace($url);
	}

	public function filter_intermediate_image_sizes_advanced($sizes){
		return isset($sizes['full']) ? ['full'=>$sizes['full']] : [];
	}

	public function filter_attachment_url($url, $id){
		if(wp_attachment_is_image($id)){
			return $this->host_replace($url);
		}

		return $url;
	}

	public function filter_image_downsize($out, $id, $size){
		if(wp_attachment_is_image($id)){
			$meta	= wp_get_attachment_metadata($id);
			
			if(is_array($meta) && isset($meta['width'], $meta['height'])){
				$ratio	= 2;
				$size	= wpjam_parse_size($size, $ratio);

				if($size['crop']){
					$width	= min($size['width'],	$meta['width']);
					$height	= min($size['height'],	$meta['height']);
				}else{
					list($width, $height)	= wp_constrain_dimensions($meta['width'], $meta['height'], $size['width'], $size['height']);
				}

				$img_url	= wp_get_attachment_url($id);

				if($width < $meta['width'] || $height <  $meta['height']){
					$img_url	= wpjam_get_thumbnail($img_url, compact('width', 'height'));
					$out		= [$img_url, (int)($width/$ratio), (int)($height/$ratio), true];
				}else{
					$img_url	= wpjam_get_thumbnail($img_url);
					$out		= [$img_url, $width, $height, false];
				}
			}	
		}

		return $out;
	}

	public function filter_wp_resource_hints($urls, $relation_type){
		return $relation_type == 'dns-prefetch' ? $urls+[$this->cdn_host] : $urls;
	}

	public static function filter_option_value($value){
		foreach (['exts', 'dirs'] as $k) {
			$v	= $value[$k] ?? [];

			if($v){
				if(!is_array($v)){
					$v	= explode('|', $v);
				}

				$v = array_unique(array_filter(array_map('trim', $v)));
			}

			$value[$k]	= $v;
		};

		return $value;
	}

	public static function get_show_if($compare='=', $value=''){
		return ['key'=>'cdn_name', 'compare'=>$compare, 'value'=>$value];
	}

	public static function load_option_page($plugin_page){
		$detail = '阿里云 OSS 用户：请点击这里注册和申请<a href="http://wpjam.com/go/aliyun/" target="_blank">阿里云</a>可获得代金券，阿里云OSS<strong><a href="https://blog.wpjam.com/m/aliyun-oss-cdn/" target="_blank">详细使用指南</a></strong>。
		腾讯云 COS 用户：请点击这里注册和申请<a href="http://wpjam.com/go/qcloud/" target="_blank">腾讯云</a>可获得优惠券，腾讯云COS<strong><a href="https://blog.wpjam.com/m/qcloud-cos-cdn/" target="_blank">详细使用指南</a></strong>。';

		$options	= array_merge([''=>' '], wp_list_pluck(WPJAM_CDN_Type::get_by(), 'title'));

		$cdn_fields		= [
			'guide'		=> ['title'=>'使用说明',		'type'=>'view',		'value'=>wpautop($detail)],
			'cdn_name'	=> ['title'=>'云存储',		'type'=>'select',	'options'=>$options,	'class'=>'show-if-key'],
			'host'		=> ['title'=>'CDN 域名',		'type'=>'url',		'show_if'=>self::get_show_if('!=',''),	'description'=>'设置为在CDN云存储绑定的域名。'],
			'disabled'	=> ['title'=>'使用本站',		'type'=>'checkbox',	'show_if'=>self::get_show_if('=',''),	'description'=>'如使用 CDN 之后切换回使用本站图片，请勾选该选项，并将原 CDN 域名填回「本地设置」的「额外域名」中。'],
			'image'		=> ['title'=>'图片处理',		'type'=>'checkbox',	'show_if'=>self::get_show_if('IN', ['aliyun_oss', 'qcloud_cos', 'qiniu']),	'value'=>1,	'description'=>'开启云存储图片处理功能，使用云存储进行裁图、添加水印等操作。<br />&emsp;* 注意：开启云存储图片处理功能，文章和媒体库中的所有图片都会镜像到云存储。'],
		];

		$local_fields	= [
			'local'		=> ['title'=>'本地域名',		'type'=>'url',		'value'=>home_url(),	'description'=>'将该域名填入<strong>云存储的镜像源</strong>。'],
			'no_http'	=> ['title'=>'无HTTP替换',	'type'=>'checkbox',	'show_if'=>self::get_show_if('!=',''),	'description'=>'将无<code>http://</code>或<code>https://</code>的静态资源也进行镜像处理'],
			'exts'		=> ['title'=>'扩展名',		'type'=>'mu-text',	'value'=>['png','jpg','gif','ico'],		'class'=>'',	'description'=>'设置要镜像的静态文件的扩展名。'],
			'dirs'		=> ['title'=>'目录',			'type'=>'mu-text',	'value'=>['wp-content','wp-includes'],	'class'=>'',	'description'=>'设置要镜像的静态文件所在的目录。'],
			'locals'	=> ['title'=>'额外域名',		'type'=>'mu-text',	'item_type'=>'url'],
		];

		$image_fields	= [
			'thumbnail_set'	=> ['title'=>'缩图设置',	'type'=>'fieldset',	'fields'=>[
				'no_subsizes'	=> ['type'=>'checkbox',	'value'=>1,	'description'=>'云存储有更强大的缩图功能，本地不用再生成缩略图。'],
				'thumbnail'		=> ['type'=>'checkbox',	'value'=>1,	'description'=>'使用云存储缩图功能对文章内容中的图片进行最佳尺寸显示处理。'],
				'max_view'		=> ['type'=>'view',		'group'=>'max',	'show_if'=>['key'=>'thumbnail', 'value'=>1],	'value'=>'文章中图片最大宽度：'],
				'max_width'		=> ['type'=>'number',	'group'=>'max',	'show_if'=>['key'=>'thumbnail', 'value'=>1],	'value'=>($GLOBALS['content_width'] ?? 0),	'class'=>'small-text',	'description'=>'px。']
			]],
			'image_set'		=> ['title'=>'格式质量',	'type'=>'fieldset',	'fields'=>[
				'webp'			=> ['type'=>'checkbox',	'description'=>'将图片转换成WebP格式，仅支持阿里云OSS和腾讯云COS。'],
				'interlace'		=> ['type'=>'checkbox',	'description'=>'JPEG格式图片渐进显示。'],
				'quality_view'	=> ['type'=>'view',		'group'=>'quality',	'value'=>'图片质量：'],
				'quality'		=> ['type'=>'number',	'group'=>'quality',	'class'=>'small-text',	'mim'=>0,	'max'=>100]
			]],
		];

		$watermark_options = [
			'SouthEast'	=> '右下角',
			'SouthWest'	=> '左下角',
			'NorthEast'	=> '右上角',
			'NorthWest'	=> '左上角',
			'Center'	=> '正中间',
			'West'		=> '左中间',
			'East'		=> '右中间',
			'North'		=> '上中间',
			'South'		=> '下中间',
		];

		$watermark_fields = [
			'watermark'	=> ['title'=>'水印图片',	'type'=>'image',	'description'=>'请使用云存储域名下的图片'],
			'disslove'	=> ['title'=>'透明度',	'type'=>'number',	'class'=>'small-text',	'description'=>'取值范围：1-100，默认为100（不透明）',	'min'=>0,	'max'=>100],
			'set'		=> ['title'=>'位置边距',	'type'=>'fieldset',	'fields'=>[
				'gravity_v'	=> ['type'=>'view',		'group'=>'gravity',	'value'=>'水印位置：'],
				'gravity'	=> ['type'=>'select',	'group'=>'gravity',	'options'=>$watermark_options],
				'dx_v'		=> ['type'=>'view',		'group'=>'dx',		'value'=>'横轴边距：'],
				'dx'		=> ['type'=>'number',	'group'=>'dx',		'class'=>'small-text',	'value'=>10,	'description'=>'px'],
				'dy_v'		=> ['type'=>'view',		'group'=>'dy',		'value'=>'纵轴边距：'],
				'dy'		=> ['type'=>'number',	'group'=>'dy',		'class'=>'small-text',	'value'=>10,	'description'=>'px']
			]],
		];

		if(is_network_admin()){
			unset($local_fields['local']);
			unset($watermark_fields['watermark']);
		}

		$remote_options	= [
			''			=>'关闭远程图片镜像到云存储',
			'download'	=>'将远程图片下载到服务器再镜像到云存储',
			'1'			=>'自动将远程图片镜像到云存储（不推荐）'
		];

		if(is_multisite() || !$GLOBALS['wp_rewrite']->using_mod_rewrite_permalinks() || !extension_loaded('gd')){
			unset($remote_options[1]);
		}

		$remote_fields	= [
			'remote'		=> ['title'=>'远程图片',	'type'=>'select',	'options'=>$remote_options],
			'exceptions'	=> ['title'=>'例外',		'type'=>'textarea',	'class'=>'regular-text','description'=>'如果远程图片的链接中包含以上字符串或域名，就不会被保存并镜像到云存储。']
		];

		$remote_summary	= '
		*自动将远程图片镜像到云存储需要博客支持固定链接和服务器支持GD库（不支持gif图片）。
		*将远程图片下载服务器再镜像到云存储，会在你保存文章的时候自动执行。
		*古腾堡编辑器已自带上传外部图片的功能，如使用，在模块工具栏点击一下上传按钮。';

		$sections	= [
			'cdn'		=> ['title'=>'云存储设置',	'fields'=>$cdn_fields],
			'local'		=> ['title'=>'本地设置',		'fields'=>$local_fields],
			'image'		=> ['title'=>'图片设置',		'fields'=>$image_fields,	'show_if'=>['key'=>'image', 'compare'=>'=', 'value'=>1]],
			'watermark'	=> ['title'=>'水印设置',		'fields'=>$watermark_fields,'show_if'=>['key'=>'image', 'compare'=>'=', 'value'=>1]],
			'remote'	=> ['title'=>'远程图片',		'fields'=>$remote_fields,	'show_if'=>self::get_show_if('!=', ''),	'summary'=>$remote_summary],
		];

		wpjam_register_option('wpjam-cdn', [
			'sections'		=> $sections, 
			'site_default'	=> true,
			'summary'		=>'CDN 加速使用云存储对博客的静态资源进行 CDN 加速，详细介绍请点击：<a href="https://blog.wpjam.com/m/wpjam-basic-cdn/" target="_blank">CDN 加速</a>。'
		]);

		add_filter('option_wpjam-cdn', [self::class, 'filter_option_value']);
	}
}

class WPJAM_CDN_Type{
	use WPJAM_Register_Trait;
}

//注册 CDN 服务
function wpjam_register_cdn($name, $args){
	WPJAM_CDN_Type::register($name, $args);
}

function wpjam_unregister_cdn($name){
	WPJAM_CDN_Type::unregister($name);
}

// 获取 CDN 设置
function wpjam_get_cdn_setting($name, $default=null){
	return WPJAM_CDN::get_instance()->get_setting($name,$default);
}

function wpjam_cdn_get_setting($name, $default=null){
	return wpjam_get_cdn_setting($name, $default);
}

function wpjam_is_image($image_url){
	$ext_types	= wp_get_ext_types();
	$img_exts	= $ext_types['image'];

	$image_parts	= explode('?', $image_url);

	return preg_match('/\.('.implode('|', $img_exts).')$/i', $image_parts[0]);
}

function wpjam_is_remote_image($img_url, $strict=true){
	return WPJAM_CDN::get_instance()->is_remote_image($img_url, $strict);
}

function wpjam_cdn_host_replace($html, $to_cdn=true){
	return WPJAM_CDN::get_instance()->host_replace($html, $to_cdn);
}

wpjam_register_cdn('aliyun_oss',	['title'=>'阿里云OSS',	'file'=>WPJAM_BASIC_PLUGIN_DIR.'cdn/aliyun_oss.php']);
wpjam_register_cdn('qcloud_cos',	['title'=>'腾讯云COS',	'file'=>WPJAM_BASIC_PLUGIN_DIR.'cdn/qcloud_cos.php']);
wpjam_register_cdn('ucloud',		['title'=>'UCloud', 	'file'=>WPJAM_BASIC_PLUGIN_DIR.'cdn/ucloud.php']);
wpjam_register_cdn('qiniu',			['title'=>'七牛云存储',	'file'=>WPJAM_BASIC_PLUGIN_DIR.'cdn/qiniu.php']);

add_action('plugins_loaded', function(){
	$instance	= WPJAM_CDN::get_instance();

	if($cdn_name = $instance->get_setting('cdn_name')){
		do_action('wpjam_cdn_loaded');

		if(!is_admin()){
			if(wpjam_is_json_request()){
				add_filter('the_content',	[$instance, 'filter_html'], 5);
			}else{
				add_filter('wpjam_html',	[$instance, 'filter_html'], 9);
			}
		}

		add_filter('wp_resource_hints',		[$instance, 'filter_wp_resource_hints'], 10, 2);

		if($instance->get_setting('image', 1)){
			$type_obj	= WPJAM_CDN_Type::get($cdn_name);
			$cdn_file	= $type_obj ? $type_obj->file : '';

			if($cdn_file && file_exists($cdn_file)){
				include $cdn_file;
			}

			if($instance->get_setting('no_subsizes', 1)){
				add_filter('intermediate_image_sizes_advanced',	[$instance, 'filter_intermediate_image_sizes_advanced']);
			}

			if($instance->get_setting('thumbnail', 1)){
				add_filter('the_content',		[$instance, 'filter_content'], 5);
			}

			add_filter('wpjam_thumbnail',		[$instance, 'filter_thumbnail'], 1);
			add_filter('wp_get_attachment_url',	[$instance, 'filter_attachment_url'], 10, 2);
			// add_filter('upload_dir',			[$instance, 'filter_upload_dir']);
			add_filter('image_downsize',		[$instance, 'filter_image_downsize'], 10 ,3);
		}

		if(wpjam_cdn_get_setting('remote') === 'download'){
			if(is_admin()){
				add_action('current_screen',	[$instance, 'on_current_screen']);
			}
		}elseif(wpjam_cdn_get_setting('remote')){
			if(!is_multisite()){
				include WPJAM_BASIC_PLUGIN_DIR.'cdn/remote.php';
			}
		}
	}else{
		if($instance->get_setting('disabled')){
			if(!is_admin() && !wpjam_is_json_request()){
				add_filter('wpjam_html',	[$instance, 'filter_html'], 9);
			}

			add_filter('the_content',		[$instance, 'filter_html'], 5);
			add_filter('wpjam_thumbnail',	[$instance, 'filter_html'], 9);
		}
	}
}, 99);