<?php
class WPJAM_Basic_Menu{
	public static function load_basic_page(){
		$url_prefix			= 'https://blog.wpjam.com/m/';
		$disabled_fields	= self::parse_fields([
			'disable_revision'			=>['title'=>'屏蔽文章修订',		'slug'=>'disable-post-revision',			'description'=>'屏蔽文章修订功能，精简文章表数据。'],
			'disable_trackbacks'		=>['title'=>'屏蔽Trackbacks',	'slug'=>'bye-bye-trackbacks',				'description'=>'彻底关闭Trackbacks，防止垃圾留言。'],
			'disable_emoji'				=>['title'=>'屏蔽Emoji图片',		'slug'=>'diable-emoji',						'description'=>'屏蔽Emoji图片转换功能，直接使用Emoji。'],
			'disable_texturize'			=>['title'=>'屏蔽字符转码',		'slug'=>'disable-wptexturize',				'description'=>'屏蔽字符换成格式化的HTML实体功能。'],
			'disable_feed'				=>['title'=>'屏蔽站点Feed',		'slug'=>'disable-feed',						'description'=>'屏蔽站点Feed，防止文章被快速被采集。'],
			'disable_admin_email_check'	=>['title'=>'屏蔽邮箱验证',		'slug'=>'disable-site-admin-email-check',	'description'=>'屏蔽站点管理员邮箱定期验证功能。'],
			'disable_auto_update'		=>['title'=>'屏蔽自动更新',		'slug'=>'disable-wordpress-auto-update',	'description'=>'关闭自动更新功能，通过手动或SSH方式更新。'],
			'disable_privacy'			=>['title'=>'屏蔽后台隐私',		'slug'=>'wordpress-remove-gdpr-pages',		'description'=>'移除为欧洲通用数据保护条例而生成的页面。'],
			'disable_autoembed'			=>['title'=>'屏蔽Auto Embeds',	'slug'=>'disable-auto-embeds-in-wordpress',	'description'=>'禁用Auto Embeds功能，加快页面解析速度。'],
			'disable_post_embed'		=>['title'=>'屏蔽文章Embed',		'slug'=>'disable-wordpress-post-embed',		'description'=>'屏蔽嵌入其他WordPress文章的Embed功能。'],
			'disable_block_editor'		=>['title'=>'屏蔽Gutenberg',		'slug'=>'disable-gutenberg',				'description'=>'屏蔽Gutenberg编辑器，换回经典编辑器。'],
			'disable_xml_rpc'			=>['title'=>'屏蔽XML-RPC',		'slug'=>'disable-xml-rpc',					'description'=>'关闭XML-RPC功能，只在后台发布文章。']
		]);

		$fonts_fields	= ['google_fonts'=>['title'=>'',	'type'=>'select',	'options'=>WPJAM_Google_Font_Service::get_options()]];

		foreach(WPJAM_Google_Font_Service::get_search() as $key => $domain){
			$fonts_fields[$key]	= ['title'=>'',	'type'=>'text',	'show_if'=>['key'=>'google_fonts','value'=>'custom'],	'placeholder'=>'请输入'.$domain.'加速服务地址'];
		}

		$fonts_fields['disable_google_fonts_4_block_editor']	= self::parse_field(['slug'=>'wordpress-disable-google-font-for-gutenberg',	'description'=>'禁止古腾堡编辑器加载 Google 字体。']);
		
		$speed_fields	= [
			'google_fonts_fieldset'	=>['title'=>'Google字体加速',	'type'=>'fieldset',	'fields'=>$fonts_fields],
			'gravatar_fieldset'		=>['title'=>'Gravatar加速',	'type'=>'fieldset',	'fields'=>[
				'gravatar'			=>['title'=>'',	'type'=>'select',	'options'=>WPJAM_Gravatar_Service::get_options()],
				'gravatar_custom'	=>['title'=>'',	'type'=>'text',		'show_if'=>['key'=>'gravatar','value'=>'custom'],	'placeholder'=>'请输入 Gravatar 加速服务地址']
			]],
			'frontend_optimization'	=>['title'=>'前端页面优化',	'type'=>'fieldset',	'fields'=>self::parse_fields([
				// 'locale'					=>['slug'=>'setup-different-admin-and-frontend-language-on-wordpress',	'description'=>'前台不加载语言包，前端加载加快0.1-0.5秒。'],
				'404_optimization'			=>['slug'=>'wpjam_redirect_guess_404_permalink',	'description'=>'改进404页面跳转到正确的页面的效率。'],
				'remove_head_links'			=>['slug'=>'remove-unnecessary-code-from-wp_head',	'description'=>'移除页面头部中无关紧要的代码。'],
				'remove_admin_bar'			=>['slug'=>'remove-wp-3-1-admin-bar',				'description'=>'移除工具栏和后台个人设置页面工具栏有关的选项。'],
				'remove_capital_P_dangit'	=>['slug'=>'remove-capital_p_dangit',				'description'=>'移除WordPress大小写修正，让用户自己决定怎么写。'],
			])],
			'backend_optimization'	=>['title'=>'后台界面优化',	'type'=>'fieldset',	'fields'=>self::parse_fields([
				'remove_help_tabs'			=>['slug'=>'wordpress-remove-help-tabs',		'description'=>'移除后台界面右上角的帮助。'],
				'remove_screen_options'		=>['slug'=>'wordpress-remove-screen-options',	'description'=>'移除后台界面右上角的选项。'],
			])]
		];

		$enhance_fields		= [
			'optimized_by_wpjam'	=>['title'=>'由WPJAM优化',	'type'=>'checkbox',	'description'=>'在网站底部显示：Optimized by WPJAM Basic。'],
			'timestamp_file_name'	=>['title'=>'图片时间戳',		'type'=>'checkbox',	'description'=>'<a target="_blank" href="'.$url_prefix.'add-timestamp-2-image-filename/">给上传的图片加上时间戳</a>，防止<a target="_blank" href="'.$url_prefix.'not-name-1-for-attachment/">大量的SQL查询</a>。'],
			'no_category_base_set'	=>['title'=>'简化分类链接',	'type'=>'fieldset',	'fields'=>[
				'no_category_base'		=>self::parse_field(['slug'=>'wordpress-no-category-base',	'description'=>'去掉分类目录链接中的category或者自定义分类的%taxonomy%。']),
				'no_category_base_for'	=>['title'=>'分类模式：',	'type'=>'select',	'options'=>array_column(get_taxonomies(['public'=>true,'hierarchical'=>true], 'objects'), 'label', 'name'),	'show_if'=>['key'=>'no_category_base','value'=>1]]
			]],
			'excerpt_fieldset'		=>['title'=>'强化文章摘要',	'type'=>'fieldset',	'fields'=>[
				'excerpt_optimization'	=>['title'=>'未设摘要：',	'type'=>'select',	'options'=>[0=>'WordPress 默认方式截取',1=>'按照中文最优方式截取',2=>'直接不显示摘要']],
				'excerpt_length'		=>['title'=>'摘要长度：',	'type'=>'number',	'value'=>200,	'show_if'=>['key'=>'excerpt_optimization', 'value'=>1],	'class'=>'small-text',	'description'=>'<a target="_blank" href="'.$url_prefix.'get_post_excerpt/">中文最优方式按照<strong>中文2个字节，英文1个字节</strong>算法从内容中截取</a>。']
			]],
			'x-frame-options'		=>['title'=>'Frame嵌入设置',	'type'=>'select',	'options'=>[''=>'所有网页', 'SAMEORIGIN'=>'只允许嵌入同域名下的网页', 'DENY'=>'不允许嵌入网页']]
		];

		if($GLOBALS['wp_rewrite']->use_verbose_page_rules){
			unset($enhance_fields['no_category_base_set']['fields']);

			$enhance_fields['no_category_base_set']['type']		= 'view';
			$enhance_fields['no_category_base_set']['value']	= '你的固定链接设置不能去掉分类目录链接中的 category 或者自定义分类的 %taxonomy%，请先修改固定链接设置。';
		}

		wpjam_register_option('wpjam-basic', [
			'site_default'		=>true,
			'sanitize_callback'	=>[self::class, 'sanitize_callback'],
			'summary'			=>'优化设置让你通过关闭一些不常用的功能来加快  WordPress 的加载，详细介绍请点击：<a href="https://blog.wpjam.com/m/wpjam-basic-optimization-setting/" target="_blank">优化设置</a>。',
			'sections'			=>[
				'disabled'	=>['title'=>'功能屏蔽',	'fields'=>$disabled_fields],
				'speed'		=>['title'=>'加速优化', 	'fields'=>$speed_fields],
				'enhance'	=>['title'=>'功能增强',	'fields'=>$enhance_fields],
			],
		]);
	}

