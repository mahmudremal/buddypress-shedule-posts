<?php
/**
 * Register Menus
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;

class Menus {

	use Singleton;
	private $getIcons = null;

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_filter( 'futurewordpress/project/settings/fields', [ $this, 'menus' ], 10, 1 );
		// add_filter( 'futurewordpress/project/icons/svg', [ $this, 'icons' ], 10, 1 );
		// add_filter( 'futurewordpress/project/job/single/header', [ $this, 'jobHeader' ], 10, 2 );
		// add_filter( 'futurewordpress/project/job/single/social', [ $this, 'jobSocial' ], 10, 2 );
		// add_filter( 'futurewordpress/project/company/single/social', [ $this, 'jobSocial' ], 10, 2 );
		
		// add_filter( 'futurewordpress/project/job/salary', [ $this, 'jobSalary' ], 10, 1 );
		// add_filter( 'futurewordpress/project/job/icons', [ $this, 'jobIcons' ], 10, 2 );
		// add_filter( 'futurewordpress/project/job/apply/link', [ $this, 'jobApplyLink' ], 10, 1 );
		// add_filter( 'futurewordpress/project/job/icon/favourite', [ $this, 'jobFavIcon' ], 10, 2 );
		// add_filter( 'futurewordpress/project/job/save/favourite', [ $this, 'jobFavourite' ], 10, 1 );
		// add_filter( 'futurewordpress/project/job/info/postedon', [ $this, 'jobPosted' ], 10, 1 );
		// add_filter( 'template_include', [ $this, 'jobTemplate' ], 10, 1 );
	}
	public function menus( $args ) {
    // get_fwp_option( 'key', 'default' )
		// is_FwpActive( 'key' )
		$args = [];
		$args['standard'] = [
			'title'					=> __( 'General', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'description'			=> __( 'Generel fields comst commonly used to changed.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_enabled',
					'label'					=> __( 'Enable Shedule posts', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Mark to enable shedule posts features on Buddypress activity post.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_dashboardwidget',
					'label'					=> __( 'Dashboard Widget', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Show Scheduled Posts in Dashboard Widget.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_defaultime',
					'label'			=> __( 'Default Time', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'	=> __( 'Set Default Schedule Time fro activity posts.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'time',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_hidepostnow',
					'label'			=> __( 'Post Immediately', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'	=> __( 'Hide Post Immediately or "post Update" button. If you check it, then buddypress default "post Update" button will be hidden.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> false
				],
			]
		];
		$args['notify'] = [
			'title'					=> __( 'Notification', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'description'			=> __( 'Setup notification information and function which should be enabled on reacting on Shedule posts.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_notifybuddypress',
					'label'					=> __( 'Enable Shedule posts', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Mark to enable shedule posts features on Buddypress activity post.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
			]
		];
		return $args;
	}
	public function icons( $args ) {
		$svgs = [];
		return isset( $svgs[ $args[ 'icon' ] ] ) ? $svgs[ $args[ 'icon' ] ] : false;
	}
	public function jobHeader( $html, $args ) {
		if( ! isset( FUTUREWORDPRESS_PROJECT_OPTIONS[ 'header' ] ) || FUTUREWORDPRESS_PROJECT_OPTIONS[ 'header' ] != 'on' ) {return;}
		$args = wp_parse_args( $args, [
			'post' => [],
			'meta' => [
				'company' => [],
				'jobs' => []
			],
			'terms' => []
		] );
		ob_start();
		?>
		<!-- Candidate Personal Info-->
		<section class="bgc-fa pt80 mt80 mbt45">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-xl-9">
						<div class="candidate_personal_info style2">
							<div class="thumb text-center">
								<img class="img-fluid rounded" src="<?php echo esc_url( (
									isset( $args[ 'meta' ][ 'company' ][ 'logo' ] ) && ! empty( $args[ 'meta' ][ 'company' ][ 'logo' ] ) ? $args[ 'meta' ][ 'company' ][ 'logo' ] : 'logo placeholder'
								) ); ?>" alt="<?php esc_attr_e( 'Logo', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ); ?>"><br><br>
								<a class="mt25" href="<?php echo esc_url( site_url( FUTUREWORDPRESS_PROJECT_CPT_JOB_OPENINGS ) ); ?>">
									<!-- <span class="flaticon-right-arrow pl10"></span> --> <?php esc_html_e( FUTUREWORDPRESS_PROJECT_OPTIONS[ 'view_all_jobs_txt' ], FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ); ?> <?php echo apply_filters( 'futurewordpress/project/job/icons', 'right-arrow', true ); ?>
								</a>
							</div>
							<div class="details">
								<small class="text-thm2"><?php echo esc_html( implode( ' | ', $args[ 'terms' ] ) ); ?></small>
								<h3><?php echo esc_html( $args[ 'post' ]->post_title ); ?></h3>
								<p><?php echo wp_kses_post( apply_filters( 'futurewordpress/project/job/info/postedon', $args ) ); ?></p>
								<ul class="address_list">
									<li class="list-inline-item">
										<a href="<?php echo esc_url( ( isset( $args[ 'meta' ][ 'jobs' ][ 'url' ] ) && ! empty( $args[ 'meta' ][ 'jobs' ][ 'url' ] ) ? $args[ 'meta' ][ 'jobs' ][ 'url' ] : '#' ) ); ?>">
										<span class="fa fa-map-marker-alts"></span>
										<!-- <img src="<?php // echo esc_url( apply_filters( 'futurewordpress/project/job/icons', 'location-pin' ) ); ?>" alt=""> -->
										<?php echo esc_html( ( isset( $args[ 'meta' ][ 'jobs' ][ 'location' ] ) && ! empty( $args[ 'meta' ][ 'jobs' ][ 'location' ] ) ? $args[ 'meta' ][ 'jobs' ][ 'location' ] : ( isset( $args[ 'meta' ][ 'company' ][ 'location' ] ) ? $args[ 'meta' ][ 'company' ][ 'location' ] : '' ) ) ); ?>
										</a>
									</li>
									<?php if( is_FwpActive( 'offered_salary' ) ) : ?>
										<li class="list-inline-item">
											<span>
												<i class="fa fa-money"></i>
												<!-- <img src="<?php // echo esc_url( apply_filters( 'futurewordpress/project/job/icons', 'money-cash' ) ); ?>" alt=""> -->
												<?php echo esc_html( apply_filters( 'futurewordpress/project/job/salary', $args[ 'meta' ][ 'jobs' ] ) ); ?>
											</span>
										</li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-xl-3">
						<div class="candidate_personal_overview style2">
							<?php if( $args[ 'meta' ][ 'jobs' ][ '_status' ] && ! ( $args[ 'post' ]->post_author == get_current_user_id() || $args[ 'meta' ][ 'company' ][ 'authorizeid' ] == get_current_user_id() ) ) : ?>
								<a class="btn btn-block btn-thm mb15" href="<?php echo esc_url( apply_filters( 'futurewordpress/project/job/apply/link', $args ) ); ?>">
									<?php esc_html_e( FUTUREWORDPRESS_PROJECT_OPTIONS[ 'apply_now_txt' ], FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ); ?> <?php // echo apply_filters( 'futurewordpress/project/job/icons', 'right-arrow', true ); ?>
									<span class="flaticon-right-arrow pl10"></span>
								</a>
							<?php endif; ?>
							<?php if( is_FwpActive( 'shortist' ) ) : ?>
								<a class="btn btn-block btn-gray" href="<?php echo esc_url( apply_filters( 'futurewordpress/project/job/save/favourite', $args ) ); ?>">
								<!-- <span class="flaticon-favorites "></span> -->
								<?php echo apply_filters( 'futurewordpress/project/job/icon/favourite', $args, true ); ?> <?php esc_html_e( FUTUREWORDPRESS_PROJECT_OPTIONS[ 'shortlist_txt' ], FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
	public function jobSocial( $html, $args ) {
		$text = urlencode( str_replace( [
			'{post_title}',
			'{post_excerpt}'
		], [
			get_the_title(),
			get_the_excerpt()
		], get_fwp_option( 'job_social_share_title', '{post_title}' ) ) );
		$args = [
			'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( get_the_permalink() ) . '',
			'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode( get_the_permalink() ) . '&text=' . $text,
			'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . urlencode( get_the_permalink() ) . '&media=&description=' . $text,
			'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_the_permalink() ) . '',
			'envelope' => 'mailto:?&subject=&cc=&bcc=&body=' . urlencode( get_the_permalink() ) . '%0A' . $text,
		];
		return $args;
	}
	public function jobTemplate( $template ) {
		global $post;
		if( is_archive() ) {
			if( is_post_type_archive( FUTUREWORDPRESS_PROJECT_CPT_JOB_OPENINGS ) ) {
				$file = FUTUREWORDPRESS_PROJECT_DIR_PATH . '/template-parts/jobs/archive.php';
				if( file_exists( $file ) && ! is_dir( $file ) ) {
					return $file;
				} else {
					return $template;
				}
			} elseif( is_post_type_archive( 'companies' ) ) {
				$file = FUTUREWORDPRESS_PROJECT_DIR_PATH . '/template-parts/company/archive.php';
				if( file_exists( $file ) && ! is_dir( $file ) ) {
					return $file;
				} else {
					return $template;
				}
			} else {
				return $template;
			}
		} elseif( is_single() ) {
			if( 'companies' === get_post_type() ) {
				$file = FUTUREWORDPRESS_PROJECT_DIR_PATH . '/template-parts/company/single.php';
				if( file_exists( $file ) && ! is_dir( $file ) ) {
					return $file;
				} else {
					return $template;
				}
			} elseif ( FUTUREWORDPRESS_PROJECT_CPT_JOB_OPENINGS === get_post_type() ) {
				$file = FUTUREWORDPRESS_PROJECT_DIR_PATH . '/template-parts/jobs/single.php';
				if( file_exists( $file ) && ! is_dir( $file ) ) {
					return $file;
				} else {
					return $template;
				}
			} else {
				return $template;
			}
		} else {
			return $template;
		}
	}
	public function jobSalary( $jobs ) {
		$html = $this->miniNumber( $jobs[ 'salary' ] );
		$html .= ( ! empty( $jobs[ 'salaryto' ] ) ) ? ' - ' . $this->miniNumber( $jobs[ 'salaryto' ] ) : '';
		return $html;
	}
	public function jobIcons( $icon, $load = false ) {
		if( $this->getIcons === null ) {
			$icons = scandir( FUTUREWORDPRESS_PROJECT_DIR_PATH . '/assets/src/icons', true );$getIcons = [];
			foreach( $icons as $i => $ic ) {
				if( ! in_array( $ic, [ '.', '..' ] ) ) {
					$this->getIcons[ pathinfo( $ic, PATHINFO_FILENAME ) ] = $ic;
				}
			}
		}
		return isset( $this->getIcons[ $icon ] ) ? ( 
			( $load ) ? file_get_contents( FUTUREWORDPRESS_PROJECT_DIR_PATH . '/assets/src/icons/' . $this->getIcons[ $icon ] ) : FUTUREWORDPRESS_PROJECT_DIR_URI . '/assets/src/icons/' . $this->getIcons[ $icon ]
			) : false;
	}
	public function miniNumber( $num ) {
		if( $num >= 1000 ) {
			return ( $num / 1000 ) . "k";
	 } else {
			return $num;
	 }
	}
	public function jobApplyLink( $args ) {
		global $wp;
		return site_url(  'apply/application/' . $args[ 'post' ]->ID . '/?_wp_http_referer=' . $wp->request );
	}
	public function jobFavourite( $args ) {
		global $wp;
		return site_url( 'apply/favourite/' . $args[ 'post' ]->ID . '/?_wp_http_referer=' . $wp->request );
	}
	public function jobPosted( $args ) {
		return sprintf( __( 'Posted %s by %s', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ), date( 'd F', strtotime( $args[ 'post' ]->post_date ) ), wp_kses_post( '<a href="' . esc_url( ( isset( $args[ 'meta' ][ 'company' ][ 'url' ] ) && ! empty( $args[ 'meta' ][ 'company' ][ 'url' ] ) ? $args[ 'meta' ][ 'company' ][ 'url' ] : 'javascript:void(0);' ) ) . '" class="text-thm' . ( is_single() ? '2' : '' ) . '">' . ( isset( $args[ 'meta' ][ 'company' ][ 'name' ] ) && ! empty( $args[ 'meta' ][ 'company' ][ 'name' ] ) ? $args[ 'meta' ][ 'company' ][ 'name' ] : __( 'Invisible company', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ) ) . '</a>' ) );
	}
	public function jobFavIcon( $args, $is_read = false ) {
		if( apply_filters( 'futurewordpress/project/job/is/favourite', $args[ 'post' ]->ID ) ) {
			return '<span class="fa fa-star" style="line-height: 47.5px;"></span>';
			// return $this->jobIcons( 'star-fill', $is_read );
		} else {
			return '<span class="flaticon-favorites"></span>';
			// return $this->jobIcons( 'star-o', $is_read );
		}
	}

}
