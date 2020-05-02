<?php
/**
 * Plugin Name: Deals Custom Post
 * Plugin URI: http://www.inc42.com
 * Description: Deals is a Admin Side Custom Post Type developer by Omi Kabira for Inc42
 * Version: 1.0
 * Author: Omi Kabira
 * Author URI: https://www.omikabira.github.io
 *
 * @package   Custom_Post_Types_Deals
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 */

// avoid direct calls to this file.
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define( 'DEALS_PLUGIN', __FILE__ );

define( 'DEALS_PLUGIN_BASENAME', plugin_basename( DEALS_PLUGIN ) );

define( 'DEALS_PLUGIN_NAME', trim( dirname( DEALS_PLUGIN_BASENAME ), '/' ) );

define( 'DEALS_PLUGIN_DIR', untrailingslashit( dirname( DEALS_PLUGIN ) ) );

define( 'DEALS_PLUGIN_DIR_URL', untrailingslashit( plugin_dir_url( DEALS_PLUGIN ) ) );

/* Register custom post types on the 'init' hook. */
add_action( 'init', 'register_deal_post_type' );

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function register_deal_post_type() {
	$labels = array(
		'name'                  => 'Deals',
		'singular_name'         => 'Deal',
		'add_new'               => 'Add New',
		'add_new_item'          => 'Add New Deal',
		'edit_item'             => 'Edit Deal',
		'new_item'              => 'New Deal',
		'all_items'             => 'All Deals',
		'view_item'             => 'Vew Deal',
		'search_items'          => 'Search Deals',
		'not_found'             => 'No Deal found',
		'not_found_in_trash'    => 'No Deal found in trash',
		'menu_name'             => 'Deals',
		'featured_image'        => 'Logo',
		'set_featured_image'    => 'Set Logo ',
		'remove_featured_image' => 'Remove Logo',
		'use_featured_image'    => 'Use as Logo',
		'insert_into_item'      => 'Insert into Deal',
		'uploaded_to_this_item' => 'Uploaded to this Deal',
		'filter_items_list'     => 'Filter Deals',
		'items_list_navigation' => 'Deals navigation',
		'items_list'            => 'Deals list',
	);

	$args = array(
		'public'             => true,
		'label'              => 'Deals',
		'labels'             => $labels,
		'description'        => 'This is the deal post type.',
		'capabilities'       => array( 'create_posts' => true ),
		'map_meta_cap'       => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'deals' ),
		'capability_type'    => 'post',
		'supports'           => array(
			'title',
			'thumbnail',
		),
	);
	register_post_type( 'deals', $args );
}


/**
 * FUnction to call Dealmeta class
 */
function call_dealmeta_class() {
	include_once DEALS_PLUGIN_DIR . '/classes/class-dealmeta.php';
	return new Dealmeta();
}

if ( is_admin() ) {
	add_action( 'init', 'call_dealmeta_class' );
}

/**
 * Function to initialize script and style
 *
 * @param int $postid Get the id o post.
 */