	public static function parse_fields($fields){
		return array_map([self::class, 'parse_field'], $fields);
	}

	public static function parse_field($field){
		$field['type']	= $field['type'] ?? 'checkbox';
		$field['title']	= $field['title'] ?? '';

		if($slug = wpjam_array_pull($field, 'slug')){
			$field['description']	= '<a target="_blank" href="https://blog.wpjam.com/m/'.$slug.'/">'.$field['description'].'</a>';
		}

		return $field;
	}

	public static function sanitize_callback($value){
		flush_rewrite_rules();

		return $value;
	}

	public static function load_extends_page(){
		$fields		= [];
		$extend_dir = WPJAM_BASIC_PLUGIN_DIR.'extends';
		$headers	= ['Name'=>'Name',	'URI'=>'URI',	'Version'=>'Version',	'Description'=>'Description'];

		$extends 	= wpjam_get_option('wpjam-extends');
		$extends	= $extends ? array_filter($extends) : [];

		foreach($extends as $extend_file => $value){	// 已激活的优先
			if(is_file($extend_dir.'/'.$extend_file)){
				$data	= get_file_data($extend_dir.'/'.$extend_file, $headers);

				if($data['Name']){
					$fields[$extend_file] = ['title'=>'<a href="'.$data['URI'].'" target="_blank">'.$data['Name'].'</a>', 'type'=>'checkbox', 'description'=>$data['Description']];
				}
			}
		}

		if($extend_handle = opendir($extend_dir)) {
			while(($extend_file = readdir($extend_handle)) !== false){
				if($extend_file != '.' && $extend_file != '..' && is_file($extend_dir.'/'.$extend_file) && pathinfo($extend_file, PATHINFO_EXTENSION) == 'php' && !isset($fields[$extend_file])){
					$data	= get_file_data($extend_dir.'/'.$extend_file, $headers);

					if($data['Name']){
						$fields[$extend_file] = ['title'=>'<a href="'.$data['URI'].'" target="_blank">'.$data['Name'].'</a>', 'type'=>'checkbox', 'description'=>$data['Description']];
					}
				}
			}

			closedir($extend_handle);
		}

		if(is_multisite() && !is_network_admin()){
			$sitewide_extends	= wpjam_get_site_option('wpjam-extends');
			$sitewide_extends	= array_filter($sitewide_extends);

			foreach($sitewide_extends as $extend_file => $value){
				unset($fields[$extend_file]);
			}
		}

		$summary	= is_network_admin() ? '在管理网络激活将整个站点都会激活！' : '';

		wpjam_register_option('wpjam-extends', ['fields'=>$fields, 'summary'=>$summary, 'ajax'=>false]);
	}

