<?php
use B2\Modules\Common\Post;
use B2\Modules\Templates\Modules\Posts;
use B2\Modules\Templates\Modules\Sliders;
use B2\Modules\Common\Circle;
use B2\Modules\Common\PostRelationships;
use B2\Modules\Common\User;
use B2\Modules\Common\Comment;
use B2\Modules\Common\CircleRelate;
use B2\Modules\Templates\Collection;
use B2APP\common\methods;
use B2APP\common\opt;

if(!current_user_can('administrator')) wp_die('您无权访问此页');