function my_enqueue( $postid ) {

	wp_register_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap.min.css', '1.0', true );
	wp_enqueue_style( 'bootstrap' );

	wp_register_style( 'theme-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', '1.0', true );
	wp_enqueue_style( 'theme-style' );

	wp_register_script( 'jquery', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.js', '0.0.1', true );
	wp_enqueue_script( 'jquery' );

	wp_register_script( 'mainjs', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', 'jquery', '0.0.1', true );
	wp_enqueue_script( 'mainjs' );

	wp_localize_script(
		'mainjs',
		'pluginVar',
		array(
			'pluginsUrl'  => plugins_url( '', __FILE__ ),
			'currentUser' => get_current_user_id(),
			'postid'      => $postid,
			'ajax'        => admin_url( 'admin-ajax.php' ),
		)
	);
}

/**
 * Function to convert multiple names in to count.
 *
 * @param  array $authors Get All names.
 * @param  int   $take_count Number of count to show.
 */
function get_name_with_counts( array $authors, $take_count ) {

	$total_authors = count( $authors );
	$tail_count    = $total_authors - $take_count;
	$first         = array_slice( $authors, 0, $take_count );
	$string        = implode( ',', $first );
	if ( $tail_count > 0 ) {
		$string .= ' +  ' . $tail_count;
	}
	return $string;
}

/**
 * Function to show single deal.
 *
 * @param  int $postid Get post id.
 */
function deal_card( $postid ) {

	my_enqueue( $postid );
	$post               = get_post( $postid );
	$company            = get_the_title( $post );
	$logo               = get_the_post_thumbnail_url( $post, $size = 'post-thumbnail' );
	$deal_founders      = get_post_meta( $postid, 'deal_founders', true );
	$deal_sectors       = get_post_meta( $postid, 'deal_sectors', true );
	$deal_launch_year   = get_post_meta( $postid, 'deal_launch_year', true );
	$deal_investors     = get_post_meta( $postid, 'deal_investors', true );
	$deal_article_title = get_post_meta( $postid, 'deal_article_title', true );
	$deal_article_link  = get_post_meta( $postid, 'deal_article_link', true );
	$deal_founders      = explode( ',', $deal_founders );
	$deal_founders      = get_name_with_counts( $deal_founders, 1 );
	$deal_investors     = explode( ',', $deal_investors );
	$deal_investors     = get_name_with_counts( $deal_investors, 2 );

	?>

	<div class="col-md-3 col-sm-4 col-xs-12">
		<div class="card text-white bg-primary mb-3">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<img src="<?php echo esc_url( $logo ); ?>" class="img-fluid" alt="<?php echo esc_html( ucfirst( $company ) ); ?>">
					</div>
					<div class="col-md-8">
						<h4 class="card-title"><?php echo esc_html( $company ); ?></h4>
						<h6 class="card-subtitle mb-2 text-muted"><span class="badge badge-info"><?php echo esc_html( ucfirst( $deal_sectors ) ); ?></span></h6>
					</div>
				</div>
				<hr>
				<p class="card-text">Launch Year : <b><?php echo esc_html( $deal_launch_year ); ?></b></p>
				<p class="card-text">Founders : <b><?php echo esc_html( ucfirst( $deal_founders ) ); ?></b></p>
				<p class="card-text">Investors : <b><?php echo esc_html( ucfirst( $deal_investors ) ); ?></b></p>
				<p lass="card-text">News : <a href="<?php echo esc_url( $deal_article_link ); ?>" class="card-link"><?php echo esc_html( ucfirst( $deal_article_title ) ); ?></a></p>
			</div>
		</div>
		<br>
	</div>

	<?php
}
add_shortcode( 'deal-card', 'single_deal_shortcode' );

/**
 * Function to call single deal shortcode.
 *
 * @param  array $attrs Get All Attributes for shortcode.
 */
function single_deal_shortcode( $attrs ) {
	deal_card( $attrs['postid'] );
	$result = ob_get_clean();
	return $result;
}

/**
 * Function to change post title to company.
 *
 * @param  string $title Get title of deal post.
 */
function wpb_change_title_text( $title ) {
	$screen = get_current_screen();
	if ( 'deals' === $screen->post_type ) {
		$title = 'Enter Company Name';
	}
	return $title;
}

add_filter( 'enter_title_here', 'wpb_change_title_text' );


add_shortcode( 'all-deals', 'all_deals_shortcode' );

/**
 * Function to call all deals shortcode.
 *
 * @param  array $attrs Get All Attributes for shortcode.
 */
function all_deals_shortcode( $attrs ) {
	$query = new WP_Query(
		array(
			'post_type'   => 'deals',
			'post_status' => 'publish',
		)
	);
	?>
	<section class="filters jumbotron" style="padding: 3rem 2rem 1rem;">
		<div class="row">
			<div class="col-md-12">
				<p><b>Filter By :</b></p>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?php
					$sectors_list = array(
						'ecommerce'         => 'E-commerce',
						'fintech'           => 'FinTech',
						'consumer_services' => 'Consumer Services',
						'healthtech'        => 'HealthTech',
						'edtech'            => 'EdTech',
						'agritech'          => 'AgriTech',
						'logistics'         => 'Logistics',
					);

					?>
					<label for="filter-sectors">Sectors</label>
					<select id="filter-sectors" class="form-control">
						<option value="-1" >Select Sector </option>
						<?php foreach ( $sectors_list as $key => $value ) { ?>
							<option value="<?php echo esc_html( $key ); ?>"> <?php echo esc_html( $value ); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<?php
			$stage_list = array(
				'seed'           => 'Seed',
				'preseries_a'    => 'Pre-Series A',
				'series_a'       => 'Series A',
				'preseries_b'    => 'Pre-Series B',
				'series_b'       => 'Series B',
				'series_c'       => 'Series C',
				'series_d'       => 'Series D',
				'series_e'       => 'Series E',
				'series_f'       => 'Series F',
				'late_stage'     => 'Late Stage',
				'debt_financing' => 'Debt Financing',
				'acquisition'    => 'Acquisition',
				'ipo'            => 'IPO',
			);

			?>
			<div class="col-md-3">
				<div class="form-group">
					<label for="filter-stage">Deal Stage</label>
					<select id="filter-stage" class="form-control">
						<option value="-1" >Select Stage </option>
						<?php foreach ( $stage_list as $key => $value ) { ?>
							<option value="<?php echo esc_html( $key ); ?>"> <?php echo esc_html( $value ); ?> </option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label for="filter_fund">Funding Amount</label>
					<input type="range" class="custom-range" id="filter_fund" step="10000" value="0" min="0" max="5000000" oninput="ageOutputId.value = filter_fund.value">
					<output name="ageOutputName" id="ageOutputId">0</output>
				</div>
			</div>
		</section>

		<section class="jumbotron mb-2">
			<div class="row" id="all-deals">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();
					deal_card( $post_id );
				}
				?>
			</div>
		</section>
		<?php
		$result = ob_get_clean();
		return $result; }


		add_action( 'wp_ajax_sector_filter', 'sector_filter_function' );
		add_action( 'wp_ajax_nopriv_sector_filter', 'sector_filter_function' );

/**
 * Function to filter the deals with sectors
 */
function sector_filter_function() {
	global $wpdb;

	if ( isset( $_POST['sector'] ) ) {
		$sector = sanitize_text_field( wp_unslash( $_POST['sector'] ) );
		if ( wp_verify_nonce( $sector ) ) {
			return $sector;
		}
		if ( '-1' === $sector ) {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
		} else {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'deal_sectors',
						'value'   => $sector,
						'compare' => 'LIKE',
					),
				),
			);
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$deal_data = '';
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id    = get_the_ID();
				$deal_data .= deal_card( $post_id );
			}
		} else {
			$deal_data = 'No Deal Found.';
		}
		echo wp_json_encode( $deal_data );
		die();
	}

}