	public static function load_custom_page(){
		wpjam_register_option('wpjam-custom', [
			'site_default'	=> true,
			'summary'		=> '对网站的前后台的样式个性化设置，详细介绍请点击：<a href="https://blog.wpjam.com/m/wpjam-basic-custom-setting/"  target="_blank">样式定制</a>。',
			'sections'		=> [
				'wpjam-custom'	=> ['title'=>'前台定制',	'fields'=>[
					'head'			=> ['title'=>'前台 Head 代码',		'type'=>'textarea',	'class'=>''],
					'footer'		=> ['title'=>'前台 Footer 代码',		'type'=>'textarea',	'class'=>''],
				]],
				'admin-custom'	=> ['title'=>'后台定制',	'fields'=>[
					'admin_logo'	=> ['title'=>'后台左上角 Logo',		'type'=>'img',	'item_type'=>'url',	'description'=>'建议大小：20x20。'],
					'admin_head'	=> ['title'=>'后台 Head 代码 ',		'type'=>'textarea',	'class'=>''],
					'admin_footer'	=> ['title'=>'后台 Footer 代码',		'type'=>'textarea',	'class'=>'']
				]],
				'login-custom'	=> ['title'=>'登录界面', 	'fields'=>[
					// 'login_logo'			=> ['title'=>'登录界面 Logo',		'type'=>'img',		'description'=>'建议大小：宽度不超过600px，高度不超过160px。'),
					'login_head'	=> ['title'=>'登录界面 Head 代码',	'type'=>'textarea',	'class'=>''],
					'login_footer'	=> ['title'=>'登录界面 Footer 代码',	'type'=>'textarea',	'class'=>''],
					'login_redirect'=> ['title'=>'登录之后跳转的页面',		'type'=>'text'],
				]]
			]
		]);
	}

