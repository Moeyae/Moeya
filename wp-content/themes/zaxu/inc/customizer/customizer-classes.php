<?php
/*
 * @Description: Customizer classes
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

if( class_exists('WP_Customize_Control') ):
  class WP_Customize_Post_Select extends WP_Customize_Control {
    public $type = 'latest_post_dropdown';
    public function render_content() {
      $latest = new WP_Query( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => '-1'
      ) );
      ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
          <select <?php $this->link(); ?>>
            <?php printf('<option value="%s" %s>%s</option>', 'none', selected($this->value(), 'none', false), esc_html('None', 'zaxu') ); ?>
            <?php 
              while( $latest->have_posts() ) {
                $latest->the_post();
                echo "<option " . selected( $this->value(), get_the_ID() ) . " value='" . get_the_ID() . "'>" . the_title('', '', false) . "</option>";
              }
            ?>
          </select>
          <?php if ( isset($this->description) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
          <?php endif; ?>
        </label>
      <?php
    }
  }
endif;

if( class_exists('WP_Customize_Control') ):
  class WP_Customize_Post_Select_Multiple extends WP_Customize_Control {
    public $type = 'latest_post_dropdown';
    public function render_content() {
      $latest = new WP_Query( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => '-1'
      ) );
      ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
          <select multiple <?php $this->link(); ?>>
            <?php printf('<option value="%s" %s>%s</option>', 'none', selected($this->value(), 'none', false), esc_html('None', 'zaxu') ); ?>
            <?php 
              while( $latest->have_posts() ) {
                $latest->the_post();
                echo "<option " . selected( $this->value(), get_the_ID() ) . " value='" . get_the_ID() . "'>" . the_title('', '', false) . "</option>";
              }
            ?>
          </select>
          <?php if ( isset($this->description) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
          <?php endif; ?>
        </label>
      <?php
    }
  }
endif;
?>