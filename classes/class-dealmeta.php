<?php
/**
 * File to add metaboxes for deals
 *
 * @file class-dealmeta.php
 * @package custom_post_deals
 */

/**
 * Implements Dealmeta.
 */
class Dealmeta {
	/**
	 * Constructor to add meta boxes
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( &$this, 'deal_sectors' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_launch_year' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_founders' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_stage' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_funding_amount' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_investors' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_article_title' ) );
		add_action( 'add_meta_boxes', array( &$this, 'deal_article_link' ) );
		add_action( 'save_post', array( &$this, 'save_deal_meta' ) );
	}

	/**
	 * Sectors Meta Box.
	 */
	public function deal_sectors() {
		add_meta_box( 'deal_sectors', __( 'Sector' ), array( &$this, 'sectors_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Launch Year Meta Box.
	 */
	public function deal_launch_year() {
		add_meta_box( 'deal_launch_year', __( 'Launch Year' ), array( &$this, 'launch_year_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Founders Meta Box.
	 */
	public function deal_founders() {
		add_meta_box( 'deal_founders', __( 'Founders' ), array( &$this, 'founders_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Stage Meta Box.
	 */
	public function deal_stage() {
		add_meta_box( 'deal_stage', __( 'Stage' ), array( &$this, 'stage_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Funding Amount Meta Box.
	 */
	public function deal_funding_amount() {
		add_meta_box( 'deal_funding_amount', __( 'Funding Amount' ), array( &$this, 'funding_amount_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Investors Meta Box.
	 */
	public function deal_investors() {
		add_meta_box( 'deal_investors', __( 'Investors' ), array( &$this, 'investors_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Article Title Meta Box.
	 */
	public function deal_article_title() {
		add_meta_box( 'deal_article_title', __( 'Article Title' ), array( &$this, 'article_title_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Article Link Meta Box.
	 */
	public function deal_article_link() {
		add_meta_box( 'deal_article_link', __( 'Article Link' ), array( &$this, 'article_link_content' ), 'deals', $context = 'advanced', $priority = 'default', $callback_args = null );
	}

	/**
	 * Insert Sectors.
	 *
	 * @param int $post = to get the post content.
	 */
	public function sectors_content( $post ) {

		$sectors_list = array(
			'ecommerce'         => 'E-commerce',
			'fintech'           => 'FinTech',
			'consumer_services' => 'Consumer Services',
			'healthtech'        => 'HealthTech',
			'edtech'            => 'EdTech',
			'agritech'          => 'AgriTech',
			'logistics'         => 'Logistics',
		);

		$sectors = get_post_meta( $post->ID, 'deal_sectors', true );

		?>
		<div>
			<select id="deal_sectors" name="deal_sectors">
				<option <?php echo ( ( empty( $sectors ) ) ? 'selected' : '' ); ?>value="-1" >Select Sector </option>
				<?php foreach ( $sectors_list as $key => $value ) { ?>
					<option value="<?php echo esc_html( $key ); ?>" <?php echo( ( isset( $sectors ) && ( $sectors === $key ) ) ? 'selected' : '' ); ?> > <?php echo esc_html( $value ); ?>
				</option>
			<?php } ?>
		</select>
	</div>
<?php }

	/**
	 * Insert Launch Year.
	 *
	 * @param int $post = to get the post content.
	 */
	public function launch_year_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_launch_year', true );
		?>
		<div>
			<input type="date" name="deal_launch_year" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert Founders.
	 *
	 * @param int $post = to get the post content.
	 */
	public function founders_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_founders', true );
		?>
		<div>
			<input type="text" name="deal_founders" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert Stage.
	 *
	 * @param int $post = to get the post content.
	 */
	public function stage_content( $post ) {

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

		$stage = get_post_meta( $post->ID, 'deal_stage', true );

		?>
		<div>
			<select id="deal_stage" name="deal_stage">
				<option <?php echo ( ( empty( $stage ) ) ? 'selected' : '' ); ?>value="-1" >Select Stage </option>
				<?php foreach ( $stage_list as $key => $value ) { ?>
					<option value="<?php echo esc_html( $key ); ?>" <?php echo( ( isset( $stage ) && ( $stage === $key ) ) ? 'selected' : '' ); ?> > <?php echo esc_html( $value ); ?>
				</option>
			<?php } ?>
		</select>
	</div>

<?php }

	/**
	 * Insert Funding Amount.
	 *
	 * @param int $post = to get the post content.
	 */
	public function funding_amount_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_funding_amount', true );
		?>
		<div>
			<input type="number" name="deal_funding_amount" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert Investors.
	 *
	 * @param int $post = to get the post content.
	 */
	public function investors_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_investors', true );
		?>
		<div>
			<input type="text" name="deal_investors" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert Article Title.
	 *
	 * @param int $post = to get the post content.
	 */
	public function article_title_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_article_title', true );
		?>
		<div>
			<input type="text" name="deal_article_title" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert Article Link.
	 *
	 * @param int $post = to get the post content.
	 */
	public function article_link_content( $post ) {
		$company_name = get_post_meta( $post->ID, 'deal_article_link', true );
		?>
		<div>
			<input type="url" name="deal_article_link" value="<?php echo isset( $company_name ) ? esc_html( $company_name ) : ''; ?>">
		</div>
		<?php
	}

	/**
	 * Insert custom taxonomy columns.
	 *
	 * @param int $post_id WordPress Post ID.
	 */
	public function save_deal_meta( $post_id ) {

		if ( isset( $_POST['post_type'] ) ) {
			if ( 'deals' === $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}
		}

		if ( isset( $_POST['deal_sectors'] ) ) {
			$md_sectors = sanitize_text_field( wp_unslash( $_POST['deal_sectors'] ) );
			if ( wp_verify_nonce( $md_sectors ) ) {
				return $md_sectors;
			}
		}

		if ( isset( $_POST['deal_launch_year'] ) ) {
			$md_launch_year = sanitize_text_field( wp_unslash( $_POST['deal_launch_year'] ) );
		}

		if ( isset( $_POST['deal_founders'] ) ) {
			$md_founders = sanitize_text_field( wp_unslash( $_POST['deal_founders'] ) );
		}
		if ( isset( $_POST['deal_stage'] ) ) {
			$md_stage = sanitize_text_field( wp_unslash( $_POST['deal_stage'] ) );
		}
		if ( isset( $_POST['deal_funding_amount'] ) ) {
			$md_funding_amount = sanitize_text_field( wp_unslash( $_POST['deal_funding_amount'] ) );
		}
		if ( isset( $_POST['deal_investors'] ) ) {
			$md_investors = sanitize_text_field( wp_unslash( $_POST['deal_investors'] ) );
		}
		if ( isset( $_POST['deal_article_title'] ) ) {
			$md_article_title = sanitize_text_field( wp_unslash( $_POST['deal_article_title'] ) );
		}
		if ( isset( $_POST['deal_article_link'] ) ) {
			$md_article_link = sanitize_text_field( wp_unslash( $_POST['deal_article_link'] ) );
		}
		// save data in INVISIBLE custom field (note the "_" prefixing the custom fields' name.
		update_post_meta( $post_id, 'deal_sectors', $md_sectors );
		update_post_meta( $post_id, 'deal_launch_year', $md_launch_year );
		update_post_meta( $post_id, 'deal_founders', $md_founders );
		update_post_meta( $post_id, 'deal_stage', $md_stage );
		update_post_meta( $post_id, 'deal_funding_amount', $md_funding_amount );
		update_post_meta( $post_id, 'deal_investors', $md_investors );
		update_post_meta( $post_id, 'deal_article_title', $md_article_title );
		update_post_meta( $post_id, 'deal_article_link', $md_article_link );
	}

}