	public static function load_posts_page(){
		wpjam_set_plugin_page_summary('文章设置把文章编辑的一些常用操作，提到文章列表页面，方便设置和操作，详细介绍请点击：<a href="https://blog.wpjam.com/m/wpjam-basic-posts/" target="_blank">文章设置</a>。');

		wpjam_register_plugin_page_tab('posts', [
			'title'			=> '文章列表',
			'function'		=> 'option',
			'option_name'	=> 'wpjam-basic',
			'order'			=> 20,
			'summary'		=> '文章设置优化，增强文章列表和文章功能。',
			'fields'		=> [
				'post_list_ajax'			=> ['title'=>'AJAX操作',	'type'=>'checkbox',	'value'=>1,	'description'=>'在文章列表页全面实现AJAX操作。'],
				'post_list_set_thumbnail'	=> ['title'=>'缩略图',	'type'=>'checkbox',	'value'=>1,	'description'=>'在文章列表页显示和设置文章缩略图。'],
				'post_list_update_views'	=> ['title'=>'浏览数',	'type'=>'checkbox',	'value'=>1,	'description'=>'在文章列表页显示和修改文章浏览数。'],
				'post_list_author_filter'	=> ['title'=>'作者过滤',	'type'=>'checkbox',	'value'=>1,	'description'=>'在文章列表页支持通过作者进行过滤。'],
				'post_list_sort_selector'	=> ['title'=>'排序选择',	'type'=>'checkbox',	'value'=>1,	'description'=>'在文章列表页显示排序下拉选择框。'],
			],
			'site_default'	=> true
		]);
	}

