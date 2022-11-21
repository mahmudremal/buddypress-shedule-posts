<?php
/**
 * Loadmore Single Posts
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;
// use \WP_Query;

class Dashboard {

	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
    add_action( 'init', [ $this, 'profile' ], 10, 0 );
    add_action( 'init', [ $this, 'animals' ], 10, 0 );
	}
  public function profile() {
    /**
     * adds the profile user nav link
     */
    function bp_custom_user_nav_item() {
      global $bp;
      $args = array(
        'name' => __('Portfolio', 'buddypress'),
        'slug' => 'portfolio',
        'default_subnav_slug' => 'portfolio',
        'position' => 50,
        'screen_function' => 'bp_custom_user_nav_item_screen',
        'item_css_id' => 'portfolio'
      );
      bp_core_new_nav_item( $args );
    }
    add_action( 'bp_setup_nav', 'bp_custom_user_nav_item', 99 );
    /**
    * the calback function from our nav item arguments
    */
    function bp_custom_user_nav_item_screen() {
      add_action( 'bp_template_content', 'bp_custom_screen_content' );
      bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
    /**
    * the function hooked to bp_template_content, this hook is in plugns.php
    */
    function bp_custom_screen_content() {
      echo '<p>The custom content.
      You can put a post loop here or something else</P>';
    }
  }
  public function animals() {
    function add_animal_tabs() {
      global $bp;
      
      bp_core_new_nav_item( array(
        'name'                  => 'Animals',
        'slug'                  => 'animals',
        'parent_url'            => $bp->displayed_user->domain,
        'parent_slug'           => $bp->profile->slug,
        'screen_function'       => 'animals_screen',			
        'position'              => 200,
        'default_subnav_slug'   => 'animals'
      ) );
        
      bp_core_new_subnav_item( array(
        'name'              => 'Dogs',
        'slug'              => 'dogs',
        'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'animals' ),
        'parent_slug'       => 'animals',
        'screen_function'   => 'dogs_screen',
        'position'          => 100,
        'user_has_access'   => bp_is_my_profile()
      ) );		
    
      bp_core_new_subnav_item( array(
        'name'              => 'Cats',
        'slug'              => 'cats',
        'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'animals' ),
        'parent_slug'       => 'animals',
        'screen_function'   => 'cats_screen',
        'position'          => 150,
        'user_has_access'   => bp_is_my_profile()
      ) );	
    
    }
    add_action( 'bp_setup_nav', 'add_animal_tabs', 100 );
    
    
    
    function animals_screen() {
        add_action( 'bp_template_title', 'animals_screen_title' );
        add_action( 'bp_template_content', 'animals_screen_content' );
        bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
    function animals_screen_title() { 
      echo 'Animals Title<br/>';
    }
    function animals_screen_content() { 
      echo 'Animals Content<br/>';
    }
    
    
    function dogs_screen() {
        add_action( 'bp_template_content', 'dogs_screen_content' );
        bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
    function dogs_screen_content() { 
      echo 'Dogs'; 
    }
    
    function cats_screen() {
        add_action( 'bp_template_content', 'cats_screen_content' );
        bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
    function cats_screen_content() { 
      echo 'Cats'; 
    }
  }
}