add_action( 'wp_ajax_stage_filter', 'stage_filter_function' );
add_action( 'wp_ajax_nopriv_stage_filter', 'stage_filter_function' );

/**
 * Function to filter the deals with stages
 */
function stage_filter_function() {
	global $wpdb;

	if ( isset( $_POST['stage'] ) ) {
		$stage = sanitize_text_field( wp_unslash( $_POST['stage'] ) );
		if ( wp_verify_nonce( $stage ) ) {
			return $stage;
		}
		if ( '-1' === $stage ) {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
		} else {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'deal_stage',
						'value'   => $stage,
						'compare' => 'LIKE',
					),
				),
			);
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$deal_data = '';
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id    = get_the_ID();
				$deal_data .= deal_card( $post_id );
			}
		} else {
			$deal_data = 'No Deal Found.';
		}
		echo wp_json_encode( $deal_data );
		die();
	}

}


add_action( 'wp_ajax_fund_filter', 'fund_filter_function' );
add_action( 'wp_ajax_nopriv_fund_filter', 'fund_filter_function' );

/**
 * Function to filter the deals with funds
 */
function fund_filter_function() {
	global $wpdb;

	if ( isset( $_POST['fund'] ) ) {
		$fund = sanitize_text_field( wp_unslash( $_POST['fund'] ) );
		if ( wp_verify_nonce( $fund ) ) {
			return $fund;
		}
		if ( '-1' === $fund ) {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
		} else {
			$args = array(
				'post_type'      => 'deals',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'deal_funding_amount',
						'value'   => $fund,
						'compare' => '=',
					),
				),
			);
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$deal_data = '';
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id    = get_the_ID();
				$deal_data .= deal_card( $post_id );
			}
		} else {
			$deal_data = 'No Deal Found.';
		}
		echo wp_json_encode( $deal_data );
		die();
	}

}