	public static function load_dashicons_page(){
		
		echo wpautop('Dashicons 功能列出所有的 Dashicons 以及每个 Dashicon 的名称和 HTML 代码。<br />详细介绍请查看：<a href="https://blog.wpjam.com/m/wpjam-basic-dashicons/" target="_blank">Dashicons</a>，在 WordPress 后台<a href="https://blog.wpjam.com/m/using-dashicons-in-wordpress-admin/" target="_blank">如何使用 Dashicons</a>。');

		$dashicon_css_file	= fopen(ABSPATH.'/'.WPINC.'/css/dashicons.css','r') or die("Unable to open file!");

		$i	= 0;

		$dashicons_html = '';

		while(!feof($dashicon_css_file)) {
			$line	= fgets($dashicon_css_file);
			$i++;

			if($i < 32) continue;

			if($line){
				if (preg_match_all('/.dashicons-(.*?):before/i', $line, $matches)) {
					$dashicons_html .= '<p data-dashicon="dashicons-'.$matches[1][0].'"><span class="dashicons-before dashicons-'.$matches[1][0].'"></span> <br />'.$matches[1][0].'</p>'."\n";
				}elseif(preg_match_all('/\/\* (.*?) \*\//i', $line, $matches)){
					if($dashicons_html){
						echo '<div class="wpjam-dashicons">'.$dashicons_html.'</div>'.'<div class="clear"></div>';
					}
					echo '<h2>'.$matches[1][0].'</h2>'."\n";
					$dashicons_html = '';
				}
			}
		}

		echo '<div class="wpjam-dashicons">'.$dashicons_html.'</div>'.'<div class="clear"></div>';

		fclose($dashicon_css_file);
		?>
		<style type="text/css">
		h2{max-width: 800px; margin:40px 0 20px 0; padding-bottom: 20px; clear: both; border-bottom: 1px solid #ccc;}
		div.wpjam-dashicons{max-width: 800px; float: left;}
		div.wpjam-dashicons p{float: left; margin:0px 10px 10px 0; padding: 10px; width:70px; height:70px; text-align: center; cursor: pointer;}
		div.wpjam-dashicons .dashicons-before:before{font-size:32px; width: 32px; height: 32px;}
		div#TB_ajaxContent p{font-size:20px; float: left;}
		div#TB_ajaxContent .dashicons{font-size:100px; width: 100px; height: 100px;}
		</style>
		<script type="text/javascript">
		jQuery(function($){
			$('body').on('click', 'div.wpjam-dashicons p', function(){
				let dashicon	= $(this).data('dashicon');
				let html 		= '<p><span class="dashicons '+dashicon+'"></span></p><p style="margin-left:20px;">'+dashicon+'<br /><br />HTML：<br /><code>&lt;span class="dashicons '+dashicon+'"&gt;&lt;/span&gt;</code></p>';
				
				$('#tb_modal').wpjam_show_modal(html, dashicon, 680);
			});
		});
		</script>
		<?php
	}

	public static function load_about_page(){
		$jam_plugins = get_transient('about_jam_plugins');

		if($jam_plugins === false){
			$response	= wpjam_remote_request('http://jam.wpweixin.com/api/template/get.json?id=5644');

			if(!is_wp_error($response)){
				$jam_plugins	= $response['template']['table']['content'];
				set_transient('about_jam_plugins', $jam_plugins, DAY_IN_SECONDS );
			}
		}

		?>
		<div style="max-width: 900px;">
			<table id="jam_plugins" class="widefat striped">
				<tbody>
				<tr>
					<th colspan="2">
						<h2>WPJAM 插件</h2>
						<p>加入<a href="http://97866.com/s/zsxq/">「WordPress果酱」知识星球</a>即可下载：</p>
					</th>
				</tr>
				<?php foreach($jam_plugins as $jam_plugin){ ?>
				<tr>
					<th style="width: 100px;"><p><strong><a href="<?php echo $jam_plugin['i2']; ?>"><?php echo $jam_plugin['i1']; ?></a></strong></p></th>
					<td><?php echo wpautop($jam_plugin['i3']); ?></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>

			<div class="card">
				<h2>WPJAM Basic</h2>

				<p><strong><a href="http://blog.wpjam.com/project/wpjam-basic/">WPJAM Basic</a></strong> 是 <strong><a href="http://blog.wpjam.com/">我爱水煮鱼</a></strong> 的 Denis 开发的 WordPress 插件。</p>

				<p>WPJAM Basic 除了能够优化你的 WordPress ，也是 「WordPress 果酱」团队进行 WordPress 二次开发的基础。</p>
				<p>为了方便开发，WPJAM Basic 使用了最新的 PHP 7.2 语法，所以要使用该插件，需要你的服务器的 PHP 版本是 7.2 或者更高。</p>
				<p>我们开发所有插件都需要<strong>首先安装</strong> WPJAM Basic，其他功能组件将以扩展的模式整合到 WPJAM Basic 插件一并发布。</p>
			</div>

			<div class="card">
				<h2>WPJAM 优化</h2>
				<p>网站优化首先依托于强劲的服务器支撑，这里强烈建议使用<a href="https://wpjam.com/go/aliyun/">阿里云</a>或<a href="https://wpjam.com/go/qcloud/">腾讯云</a>。</p>
				<p>更详细的 WordPress 优化请参考：<a href="https://blog.wpjam.com/article/wordpress-performance/">WordPress 性能优化：为什么我的博客比你的快</a>。</p>
				<p>我们也提供专业的 <a href="https://blog.wpjam.com/article/wordpress-optimization/">WordPress 性能优化服务</a>。</p>
			</div>
		</div>
		<style type="text/css">
			.card {max-width: 320px; float: left; margin-top:20px;}
			.card a{text-decoration: none;}
			table#jam_plugins{margin-top:20px; width: 520px; float: left; margin-right: 20px;}
			table#jam_plugins th{padding-left: 2em; }
			table#jam_plugins td{padding-right: 2em;}
			table#jam_plugins th p, table#jam_plugins td p{margin: 6px 0;}
		</style>
	<?php }

	public static function add_menu_pages(){
		$subs	= [];

		$subs['wpjam-basic']	= ['menu_title'=>'优化设置',	'function'=>'option',	'load_callback'=>[self::class, 'load_basic_page']];

		$verified	= WPJAM_Verify::verify();

		if(!$verified){
			$subs['wpjam-verify']	= ['menu_title'=>'扩展管理',	'page_title'=>'验证 WPJAM',	'function'=>'form',	'form_name'=>'verify_wpjam',	'load_callback'=>['WPJAM_Verify', 'page_action']];
		}else{
			$subs		+= WPJAM_Basic::$sub_pages;
			$subs		= apply_filters('wpjam_basic_sub_pages', $subs);

			$capability	= is_multisite() ? 'manage_sites' : 'manage_options';

			$subs['wpjam-custom']	= ['menu_title'=>'样式定制',		'function'=>'option',	'order'=>20,	'load_callback'=>[self::class, 'load_custom_page']];
			$subs['wpjam-cdn']		= ['menu_title'=>'CDN加速',		'function'=>'option',	'order'=>19,	'load_callback'=>['WPJAM_CDN', 'load_option_page']];
			$subs['wpjam-thumbnail']= ['menu_title'=>'缩略图设置',	'function'=>'option',	'order'=>18,	'load_callback'=>['WPJAM_Thumbnail', 'load_option_page']];
			$subs['wpjam-posts']	= ['menu_title'=>'文章设置',		'function'=>'tab',		'order'=>18,	'load_callback'=>[self::class, 'load_posts_page']];
			$subs['wpjam-crons']	= ['menu_title'=>'定时作业',		'function'=>'tab',		'order'=>10,	'load_callback'=>['WPJAM_Cron',	'load_plugin_page']];
			$subs['server-status']	= ['menu_title'=>'系统信息',		'function'=>'tab',		'capability'=>$capability,	'page_file'=>__DIR__.'/server-status.php'];
			$subs['dashicons']		= ['menu_title'=>'Dashicons',	'function'=>[self::class, 'load_dashicons_page']];
			$subs['wpjam-extends']	= ['menu_title'=>'扩展管理',		'function'=>'option',	'load_callback'	=>[self::class, 'load_extends_page']];

			if($verified !== 'verified'){
				$subs['wpjam-basic-topics']	= ['menu_title'=>'讨论组',		'function'=>'tab',	'page_file'=>__DIR__.'/wpjam-topics.php'];
				$subs['wpjam-about']		= ['menu_title'=>'关于WPJAM',	'function'=>[self::class, 'load_about_page']];
			}
		}

		wpjam_add_menu_page('wpjam-basic', [
			'menu_title'	=> 'WPJAM',
			'icon'			=> 'dashicons-performance',
			'position'		=> '58.99',
			'network'		=> true,
			'function'		=> 'option',
			'subs'			=> $subs
		]);
	}

	public static function add_separator(){
		$GLOBALS['menu']['58.88']	= ['',	'read',	'separator'.'58.88', '', 'wp-menu-separator'];
	}

	public static function on_builtin_page_load($screen_base, $current_screen){
		if(in_array($screen_base, ['post', 'edit', 'upload'])){
			include __DIR__.'/wpjam-posts.php';

			WPJAM_Posts_Admin::builtin_page_load($screen_base, $current_screen);
		}elseif(in_array($screen_base, ['dashboard', 'dashboard-network', 'dashboard-user'])){
			include __DIR__.'/wpjam-dashboard.php';

			wpjam_dashboard_builtin_page_load($screen_base);
		}

		if(in_array($screen_base, ['edit', 'upload', 'edit-tags'])){
			add_action('admin_head', [self::class, 'inline_script']);
		}
	}

	public static function inline_script(){
		if(!wpjam_basic_get_setting('post_list_ajax', 1)){ ?>
		
		<script type="text/javascript">
			wpjam_page_setting.list_table.ajax	= false;
		</script>
		
		<?php }
	}
}

add_action('wpjam_loaded',	function(){
	add_action('wpjam_builtin_page_load',	['WPJAM_Basic_Menu', 'on_builtin_page_load'], 10, 2);

	add_action('wpjam_admin_init',	['WPJAM_Basic_Menu', 'add_menu_pages']);
	add_action('admin_menu',		['WPJAM_Basic_Menu', 'add_separator']);
});