<?php
// BOOTSTRAP

// load the bootstrap walker
add_action( 'after_setup_theme', 'childtheme_include_third_party_files' );
function childtheme_include_third_party_files() {
	//$options = get_option( 'childtheme_settings' );
	
	//if( $options['childtheme_nav_walker'] ) {
		require_once( get_stylesheet_directory() . '/boilerplate/php/lib/wp-bootstrap-navwalker.php' );
	//}
}

// filter menu args to apply bootstrap walker and other settings
add_filter( 'wp_nav_menu_args', 'childtheme_nav_menu_args_filter' );
function childtheme_nav_menu_args_filter( $args ) {
  // target only primary and secondary menu locations
  if ( $args['theme_location'] === 'primary' || $args['theme_location'] === 'secondary' ) {
		// define a useful menu container id
	  $container_id = $args['theme_location'] . '-menu-container';
		$menu_id = $args['theme_location'] . '-menu';

	  // define the wrapper code which includes the navbar toggle markup
	  $args['items_wrap'] = '
			<div class="container navbar navbar-expand-md navbar-light collapse navbar-collapse" id="' . $container_id . '">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#' . $menu_id . '" aria-controls="' . $menu_id . '" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			
				<div class="collapse navbar-collapse" id="' . $menu_id . '">
					<ul id="%1$s" class="navbar-nav mr-auto mt-2 mt-lg-0 %2$s">
		      	%3$s
		    	</ul>
				</div>
		  </div>
	  ';

	  // allow 2-tier navigation (sub-menus)
	  $args['depth'] = 2;

	  // assign Bootstrap navbar classes
	  $args['menu_class'] = 'nav navbar-nav';

		//$options = get_option( 'childtheme_settings' );

		//if( $options['childtheme_nav_walker'] ) {
	  	// use the custom Bootstrap walker
		  $args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
		  $args['walker'] = new wp_bootstrap_navwalker();
		//}
  }
  
  return $args;
}

//* add bootstrap rows wherever they are missing
add_action( 'genesis_header', 'childtheme_bootstrap_open_row', 10 );
if( !function_exists( 'childtheme_bootstrap_open_row' ) ) {
	function childtheme_bootstrap_open_row() {
		echo '<div class="row">';
	}
}

add_action( 'genesis_header', 'childtheme_bootstrap_close_row', 11 );
if( !function_exists( 'childtheme_bootstrap_close_row' ) ) {
	function childtheme_bootstrap_close_row() {
		echo '</div>';
	}
}


// add bootstrap classes throughout the layout
add_filter( 'genesis_attr_site-container',      'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_structural-wrap',      'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_site-header',         'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_title-area',      		'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_header-widget-area',  'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_nav-primary',         'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_nav-secondary',       'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_site-inner',          'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_content-sidebar-wrap','childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_content',             'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_sidebar-primary',     'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_sidebar-secondary',   'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_archive-pagination',  'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_entry-header',        'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_entry-content',       'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_entry-footer',        'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_entry-pagination',    'childtheme_add_bootstrap_class', 11, 2 );
add_filter( 'genesis_attr_site-footer',         'childtheme_add_bootstrap_class', 11, 2 );

function childtheme_add_bootstrap_class( $attr, $context ) {
	// exit without modifications if childtheme_grid_classes are switched off
	//$options = get_option( 'childtheme_settings' );
	//if( !$options['childtheme_grid_classes'] ) {
		//return $attr;
	//}
	
  // default classes to add
  $classes_to_add = apply_filters (
    'childtheme_bootstrap_classes',
    array(
			// top level containers
      //'site-container'            => 'container',
			'structural-wrap'           => 'container',
			
			// layout containers
			//'site-header'               => 'container',
			//'site-inner'                => 'container',
      //'site-footer'               => 'container',
			
			// grid variations
			'content-sidebar-wrap'      => 'row',

			// header regions
			'title-area'            		=> 'col-md-6',
			'header-widget-area'				=> 'col-md-6',
			
			// nav regions
      //'nav-primary'               => 'container navbar navbar-expand-md navbar-light', // navbar-static-top
      //'nav-secondary'             => 'container navbar navbar-expand-md navbar-light', // navbar-static-top
			
			// main regions
      'content'                   => 'col-sm-9',
      'sidebar-primary'           => 'col-sm-3',
      'archive-pagination'        => 'clearfix',
      'entry-header'              => 'clearfix',
      'entry-content'             => 'clearfix',
      'entry-footer'              => 'clearfix',
      'entry-pagination'          => 'clearfix',
    ),
    $context,
    $attr
  );

  // populate $classes_array based on $classes_to_add
  $value = isset( $classes_to_add[ $context ] ) ? $classes_to_add[ $context ] : array();

  if ( is_array( $value ) ) {
    $classes_array = $value;
  } else {
    $classes_array = explode( ' ', (string) $value );
  }

  // apply any filters to modify the class
  $classes_array = apply_filters( 'childtheme_add_class', $classes_array, $context, $attr );

  $classes_array = array_map( 'sanitize_html_class', $classes_array );

  // append the class(es) string (e.g. 'span9 custom-class1 custom-class2')
  $attr['class'] .= ' ' . implode( ' ', $classes_array );

  return $attr;
}

// modify bootstrap classes based on genesis_site_layout
add_filter('childtheme_bootstrap_classes', 'childtheme_bootstrap_classes_layout_filter', 10, 3);
function childtheme_bootstrap_classes_layout_filter( $classes_to_add, $context, $attr ) {
	// exit without modifications if childtheme_grid_classes are switched off
	//$options = get_option( 'childtheme_settings' );
	//if( $options['childtheme_grid_classes'] ) {
		//return $classes_to_add;
	//}
	
  $classes_to_add = childtheme_add_bootstrap_layout_classes( $classes_to_add );

  return $classes_to_add;
}

function childtheme_add_bootstrap_layout_classes( $classes_to_add ) {
  $layout = genesis_site_layout();
  
  switch ( $layout ) {
    case 'full-width-content':
      $classes_to_add['content'] = 'col-sm-12';
      break;
    
    case 'sidebar-content':
      $classes_to_add['content'] = 'col-sm-9 col-sm-push-3';
      $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-pull-9';
      break;
    
    case 'content-sidebar-sidebar':
      $classes_to_add['content'] = 'col-sm-6';
      $classes_to_add['sidebar-primary'] = 'col-sm-3';
      $classes_to_add['sidebar-secondary'] = 'col-sm-3';
      break;
    
    case 'sidebar-sidebar-content':
      $classes_to_add['content'] = 'col-sm-6 col-sm-push-6';
      $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-pull-3';
      $classes_to_add['sidebar-secondary'] = 'col-sm-3 col-sm-pull-9';
      break;
    
    case 'sidebar-content-sidebar':
      $classes_to_add['content'] = 'col-sm-6 col-sm-push-3';
      $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-push-3';
      $classes_to_add['sidebar-secondary'] = 'col-sm-3 col-sm-pull-9';
      break;
    
    default:
      // content-sidebar - no changes needed
      break;
  }

  return $classes_to_add;
};