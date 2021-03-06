<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoAdmin' ) ) {

	class WpssoAdmin {

		protected $p;
		protected $menu_id;
		protected $menu_name;
		protected $menu_lib;
		protected $menu_ext;
		protected $pagehook;
		protected $pageref_url;
		protected $pageref_title;

		public static $pkg = array();
		public static $readme = array();

		public $form = null;
		public $lang = array();
		public $submenu = array();

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			/**
			 * The WpssoScript add_iframe_inline_script() method includes jQuery in the thickbox iframe 
			 * to add the iframe_parent arguments when the Install or Update button is clicked.
			 *
			 * These class properties are used by both the WpssoAdmin plugin_complete_actions() and 
			 * plugin_complete_redirect() methods to direct the user back to the thickbox iframe parent
			 * (aka the plugin licenses settings page) after plugin installation / activation / update.
			 */
			foreach ( array(
				'pageref_url'   => 'esc_url_raw',
				'pageref_title' => 'esc_html',
			) as $pageref => $esc_func ) {

				if ( ! empty( $_GET[$this->p->lca . '_' . $pageref] ) ) {
					$this->$pageref = call_user_func( $esc_func, urldecode( $_GET[$this->p->lca . '_' . $pageref] ) );
				}
			}

			add_action( 'activated_plugin', array( $this, 'reset_check_head_count' ), 10 );
			add_action( 'after_switch_theme', array( $this, 'reset_check_head_count' ), 10 );
			add_action( 'upgrader_process_complete', array( $this, 'reset_check_head_count' ), 10 );

			add_action( 'after_switch_theme', array( $this, 'check_tmpl_head_attributes' ), 20 );
			add_action( 'upgrader_process_complete', array( $this, 'check_tmpl_head_attributes' ), 20 );

			if ( SucomUtil::get_const( 'DOING_AJAX' ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'DOING_AJAX is true' );
				}

				/**
				 * Nothing to do.
				 */

			} else {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'DOING_AJAX is false' );
				}

				/**
				 * The admin_menu action is run before admin_init.
				 */
				add_action( 'admin_menu', array( $this, 'load_menu_objects' ), -1000 );
				add_action( 'admin_menu', array( $this, 'add_admin_menus' ), WPSSO_ADD_MENU_PRIORITY );
				add_action( 'admin_menu', array( $this, 'add_admin_submenus' ), WPSSO_ADD_SUBMENU_PRIORITY );
				add_action( 'admin_init', array( $this, 'add_plugins_page_upgrade_notice' ) );
				add_action( 'admin_init', array( $this, 'register_setting' ) );

				/**
				 * Hook in_admin_header to allow for setting changes, plugin activation / loading, etc.
				 */
				add_action( 'in_admin_header', array( $this, 'conflict_warnings' ), 10 );
				add_action( 'in_admin_header', array( $this, 'required_notices' ), 20 );
				add_action( 'in_admin_header', array( $this, 'update_count_notice' ), 30 );

				/**
				 * WPSSO_TOOLBAR_NOTICES can be true, false, or an array of notice types to include in the menu.
				 */
				if ( SucomUtil::get_const( 'WPSSO_TOOLBAR_NOTICES', false ) ) {	// Returns false if not defined.
					add_action( 'admin_bar_menu', array( $this, 'add_admin_tb_notices_menu_item' ), WPSSO_TB_NOTICE_MENU_ORDER );
				}

				add_filter( 'current_screen', array( $this, 'maybe_show_screen_notices' ) );
				add_filter( 'plugin_action_links', array( $this, 'append_plugins_action_links' ), 10, 2 );
				add_filter( 'wp_redirect', array( $this, 'profile_updated_redirect' ), -100, 2 );

				if ( is_multisite() ) {
					add_action( 'network_admin_menu', array( $this, 'load_network_menu_objects' ), -1000 );
					add_action( 'network_admin_menu', array( $this, 'add_network_admin_menus' ), WPSSO_ADD_MENU_PRIORITY );
					add_action( 'network_admin_edit_' . WPSSO_SITE_OPTIONS_NAME, array( $this, 'save_site_options' ) );
					add_filter( 'network_admin_plugin_action_links', array( $this, 'append_site_plugins_action_links' ), 10, 2 );
				}

		 		/**
				 * Provide plugin data / information from the readme.txt for additional add-ons.
				 * Don't hook the 'plugins_api_result' filter if the update manager is active as it
				 * provides more complete plugin data than what's available from the readme.txt.
				 */
				if ( empty( $this->p->avail['p_ext']['um'] ) ) {	// Since um v1.6.0.
					add_filter( 'plugins_api_result', array( $this, 'external_plugin_data' ), 1000, 3 );	// Since wp v2.7.
				}

				add_filter( 'http_request_args', array( $this, 'add_expect_header' ), 1000, 2 );
				add_filter( 'http_request_host_is_external', array( $this, 'maybe_allow_hosts' ), 1000, 3 );
				add_filter( 'install_plugin_complete_actions', array( $this, 'plugin_complete_actions' ), 1000, 1 );
				add_filter( 'update_plugin_complete_actions', array( $this, 'plugin_complete_actions' ), 1000, 1 );
				add_filter( 'wp_redirect', array( $this, 'plugin_complete_redirect' ), 1000, 1 );
			}
		}

		public function load_network_menu_objects() {
			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
			// some network menu pages extend the site menu pages
			$this->load_menu_objects( array( 'submenu', 'sitesubmenu' ) );
		}

		public function load_menu_objects( $menu_libs = array() ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->set_plugin_pkg_info();

			if ( empty( $menu_libs ) ) {
				// 'setting' array element must follow 'submenu' to extend submenu/advanced.php
				$menu_libs = array( 'submenu', 'setting', 'profile' );
			}

			foreach ( $menu_libs as $menu_lib ) {	// profile, setting, submenu, or sitesubmenu
				foreach ( $this->p->cf['plugin'] as $ext => $info ) {
					if ( ! isset( $info['lib'][$menu_lib] ) ) {	// not all add-ons have submenus
						continue;
					}
					foreach ( $info['lib'][$menu_lib] as $menu_id => $menu_name ) {
						$classname = apply_filters( $ext . '_load_lib', false, $menu_lib . '/' . $menu_id );
						if ( is_string( $classname ) && class_exists( $classname ) ) {
							if ( ! empty( $info['text_domain'] ) ) {
								$menu_name = _x( $menu_name, 'lib file description', $info['text_domain'] );
							}
							$this->submenu[$menu_id] = new $classname( $this->p, $menu_id, $menu_name, $menu_lib, $ext );
						}
					}
				}
			}
		}

		public function set_plugin_pkg_info() {

			if ( ! empty( self::$pkg ) ) {
				return;
			}

			$has_pdir = $this->p->avail['*']['p_dir'];
			$has_aop = $this->p->check->aop( $this->p->lca, true, $has_pdir );

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				self::$pkg[$ext]['pdir'] = $this->p->check->aop( $ext, false, $has_pdir );

				self::$pkg[$ext]['aop'] = ! empty( $this->p->options['plugin_' . $ext . '_tid'] ) &&
					$has_aop && $this->p->check->aop( $ext, true, WPSSO_UNDEF_INT ) === WPSSO_UNDEF_INT ? true : false;

				self::$pkg[$ext]['type'] = self::$pkg[$ext]['aop'] ?
					_x( 'Pro', 'package type', 'wpsso' ) : _x( 'Free', 'package type', 'wpsso' );

				self::$pkg[$ext]['short'] = $info['short'] . ' ' . self::$pkg[$ext]['type'];

				self::$pkg[$ext]['name'] = SucomUtil::get_pkg_name( $info['name'], self::$pkg[$ext]['type'] );

				self::$pkg[$ext]['status'] = self::$pkg[$ext]['aop'] ? 'L' : ( self::$pkg[$ext]['pdir'] ? 'U' : 'F' );

				self::$pkg[$ext]['gen'] = $info['short'] . ' ' . ( isset( $info['version'] ) ?
					$info['version'] . '/' . self::$pkg[$ext]['status'] : '' );
			}
		}

		public function add_network_admin_menus() {
			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
			$this->add_admin_menus( 'sitesubmenu' );
		}

		/**
		 * Add a new main menu, and its sub-menu items.
		 *
		 * $menu_lib = profile | setting | submenu | sitesubmenu
		 */
		public function add_admin_menus( $menu_lib = '' ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( empty( $menu_lib ) ) {
				$menu_lib = 'submenu';
			}

			$libs = $this->p->cf['*']['lib'][$menu_lib];
			$this->menu_id = key( $libs );
			$this->menu_name = $libs[$this->menu_id];
			$this->menu_lib = $menu_lib;
			$this->menu_ext = $this->p->lca;

			if ( isset( $this->submenu[$this->menu_id] ) ) {
				$menu_slug = $this->p->lca . '-' . $this->menu_id;
				$this->submenu[$this->menu_id]->add_menu_page( $menu_slug );
			}

			$sorted_menu = array();
			$unsorted_menu = array();

			$first_top_id = false;
			$last_top_id = false;
			$first_ext_id = false;
			$last_ext_id = false;

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( ! isset( $info['lib'][$menu_lib] ) ) {	// not all add-ons have submenus
					continue;
				}

				foreach ( $info['lib'][$menu_lib] as $menu_id => $menu_name ) {

					$ksort_key = $menu_name . '-' . $menu_id;
					$parent_slug = $this->p->lca . '-' . $this->menu_id;

					if ( $ext === $this->p->lca ) {
						$unsorted_menu[] = array( $parent_slug, $menu_id, $menu_name, $menu_lib, $ext );
						if ( false === $first_top_id ) {
							$first_top_id = $menu_id;
						}
						$last_top_id = $menu_id;
					} else {
						$sorted_menu[$ksort_key] = array( $parent_slug, $menu_id, $menu_name, $menu_lib, $ext );
						if ( false === $first_ext_id ) {
							$first_ext_id = $menu_id;
						}
						$last_ext_id = $menu_id;
					}
				}
			}

			ksort( $sorted_menu );

			foreach ( array_merge( $unsorted_menu, $sorted_menu ) as $key => $arg ) {

				if ( $arg[1] === $first_top_id ) {
					$css_class = 'first-top-submenu-page';
				} elseif ( $arg[1] === $last_top_id ) {
					$css_class = 'last-top-submenu-page';
					if ( empty( $first_ext_id ) ) {
						$css_class .= ' no-add-ons';
					} else {
						$css_class .= ' with-add-ons';
					}
				} elseif ( $arg[1] === $first_ext_id ) {
					$css_class = 'first-ext-submenu-page';
				} elseif ( $arg[1] === $last_ext_id ) {
					$css_class = 'last-ext-submenu-page';
				} else {
					$css_class = '';
				}

				if ( isset( $this->submenu[$arg[1]] ) ) {
					$this->submenu[$arg[1]]->add_submenu_page( $arg[0], '', '', '', '', $css_class );
				} else {
					$this->add_submenu_page( $arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $css_class );
				}
			}
		}

		/**
		 * Add sub-menu items to existing menus (profile and setting).
		 */
		public function add_admin_submenus() {

			foreach ( array( 'profile', 'setting' ) as $menu_lib ) {

				// match WordPress behavior (users page for admins, profile page for everyone else)
				if ( $menu_lib === 'profile' && current_user_can( 'list_users' ) ) {
					$parent_slug = $this->p->cf['wp']['admin']['users']['page'];
				} else {
					$parent_slug = $this->p->cf['wp']['admin'][$menu_lib]['page'];
				}

				$sorted_menu = array();

				foreach ( $this->p->cf['plugin'] as $ext => $info ) {
					if ( ! isset( $info['lib'][$menu_lib] ) ) {	// not all add-ons have submenus
						continue;
					}
					foreach ( $info['lib'][$menu_lib] as $menu_id => $menu_name ) {
						$ksort_key = $menu_name . '-' . $menu_id;
						$sorted_menu[$ksort_key] = array( $parent_slug, $menu_id, $menu_name, $menu_lib, $ext );
					}
				}

				ksort( $sorted_menu );

				foreach ( $sorted_menu as $key => $arg ) {
					if ( isset( $this->submenu[$arg[1]] ) ) {
						$this->submenu[$arg[1]]->add_submenu_page( $arg[0] );
					} else {
						$this->add_submenu_page( $arg[0], $arg[1], $arg[2], $arg[3], $arg[4] );
					}
				}
			}
		}

		/**
		 * Called by show_setting_page() and extended by the sitesubmenu classes to load site options instead.
		 */
		protected function set_form_object( $menu_ext ) {	// $menu_ext required for text_domain

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
				$this->p->debug->log( 'setting form object for ' . $menu_ext );
			}

			$def_opts = $this->p->opt->get_defaults();

			$this->form = new SucomForm( $this->p, WPSSO_OPTIONS_NAME, $this->p->options, $def_opts, $menu_ext );
		}

		protected function &get_form_object( $menu_ext ) {	// $menu_ext required for text_domain
			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
			if ( ! isset( $this->form ) ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'form object not defined' );
				}
				$this->set_form_object( $menu_ext );
			} elseif ( $this->form->get_menu_ext() !== $menu_ext ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'form object text domain does not match' );
				}
				$this->set_form_object( $menu_ext );
			}
			return $this->form;
		}

		public function register_setting() {
			register_setting( $this->p->lca . '_setting', WPSSO_OPTIONS_NAME, array( $this, 'registered_setting_sanitation' ) );
		}

		public function add_plugins_page_upgrade_notice() {
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( ! empty( $info['base'] ) ) {
					add_action( 'in_plugin_update_message-' . $info['base'], array( $this, 'show_upgrade_notice' ), 10, 2 );
				}
			}
		}

		public function show_upgrade_notice( $data, $response ) {
			if ( isset( $data['upgrade_notice'] ) ) {	// Just in case.
				echo '<span style="display:table;border-collapse:collapse;margin-left:26px;">';
				echo '<span style="display:table-cell;">' . strip_tags( $data['upgrade_notice'] ) . '</span>';
				echo '</span>';
			}
		}

		protected function add_menu_page( $menu_slug ) {
			global $wp_version;

			$page_title = self::$pkg[$this->p->lca]['short'] . ' &mdash; ' . $this->menu_name;
			$menu_title = _x( $this->p->cf['menu']['title'], 'menu title', 'wpsso' ) . ' ' . self::$pkg[$this->p->lca]['type'];	// pre-translated
			$cap_name = isset( $this->p->cf['wp']['admin'][$this->menu_lib]['cap'] ) ? $this->p->cf['wp']['admin'][$this->menu_lib]['cap'] : 'manage_options';
			$icon_url = version_compare( $wp_version, '3.8', '>=' ) ? 'dashicons-share' : null;
			$function = array( $this, 'show_setting_page' );

			$this->pagehook = add_menu_page( $page_title, $menu_title, $cap_name, $menu_slug, $function, $icon_url, WPSSO_MENU_ORDER );

			add_action( 'load-' . $this->pagehook, array( $this, 'load_setting_page' ) );
		}

		protected function add_submenu_page( $parent_slug, $menu_id = '', $menu_name = '', $menu_lib = '', $menu_ext = '', $css_class = '' ) {

			if ( empty( $menu_id ) ) {
				$menu_id = $this->menu_id;
			}

			if ( empty( $menu_name ) ) {
				$menu_name = $this->menu_name;
			}

			if ( empty( $menu_lib ) ) {
				$menu_lib = $this->menu_lib;
			}

			if ( empty( $menu_ext ) ) {
				$menu_ext = $this->menu_ext;	// lowercase acronyn for plugin or add-on
				if ( empty( $menu_ext ) ) {
					$menu_ext = $this->p->lca;
				}
			}

			global $wp_version;

			/**
			 * WordPress version 3.8 is required for dashicons.
			 */
			if ( ( $menu_lib === 'submenu' || $menu_lib === 'sitesubmenu' ) && version_compare( $wp_version, '3.8', '>=' ) ) {

				if ( empty( $this->p->cf['menu']['dashicons'][$menu_id] ) ) {
					if ( $menu_ext === $this->p->lca ) {
						$dashicon = 'admin-settings';	// use settings dashicon by default
					} else {
						$dashicon = 'admin-plugins';	// use plugin dashicon by default for add-ons
					}
				} else {
					$dashicon = $this->p->cf['menu']['dashicons'][$menu_id];
				}

				$css_class = $this->p->lca . '-menu-item' . ( $css_class ? ' ' . $css_class : '' );
				$menu_title = '<div class="' . $css_class . ' dashicons-before dashicons-' . $dashicon . '"></div>' .
					'<div class="' . $css_class . ' menu-item-label">' . $menu_name . '</div>';

			} else {
				$menu_title = $menu_name;
			}
	
			$page_title = self::$pkg[$menu_ext]['short'] . ' &mdash; ' . $menu_name;
			$cap_name = isset( $this->p->cf['wp']['admin'][$menu_lib]['cap'] ) ? $this->p->cf['wp']['admin'][$menu_lib]['cap'] : 'manage_options';
			$menu_slug = $this->p->lca . '-' . $menu_id;
			$function = array( $this, 'show_setting_page' );

			$this->pagehook = add_submenu_page( $parent_slug, $page_title, $menu_title, $cap_name, $menu_slug, $function );

			if ( $function ) {
				add_action( 'load-' . $this->pagehook, array( $this, 'load_setting_page' ) );
			}
		}

		public function append_site_plugins_action_links( $links, $plugin_base, $menu_lib = 'sitesubmenu' ) {

			return $this->append_plugins_action_links( $links, $plugin_base, $menu_lib );
		}

		public function append_plugins_action_links( $links, $plugin_base, $menu_lib = 'submenu'  ) {

			if ( ! isset( $this->p->cf['*']['base'][$plugin_base] ) ) {
				return $links;
			}

			foreach ( $links as $num => $val ) {
				if ( strpos( $val, '>Edit<' ) !== false ) {
					unset ( $links[$num] );
				}
			}

			$ext = $this->p->cf['*']['base'][$plugin_base];

			$settings_page  = empty( $this->p->cf['plugin'][$ext]['lib'][$menu_lib] ) ? '' : key( $this->p->cf['plugin'][$ext]['lib'][$menu_lib] );
			$licenses_page  = 'sitesubmenu' === $menu_lib ? 'sitelicenses' : 'licenses';
			$dashboard_page = 'sitesubmenu' === $menu_lib ? '' : 'dashboard';

			if ( ! empty( $settings_page ) ) {

				if ( $ext === $this->p->lca ) {	// Only add for the core plugin.
					$settings_page_transl = _x( $this->p->cf['plugin'][$ext]['lib'][$menu_lib][$settings_page], 'lib file description', 'wpsso' );
					$settings_label_transl = sprintf( _x( '%s Settings', 'plugin action link', 'wpsso' ), $settings_page_transl );
				} else {
					$settings_label_transl = _x( 'Add-on Settings', 'plugin action link', 'wpsso' );
				}

				$links[] = '<a href="' . $this->p->util->get_admin_url( $settings_page ) . '">' . $settings_label_transl . '</a>';
			}

			if ( ! empty( $licenses_page ) ) {
				if ( $ext === $this->p->lca ) {	// Only add for the core plugin.
					$links[] = '<a href="' . $this->p->util->get_admin_url( $licenses_page ) . '">' . 
						_x( 'Add-ons', 'plugin action link', 'wpsso' ) . '</a>';
				}
			}

			if ( ! empty( $dashboard_page ) ) {
				if ( $ext === $this->p->lca ) {	// Only add for the core plugin.
					$links[] = '<a href="' . $this->p->util->get_admin_url( $dashboard_page ) . '">' . 
						_x( 'Dashboard', 'plugin action link', 'wpsso' ) . '</a>';
				}
			}

			return $links;
		}

		public function append_licenses_action_links( $links, $plugin_base, &$tabindex = false ) {

			if ( ! isset( $this->p->cf['*']['base'][$plugin_base] ) ) {
				return $links;
			}

			$ext = $this->p->cf['*']['base'][$plugin_base];
			$info = $this->p->cf['plugin'][$ext];
			$tabindex = is_integer( $tabindex ) ? $tabindex : false;	// Just in case.

			foreach ( $links as $num => $val ) {
				if ( strpos( $val, '>Edit<' ) !== false ) {
					unset ( $links[$num] );
				}
			}

			if ( ! empty( $info['url']['faqs'] ) ) {
				$links[] = '<a href="' . $info['url']['faqs'] . '"' .
					( $tabindex !== false ? ' tabindex="' . ++$tabindex . '"' : '' ) . '>' .
						_x( 'FAQs', 'plugin action link', 'wpsso' ) . '</a>';
			}

			if ( ! empty( $info['url']['notes'] ) ) {
				$links[] = '<a href="' . $info['url']['notes'] . '"' .
					( $tabindex !== false ? ' tabindex="' . ++$tabindex . '"' : '' ) . '>' .
						_x( 'Other Notes', 'plugin action link', 'wpsso' ) . '</a>';
			}

			if ( ! empty( $info['url']['support'] ) && self::$pkg[$ext]['aop'] ) {
				$links[] = '<a href="' . $info['url']['support'] . '"' .
					( $tabindex !== false ? ' tabindex="' . ++$tabindex . '"' : '' ) . '>' .
						_x( 'Pro Support', 'plugin action link', 'wpsso' ) . '</a>';

			} elseif ( ! empty( $info['url']['forum'] ) ) {
				$links[] = '<a href="' . $info['url']['forum'] . '"' .
					( $tabindex !== false ? ' tabindex="' . ++$tabindex . '"' : '' ) . '>' .
						_x( 'Community Forum', 'plugin action link', 'wpsso' ) . '</a>';
			}

			if ( ! empty( $info['url']['purchase'] ) ) {

				$purchase_url = add_query_arg( 'utm_source', 'licenses-action-links', $info['url']['purchase'] );

				$links[] = $this->p->msgs->get( 'pro-purchase-link', array(
					'ext' => $ext,
					'url' => $purchase_url, 
					'tabindex' => ( $tabindex !== false ? ++$tabindex : false )
				) );
			}

			return $links;
		}

		/**
		 * Define and disable the "Expect: 100-continue" header. $req should be an array,
		 * so make sure other filters aren't giving us a string or boolean.
		 */
		public function add_expect_header( $req, $url ) {
			if ( ! is_array( $req ) ) {
				$req = array();
			}
			if ( ! isset( $req['headers'] ) || ! is_array( $req['headers'] ) ) {
				$req['headers'] = array();
			}
			$req['headers']['Expect'] = '';
			return $req;
		}

		public function maybe_allow_hosts( $is_allowed, $ip, $url ) {
			if ( $is_allowed ) {	// Already allowed.
				return $is_allowed;
			}
			if ( isset( $this->p->cf['extend'] ) ) {
				foreach ( $this->p->cf['extend'] as $host ) {
					if ( strpos( $url, $host ) === 0 ) {
						return true;
					}
				}
			}
			return $is_allowed;
		}

		/**
		 * Provide plugin data / information from the readme.txt for additional add-ons.
		 */
		public function external_plugin_data( $res, $action = null, $args = null ) {

			if ( $action !== 'plugin_information' ) {	// this filter only provides plugin data
				return $res;
			} elseif ( empty( $args->slug ) ) {	// make sure we have a slug in the request
				return $res;
			} elseif ( empty( $this->p->cf['*']['slug'][$args->slug] ) ) {	// make sure the plugin slug is one of ours
				return $res;
			} elseif ( isset( $res->slug ) && $res->slug === $args->slug ) {	// if the object from WordPress looks complete, return it as-is
				return $res;
			}

			/**
			 * Get the add-on acronym to read its config.
			 */
			$ext = $this->p->cf['*']['slug'][$args->slug];

			/**
			 * Make sure we have a config for that slug.
			 */
			if ( empty( $this->p->cf['plugin'][$ext] ) ) {
				return $res;
			}

			/**
			 * Get plugin data from the plugin readme.
			 */
			$plugin_data = $this->get_plugin_data( $ext, true );

			/**
			 * Make sure we have something to return.
			 */
			if ( empty( $plugin_data ) ) {
				return $res;
			}

			/**
			 * Let WordPress known that this is not a wordpress.org plugin.
			 */
			$plugin_data->external = true;

			return $plugin_data;
		}

		/**
		 * Get the plugin readme and convert array elements to a plugin data object.
		 */
		public function get_plugin_data( $ext, $read_cache = true ) {

			$data = new StdClass;
			$info = $this->p->cf['plugin'][$ext];
			$readme = $this->get_readme_info( $ext, $read_cache );

			// make sure we got something back
			if ( empty( $readme ) ) {
				return array();
			}

			foreach ( array(
				// readme array => plugin object
				'plugin_name' => 'name',
				'plugin_slug' => 'slug',
				'base' => 'plugin',
				'stable_tag' => 'version',
				'tested_up_to' => 'tested',
				'requires_at_least' => 'requires',
				'home' => 'homepage',
				'latest' => 'download_link',
				'author' => 'author',
				'upgrade_notice' => 'upgrade_notice',
				'last_updated' => 'last_updated',
				'sections' => 'sections',
				'remaining_content' => 'other_notes',	// added to sections
				'banners' => 'banners',
			) as $key_name => $prop_name ) {
				switch ( $key_name ) {
					case 'base':	// from plugin config
						if ( ! empty( $info[$key_name] ) ) {
							$data->$prop_name = $info[$key_name];
						}
						break;
					case 'home':	// from plugin config
						if ( ! empty( $info['url']['purchase'] ) ) {	// check for purchase url first
							$data->$prop_name = $info['url']['purchase'];
							break;
						}
						// no break - override with 'home' url from config (if one is defined)
					case 'latest':	// from plugin config
						if ( ! empty( $info['url'][$key_name] ) ) {
							$data->$prop_name = $info['url'][$key_name];
						}
						break;
					case 'banners':	// from plugin config
						if ( ! empty( $info['img'][$key_name] ) ) {
							$data->$prop_name = $info['img'][$key_name];	// array with low/high images
						}
						break;
					case 'remaining_content':
						if ( ! empty( $readme[$key_name] ) ) {
							$data->sections[$prop_name] = $readme[$key_name];
						}
						break;
					default:
						if ( ! empty( $readme[$key_name] ) ) {
							$data->$prop_name = $readme[$key_name];
						}
						break;
				}
			}
			return $data;
		}

		/**
		 * This method receives only a partial options array, so re-create a full one.
		 * WordPress handles the actual saving of the options to the database table.
		 */
		public function registered_setting_sanitation( $opts ) {

			$network = false;

			if ( ! is_array( $opts ) ) {
				add_settings_error( WPSSO_OPTIONS_NAME, 'notarray', '<b>' . strtoupper( $this->p->lca ) . ' Error</b> : ' .
					__( 'Submitted options are not an array.', 'wpsso' ), 'error' );
				return $opts;
			}

			$def_opts = $this->p->opt->get_defaults();	// Get default values, including css from default stylesheets.

			$this->p->notice->trunc();	// Clear all messages before sanitation checks.

			$opts = SucomUtil::restore_checkboxes( $opts );
			$opts = array_merge( $this->p->options, $opts );
			$opts = $this->p->opt->sanitize( $opts, $def_opts, $network );	// Sanitation updates image width/height info.
			$opts = apply_filters( $this->p->lca . '_save_options', $opts, WPSSO_OPTIONS_NAME, $network, false );	// $doing_upgrade is false.

			if ( empty( $this->p->options['plugin_clear_on_save'] ) ) {

				/**
				 * Note that get_admin_url() will use the essential settings URL if we're not on a settings page.
				 */
				$clear_cache_link = $this->p->util->get_admin_url( wp_nonce_url( '?' . $this->p->lca . '-action=clear_all_cache',
					WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME ), _x( 'Clear All Caches', 'submit button', 'wpsso' ) );
	
				$this->p->notice->upd( '<strong>' . __( 'Plugin settings have been saved.', 'wpsso' ) . '</strong> <em>' .
					__( 'Please note that webpage content may take several days to reflect changes.', 'wpsso' ) . ' ' .
						sprintf( __( '%s now to force a refresh.', 'wpsso' ), $clear_cache_link ) . '</em>' );

			} else {

				$dismiss_key = 'settings-saved-clear-all-cache-and-external';

				$this->p->util->clear_all_cache( true, null, null, $dismiss_key );

				$this->p->notice->upd( '<strong>' . __( 'Plugin settings have been saved.', 'wpsso' ) . '</strong> ' .
					sprintf( __( 'All caches have been cleared (the %s option is enabled).', 'wpsso' ),
						$this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_cache',
							_x( 'Clear All Caches on Save Settings', 'option label', 'wpsso' ) ) ) );
			}

			if ( empty( $opts['plugin_filter_content'] ) ) {
				$message_key = 'notice-content-filters-disabled';
				$dismiss_key = $message_key . '-reminder';
				$this->p->notice->warn( $this->p->msgs->get( $message_key ), true, $dismiss_key, true );	// can be dismissed
			}

			$this->check_tmpl_head_attributes();

			return $opts;
		}

		public function save_site_options() {

			$network = true;

			if ( ! $page = SucomUtil::get_request_value( 'page', 'POST' ) ) {	// uses sanitize_text_field
				$page = key( $this->p->cf['*']['lib']['sitesubmenu'] );
			}

			if ( empty( $_POST[ WPSSO_NONCE_NAME ] ) ) {	// WPSSO_NONCE_NAME is an md5() string
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'nonce token validation post field missing' );
				}
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! wp_verify_nonce( $_POST[ WPSSO_NONCE_NAME ], WpssoAdmin::get_nonce_action() ) ) {
				$this->p->notice->err( __( 'Nonce token validation failed for network options (update ignored).', 'wpsso' ) );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! current_user_can( 'manage_network_options' ) ) {
				$this->p->notice->err( __( 'Insufficient privileges to modify network options.', 'wpsso' ) );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			}

			$this->p->notice->trunc();	// Clear all notices before sanitation checks.

			$def_opts = $this->p->opt->get_site_defaults();

			$opts = empty( $_POST[WPSSO_SITE_OPTIONS_NAME] ) ? $def_opts : SucomUtil::restore_checkboxes( $_POST[WPSSO_SITE_OPTIONS_NAME] );
			$opts = array_merge( $this->p->site_options, $opts );
			$opts = $this->p->opt->sanitize( $opts, $def_opts, $network );
			$opts = apply_filters( $this->p->lca . '_save_site_options', $opts, $def_opts, $network );

			update_site_option( WPSSO_SITE_OPTIONS_NAME, $opts );

			$this->p->notice->upd( '<strong>' . __( 'Plugin settings have been saved.', 'wpsso' ) . '</strong>' );

			wp_redirect( $this->p->util->get_admin_url( $page ) . '&settings-updated=true' );

			exit;	// stop after redirect
		}

		public function load_setting_page() {

			$action_query = $this->p->lca . '-action';

			wp_enqueue_script( 'postbox' );

			if ( ! empty( $_GET[$action_query] ) ) {

				$_SERVER['REQUEST_URI'] = remove_query_arg( array( $action_query, WPSSO_NONCE_NAME ) );
				$action_name = SucomUtil::sanitize_hookname( $_GET[$action_query] );

				if ( empty( $_GET[ WPSSO_NONCE_NAME ] ) ) {	// WPSSO_NONCE_NAME is an md5() string

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'nonce token validation query field missing' );
					}

				} elseif ( ! wp_verify_nonce( $_GET[ WPSSO_NONCE_NAME ], WpssoAdmin::get_nonce_action() ) ) {

					$this->p->notice->err( sprintf( __( 'Nonce token validation failed for %1$s action "%2$s".',
						'wpsso' ), 'admin', $action_name ) );

				} else {

					switch ( $action_name ) {

						case 'clear_all_cache':

							$this->p->util->clear_all_cache( true );	// $clear_external is true.
							break;

						case 'clear_all_cache_and_short_urls':

							$this->p->util->clear_all_cache( true, true );	// $clear_external is true.
							break;

						case 'clear_metabox_prefs':

							$user_id = get_current_user_id();
							$user = get_userdata( $user_id );
							$user_name = $user->display_name;
							WpssoUser::delete_metabox_prefs( $user_id );
							$this->p->notice->upd( sprintf( __( 'Metabox layout preferences for user ID #%d "%s" have been reset.',
								'wpsso' ), $user_id, $user_name ) );
							break;

						case 'clear_hidden_notices':

							$user_id = get_current_user_id();
							$user = get_userdata( $user_id );
							$user_name = $user->display_name;
							delete_user_option( $user_id, WPSSO_DISMISS_NAME, false );	// $global = false
							delete_user_option( $user_id, WPSSO_DISMISS_NAME, true );	// $global = true
							$this->p->notice->upd( sprintf( __( 'Hidden notices for user ID #%d "%s" have been cleared.',
								'wpsso' ), $user_id, $user_name ) );
							break;

						case 'change_show_options':

							$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'show-opts' ) );

							if ( isset( $this->p->cf['form']['show_options'][$_GET['show-opts']] ) ) {
								$this->p->notice->upd( sprintf( __( 'Option preference saved &mdash; viewing "%s" by default.',
									'wpsso' ), $this->p->cf['form']['show_options'][$_GET['show-opts']] ) );
								WpssoUser::save_pref( array( 'show_opts' => $_GET['show-opts'] ) );
							}
							break;

						case 'modify_tmpl_head_attributes':

							$this->modify_tmpl_head_attributes();
							break;

						case 'reload_default_sizes':

							$opts =& $this->p->options;	// Update the existing options array.
							$def_opts = $this->p->opt->get_defaults();
							$img_opts = SucomUtil::preg_grep_keys( '/_img_(width|height|crop|crop_x|crop_y)$/', $def_opts );
							$opts = array_merge( $this->p->options, $img_opts );
							$this->p->opt->save_options( WPSSO_OPTIONS_NAME, $opts );
							$this->p->notice->upd( __( 'All image dimensions have been reloaded with their default value and saved.',
								'wpsso' ) );
							break;

						default:

							do_action( $this->p->lca . '_load_setting_page_' . $action_name,
								$this->pagehook, $this->menu_id, $this->menu_name, $this->menu_lib );
							break;
					}
				}
			}

			$this->add_footer_hooks();	// Include add-on name and version to settings page footer.
			$this->add_plugin_hooks();
			$this->add_side_meta_boxes();	// Add side metaboxes before main metaboxes.
			$this->add_meta_boxes();	// Add last to move any duplicate side metaboxes.
		}

		protected function add_footer_hooks() {
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_ext_name' ) );
			add_filter( 'update_footer', array( $this, 'admin_footer_ext_gen' ) );
		}

		protected function add_plugin_hooks() {
			// method is extended by each submenu page
		}

		protected function add_side_meta_boxes() {
			if ( ! self::$pkg[$this->p->lca]['aop'] ) {
				add_meta_box( $this->pagehook . '_purchase_pro', _x( 'Pro Version Available', 'metabox title', 'wpsso' ),
					array( $this, 'show_metabox_purchase_pro' ), $this->pagehook, 'side_fixed' );
				add_meta_box( $this->pagehook . '_status_pro', _x( 'Pro Version Features', 'metabox title', 'wpsso' ),
					array( $this, 'show_metabox_status_pro' ), $this->pagehook, 'side' );
				WpssoUser::reset_metabox_prefs( $this->pagehook, array( 'purchase_pro' ), '', '', true );
			}
		}

		protected function add_meta_boxes() {
			// method is extended by each submenu page
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {
			// method is extended by each submenu page
		}

		/**
		 * Called from the add_meta_boxes() method in specific settings pages (essential, general, etc.).
		 */
		protected function maybe_show_language_notice() {

			$current_locale = SucomUtil::get_locale( 'current' );
			$default_locale = SucomUtil::get_locale( 'default' );

			if ( $current_locale && $default_locale && $current_locale !== $default_locale ) {

				$dismiss_key = $this->menu_id . '-language-notice-current-' . $current_locale . '-default-' . $default_locale;

				$this->p->notice->inf( sprintf( __( 'Please note that your current language is different from the default site language (%s).', 'wpsso' ), $default_locale ) . ' ' . sprintf( __( 'Localized option values (%s) are used for webpages and content in that language only (not for the default language, or any other language).', 'wpsso' ), $current_locale ), true, $dismiss_key, true );
			}
		}

		public function show_setting_page() {

			if ( ! $this->is_setting() ) {
				settings_errors( WPSSO_OPTIONS_NAME );
			}

			$menu_ext = $this->menu_ext;	// lowercase acronyn for plugin or add-on

			if ( empty( $menu_ext ) ) {
				$menu_ext = $this->p->lca;
			}

			$this->get_form_object( $menu_ext );

			echo '<div class="wrap" id="' . $this->pagehook . '">' . "\n";
			echo '<h1>';
			echo self::$pkg[$this->menu_ext]['short'] . ' ';
			echo '<span class="qualifier">&ndash; ';
			echo $this->menu_name;
			echo '</span></h1>' . "\n";

			if ( ! self::$pkg[$this->p->lca]['aop'] ) {
				echo '<div id="poststuff" class="metabox-holder has-right-sidebar">' . "\n";
				echo '<div id="side-info-column" class="inner-sidebar">' . "\n";

				do_meta_boxes( $this->pagehook, 'side_top', null );
				do_meta_boxes( $this->pagehook, 'side_fixed', null );
				do_meta_boxes( $this->pagehook, 'side', null );

				echo '</div><!-- #side-info-column -->' . "\n";
				echo '<div id="post-body" class="has-sidebar">' . "\n";
				echo '<div id="post-body-content" class="has-sidebar-content">' . "\n";
			} else {
				echo '<div id="poststuff" class="metabox-holder no-right-sidebar">' . "\n";
				echo '<div id="post-body" class="no-sidebar">' . "\n";
				echo '<div id="post-body-content" class="no-sidebar-content">' . "\n";
			}

			$this->show_form_content(); ?>

						</div><!-- #post-body-content -->
					</div><!-- #post-body -->
				</div><!-- #poststuff -->
			</div><!-- .wrap -->
			<script type="text/javascript">
				jQuery( document ).ready(
					function( $ ) {
						// close postboxes that should be closed
						$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
						// postboxes setup
						postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
					}
				);
			</script>
			<?php
		}

		public function profile_updated_redirect( $url, $status ) {

			if ( strpos( $url, 'updated=' ) !== false && strpos( $url, 'wp_http_referer=' ) ) {

				// match WordPress behavior (users page for admins, profile page for everyone else)
				$menu_lib = current_user_can( 'list_users' ) ? 'users' : 'profile';
				$parent_slug = $this->p->cf['wp']['admin'][$menu_lib]['page'];
				$referer_match = '/' . $parent_slug . '?page=' . $this->p->lca . '-';

				parse_str( parse_url( $url, PHP_URL_QUERY ), $parts );

				if ( strpos( $parts['wp_http_referer'], $referer_match ) ) {

					$this->p->notice->upd( __( 'Profile updated.' ) );	// green status w check mark

					$url = add_query_arg( 'updated', true, $parts['wp_http_referer'] );
				}
			}
			return $url;
		}

		protected function show_form_content() {

			if ( $this->menu_lib === 'profile' ) {

				$user_id = get_current_user_id();
				$profileuser = get_user_to_edit( $user_id );
				$current_color = get_user_option( 'admin_color', $user_id );

				if ( empty( $current_color ) ) {
					$current_color = 'fresh';
				}

				// match WordPress behavior (users page for admins, profile page for everyone else)
				$referer_admin_url = current_user_can( 'list_users' ) ?
					$this->p->util->get_admin_url( $this->menu_id, null, 'users' ) :
					$this->p->util->get_admin_url( $this->menu_id, null, $this->menu_lib );

				echo '<form name="' . $this->p->lca . '" id="' . $this->p->lca . '_setting_form" action="user-edit.php" method="post">' . "\n";
				echo '<input type="hidden" name="wp_http_referer" value="' . $referer_admin_url . '" />' . "\n";
				echo '<input type="hidden" name="action" value="update" />' . "\n";
				echo '<input type="hidden" name="user_id" value="' . $user_id . '" />' . "\n";
				echo '<input type="hidden" name="nickname" value="' . $profileuser->nickname . '" />' . "\n";
				echo '<input type="hidden" name="email" value="' . $profileuser->user_email . '" />' . "\n";
				echo '<input type="hidden" name="admin_color" value="' . $current_color . '" />' . "\n";
				echo '<input type="hidden" name="rich_editing" value="' . $profileuser->rich_editing . '" />' . "\n";
				echo '<input type="hidden" name="comment_shortcuts" value="' . $profileuser->comment_shortcuts . '" />' . "\n";
				echo '<input type="hidden" name="admin_bar_front" value="' . _get_admin_bar_pref( 'front', $user_id ) . '" />' . "\n";

				wp_nonce_field( 'update-user_' . $user_id );

			} elseif ( $this->menu_lib === 'setting' || $this->menu_lib === 'submenu' ) {

				echo '<form name="' . $this->p->lca . '" id="' . $this->p->lca . '_setting_form" action="options.php" method="post">' . "\n";

				settings_fields( $this->p->lca . '_setting' );

			} elseif ( $this->menu_lib === 'sitesubmenu' ) {

				echo '<form name="' . $this->p->lca . '" id="' . $this->p->lca . '_setting_form" action="edit.php?action=' .
					WPSSO_SITE_OPTIONS_NAME . '" method="post">' . "\n";
				echo '<input type="hidden" name="page" value="' . $this->menu_id . '" />';

			} else {
				return;
			}

			echo "\n";
			echo '<!-- ' . $this->p->lca . ' nonce fields -->' . "\n";
			wp_nonce_field( WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME );	// WPSSO_NONCE_NAME is an md5() string
			echo "\n";
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			echo "\n";
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			echo "\n";

			do_meta_boxes( $this->pagehook, 'normal', null );

			do_action( $this->p->lca . '_form_content_metaboxes_' . SucomUtil::sanitize_hookname( $this->menu_id ), $this->pagehook );

			if ( $this->menu_lib === 'profile' ) {
				echo $this->get_submit_buttons( _x( 'Save All Profile Settings', 'submit button', 'wpsso' ) );
			} else {
				echo $this->get_submit_buttons();
			}

			echo '</form>', "\n";
		}

		protected function get_submit_buttons( $submit_label_transl = '' ) {

			$view_next_key = SucomUtil::next_key( WpssoUser::show_opts(), $this->p->cf['form']['show_options'] );
			$view_name_transl = _x( $this->p->cf['form']['show_options'][$view_next_key], 'option value', 'wpsso' );
			$view_label_transl = sprintf( _x( 'View %s by Default', 'submit button', 'wpsso' ), $view_name_transl );
			$using_external_cache = wp_using_ext_object_cache();

			if ( empty( $submit_label_transl ) ) {
				$submit_label_transl = _x( 'Save All Plugin Settings', 'submit button', 'wpsso' );
			}

			if ( is_multisite() ) {
				$clear_label_transl = sprintf( _x( 'Clear All Caches for Site %d',
					'submit button', 'wpsso' ), get_current_blog_id() );
			} else {
				$clear_label_transl = _x( 'Clear All Caches', 'submit button', 'wpsso' );
			}

			if ( ! $using_external_cache && $this->p->options['plugin_shortener'] !== 'none' ) {
				$clear_label_transl .= ' [*]';
			}

			$action_buttons = apply_filters( $this->p->lca . '_action_buttons', array(
				array(
					'submit' => $submit_label_transl,
					'change_show_options&show-opts=' . $view_next_key => $view_label_transl,
				),
				array(
					'clear_all_cache' => $clear_label_transl,
					'clear_metabox_prefs' => _x( 'Reset Metabox Layout', 'submit button', 'wpsso' ),
					'clear_hidden_notices' => _x( 'Reset Hidden Notices', 'submit button', 'wpsso' ),
				),
			), $this->menu_id, $this->menu_name, $this->menu_lib );

			$submit_buttons = '';

			foreach ( $action_buttons as $row => $row_buttons ) {
				$css_class = $row ? 'button-secondary' : 'button-secondary button-highlight';	// highlight the first row

				foreach ( $row_buttons as $action_arg => $button_label ) {
					if ( $action_arg === 'submit' ) {
						$submit_buttons .= '<input type="' . $action_arg . '" class="button-primary" value="' . $button_label . '" />';
					} else {
						$button_url = wp_nonce_url( $this->p->util->get_admin_url( '?' . $this->p->lca . '-action=' . $action_arg ),
							WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME );
						$submit_buttons .= $this->form->get_button( $button_label, $css_class, '', $button_url );
					}
				}
				$submit_buttons .= '<br/>';
			}

			$html = '<div class="submit-buttons">' . $submit_buttons;

			if ( ! $using_external_cache && $this->p->options['plugin_shortener'] !== 'none' ) {

				$settings_page_link = $this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_cache',
					_x( 'Clear Short URLs on Clear All Caches', 'option label', 'wpsso' ) );

				$html .= '<p><small>[*] ';
				if ( empty( $this->p->options['plugin_clear_short_urls'] ) ) {
					$html .= sprintf( __( '%1$s option is unchecked - shortened URL cache will be preserved.',
						'wpsso' ), $settings_page_link );
				} else {
					$html .= sprintf( __( '%1$s option is checked - shortened URL cache will be refreshed.',
						'wpsso' ), $settings_page_link );
				}
				$html .= '</small></p>';
			}

			$html .= '</div>';

			return $html;
		}

		public function show_metabox_cache_status() {

			$info = $this->p->cf['plugin'][$this->p->lca];
			$table_cols = 3;
			$transient_keys = $this->p->util->get_db_transient_keys();
			$using_external_cache = wp_using_ext_object_cache();

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox cache-status">';

			echo '<tr><td colspan="' . $table_cols . '"><h4>';
			echo sprintf( __( '%s Database Transients', 'wpsso' ), $info['short'] );
			echo '</h4></td></tr>';

			echo '<tr>';
			echo '<th class="cache-label"></th>';
			echo '<th class="cache-count">' . __( 'Count', 'wpsso' ) . '</th>';
			if ( self::$pkg[$this->p->lca]['aop'] ) {
				echo '<th class="cache-expiration">' . __( 'Expiration', 'wpsso' ) . '</th>';
			}
			echo '</tr>';

			// make sure the "All Transients" count is last
			if ( isset( $this->p->cf['wp']['transient'][$this->p->lca . '_'] ) ) {	
				SucomUtil::move_to_end( $this->p->cf['wp']['transient'], $this->p->lca . '_' );
			}

			$shortened_urls_count = 0;
			$have_filtered_cache_exp = false;

			foreach ( $this->p->cf['wp']['transient'] as $cache_md5_pre => $cache_info ) {

				if ( empty( $cache_info ) ) {
					continue;
				} elseif ( empty( $cache_info['label'] ) ) {	// Skip cache info without labels.
					continue;
				}

				$cache_text_dom = empty( $cache_info['text_domain'] ) ? $this->p->lca : $cache_info['text_domain'];
				$cache_label_transl = _x( $cache_info['label'], 'option label', $cache_text_dom );
				$cache_count = count( preg_grep( '/^' . $cache_md5_pre . '/', $transient_keys ) );
				$cache_exp_secs = isset( $cache_info['opt_key'] ) &&
					isset( $this->p->options[$cache_info['opt_key']] ) ?
						 $this->p->options[$cache_info['opt_key']] : 0;
				$cache_exp_html = isset( $cache_info['opt_key'] ) ? $cache_exp_secs : '';
				
				if ( $cache_md5_pre === $this->p->lca . '_s_' ) {
					$shortened_urls_count = $cache_count;
				}

				if ( ! empty( $cache_info['filter'] ) ) {
					$filter_name = $cache_info['filter'];
					$cache_exp_filtered = (int) apply_filters( $filter_name, $cache_exp_secs );
					if ( $cache_exp_secs !== $cache_exp_filtered ) {
						$cache_exp_html = $cache_exp_filtered . ' [F]';	// show that values is changed
						$have_filtered_cache_exp = true;
					}
				}

				echo '<th class="cache-label">' . $cache_label_transl . ':</th>';
				echo '<td class="cache-count">' . $cache_count . '</td>';
				if ( self::$pkg[$this->p->lca]['aop'] ) {
					echo '<td class="cache-expiration">' . $cache_exp_html . '</td>';
				}
				echo '</tr>';
			}

			do_action( $this->p->lca . '_column_metabox_cache_status_table_rows', $table_cols, $this->form, $transient_keys );

			$clear_admin_url = $this->p->util->get_admin_url( '?' . $this->p->lca . '-action=clear_all_cache' );
			$clear_admin_url = wp_nonce_url( $clear_admin_url, WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME );
			$clear_label_transl = _x( 'Clear All Caches', 'submit button', 'wpsso' );

			if ( ! $using_external_cache && $this->p->options['plugin_shortener'] !== 'none' ) {
				$clear_label_transl .= ' [*]';
			}

			// add some extra space between the stats table and buttons
			echo '<tr><td colspan="' . $table_cols . '">&nbsp;</td></tr>';
			echo '<tr><td colspan="' . $table_cols . '">';
			echo $this->form->get_button( $clear_label_transl, 'button-secondary', '', $clear_admin_url );

			// add an extra button to clear the cache and shortened urls
			if ( $shortened_urls_count || ( ! $using_external_cache && $this->p->options['plugin_shortener'] !== 'none' && 
				empty( $this->p->options['plugin_clear_short_urls'] ) ) ) {

				$clear_admin_url = $this->p->util->get_admin_url( '?' . $this->p->lca . '-action=clear_all_cache_and_short_urls' );
				$clear_admin_url = wp_nonce_url( $clear_admin_url, WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME );
				$clear_label_transl = _x( 'Clear All Caches and Short URLs', 'submit button', 'wpsso' );

				echo $this->form->get_button( $clear_label_transl, 'button-secondary', '', $clear_admin_url );
			}

			echo '</td></tr>';

			if ( $have_filtered_cache_exp ) {
				if ( self::$pkg[$this->p->lca]['aop'] ) {
					echo '<tr><td colspan="' . $table_cols . '"><small>[F] ' .
						__( 'Expiration option value has been modified by a filter.',
							'wpsso' ) . '</small></em></td></tr>';
				}
			}

			echo '</table>';
		}

		public function show_metabox_version_info() {

			$table_cols = 2;
			$label_width = '70px';

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox version-info" style="table-layout:fixed;">';
			echo '<colgroup><col style="width:' . $label_width . ';"/><col/></colgroup>';	// required for chrome to display fixed table layout

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( empty( $info['version'] ) ) {	// Only active add-ons.
					continue;
				}

				$installed_version = isset( $info['version'] ) ? $info['version'] : ''; // Static value from config.
				$installed_style   = '';
				$stable_version    = __( 'Not Available', 'wpsso' ); // Default value.
				$latest_version    = __( 'Not Available', 'wpsso' ); // Default value.
				$latest_notice     = '';
				$changelog_url     = isset( $info['url']['changelog'] ) ? $info['url']['changelog'] : '';
				$readme_info       = $this->get_readme_info( $ext, true ); // $read_cache is true.

				if ( ! empty( $readme_info['stable_tag'] ) ) {

					$stable_version = $readme_info['stable_tag'];
					$newer_avail = version_compare( $installed_version, $stable_version, '<' );

					if ( is_array( $readme_info['upgrade_notice'] ) ) {

						/**
						 * Hooked by the update manager to apply the version filter.
						 */
						$upgrade_notice = apply_filters( $this->p->lca . '_readme_upgrade_notices', $readme_info['upgrade_notice'], $ext );

						if ( ! empty( $upgrade_notice ) ) {

							reset( $upgrade_notice );

							$latest_version = key( $upgrade_notice );
							$latest_notice  = $upgrade_notice[$latest_version];
						}
					}

					/**
					 * Hooked by the update manager to check installed version against the latest version, 
					 * if a non-stable filter is selected for that plugin / add-on.
					 */
					if ( apply_filters( $this->p->lca . '_newer_version_available',
						$newer_avail, $ext, $installed_version, $stable_version, $latest_version ) ) {

						$installed_style = 'style="background-color:#f00;"';	// red

					} elseif ( preg_match( '/[a-z]/', $installed_version ) ) {	// current but not stable (alpha chars in version)

						$installed_style = 'style="background-color:#ff0;"';	// yellow
					} else {
						$installed_style = 'style="background-color:#0f0;"';	// green
					}
				}

				echo '<tr><td colspan="' . $table_cols . '"><h4>' . $info['name'] . '</h4></td></tr>';

				echo '<tr><th class="version-label">' . _x( 'Installed', 'option label', 'wpsso' ) . ':</th>
					<td class="version-number" ' . $installed_style . '>' . $installed_version . '</td></tr>';

				echo '<tr><th class="version-label">' . _x( 'Stable', 'option label', 'wpsso' ) . ':</th>
					<td class="version-number">' . $stable_version . '</td></tr>';

				echo '<tr><th class="version-label">' . _x( 'Latest', 'option label', 'wpsso' ) . ':</th>
					<td class="version-number">' . $latest_version . '</td></tr>';

				echo '<tr><td colspan="' . $table_cols . '" class="latest-notice">' .
					( empty( $latest_notice ) ? '' : '<p><em><strong>Version ' .
						$latest_version . '</strong> ' . $latest_notice . '</em></p>' ).
					'<p><a href="' . $changelog_url . '">' . sprintf( __( 'View %s changelog...',
						'wpsso' ), $info['short'] ) . '</a></p></td></tr>';
			}

			do_action( $this->p->lca . '_column_metabox_version_info_table_rows', $table_cols, $this->form );

			echo '</table>';
		}

		public function show_metabox_status_gpl() {

			$ext_num = 0;
			$table_cols = 3;

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox module-status">';

			/**
			 * GPL version features
			 */
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( ! isset( $info['lib']['gpl'] ) ) {
					continue;
				}

				$ext_num++;

				if ( $ext === $this->p->lca ) {	// features for this plugin
					$features = array(
						'(tool) Debug Logging Enabled' => array(
							'classname' => 'SucomDebug',
						),
						'(code) Facebook / Open Graph Meta Tags' => array(
							'status' => class_exists( $this->p->lca . 'opengraph' ) ? 'on' : 'rec',
						),
						'(code) Knowledge Graph Person Markup' => array(
							'status' => $this->p->options['schema_add_home_person'] ? 'on' : 'off',
						),
						'(code) Knowledge Graph Organization Markup' => array(
							'status' => $this->p->options['schema_add_home_organization'] ? 'on' : 'off',
						),
						'(code) Knowledge Graph WebSite Markup' => array(
							'status' => $this->p->options['schema_add_home_website'] ? 'on' : 'rec',
						),
						'(code) Schema Meta Property Containers' => array(
							'status' => $this->p->schema->is_noscript_enabled() ? 'on' : 'off',
						),
						'(code) Twitter Card Meta Tags' => array(
							'status' => class_exists( $this->p->lca . 'twittercard' ) ? 'on' : 'rec',
						),
					);
				} else {
					$features = array();
				}

				self::$pkg[$ext]['purchase'] = '';

				$features = apply_filters( $ext . '_status_gpl_features', $features, $ext, $info, self::$pkg[$ext] );

				if ( ! empty( $features ) ) {

					echo '<tr><td colspan="' . $table_cols . '">';
					echo '<h4' . ( $ext_num > 1 ? ' style="margin-top:10px;"' : '' ) . '>';
					echo $info['name'];
					echo '</h4></td></tr>';

					$this->show_plugin_status( $ext, $info, $features );
				}
			}

			echo '</table>';
		}

		public function show_metabox_status_pro() {

			$ext_num = 0;

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox module-status">';

			/**
			 * Pro version features.
			 */
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( ! isset( $info['lib']['pro'] ) ) {
					continue;
				}

				$ext_num++;
				$features = array();

				if ( ! empty( $info['url']['purchase'] ) ) {
					self::$pkg[$ext]['purchase'] = add_query_arg( 'utm_source', 'status-pro-feature', $info['url']['purchase'] );
				} else {
					self::$pkg[$ext]['purchase'] = '';
				}

				foreach ( $info['lib']['pro'] as $sub => $libs ) {

					if ( $sub === 'admin' ) {	// Skip status for admin menus and tabs.
						continue;
					}

					foreach ( $libs as $id_key => $label ) {

						/**
						 * Example:
						 *	'article' => 'Item Type Article',
						 *	'article#news:no_load' => 'Item Type NewsArticle',
						 *	'article#tech:no_load' => 'Item Type TechArticle',
						 */
						list( $id, $stub, $action ) = SucomUtil::get_lib_stub_action( $id_key );

						$classname  = SucomUtil::sanitize_classname( $ext . 'pro' . $sub.$id, false );	// $underscore is false.
						$status_off = $this->p->avail[$sub][$id] ? 'rec' : 'off';

						$features[$label] = array(
							'td_class' => self::$pkg[$ext]['aop'] ? '' : 'blank',
							'purchase' => self::$pkg[$ext]['purchase'],
							'status' => class_exists( $classname ) ? ( self::$pkg[$ext]['aop'] ? 'on' : $status_off ) : $status_off,
						);
					}
				}

				$features = apply_filters( $ext . '_status_pro_features', $features, $ext, $info, self::$pkg[$ext] );

				if ( ! empty( $features ) ) {

					echo '<tr><td colspan="3">';
					echo '<h4' . ( $ext_num > 1 ? ' style="margin-top:10px;"' : '' ) . '>';
					echo $info['name'];
					echo '</h4></td></tr>';

					$this->show_plugin_status( $ext, $info, $features );
				}
			}
			echo '</table>';
		}

		private function show_plugin_status( &$ext = '', &$info = array(), &$features = array() ) {

			$status_info = array(
				'on' => array(
					'img' => 'green-circle.png',
					'title' => __( 'Module is enabled', 'wpsso' ),
				),
				'off' => array(
					'img' => 'gray-circle.png',
					'title' => __( 'Module is disabled / not loaded', 'wpsso' ),
				),
				'rec' => array(
					'img' => 'red-circle.png',
					'title' => __( 'Module recommended but disabled / not available', 'wpsso' ),
				),
			);

			uksort( $features, array( __CLASS__, 'sort_plugin_features' ) );

			foreach ( $features as $label => $arr ) {

				if ( isset( $arr['classname'] ) ) {
					$status_key = class_exists( $arr['classname'] ) ? 'on' : 'off';
				} elseif ( isset( $arr['constant'] ) ) {
					$status_key = SucomUtil::get_const( $arr['constant'] ) ? 'on' : 'off';
				} elseif ( isset( $arr['status'] ) ) {
					$status_key = $arr['status'];
				} else {
					$status_key = '';
				}

				if ( ! empty( $status_key ) ) {

					$td_class = empty( $arr['td_class'] ) ? '' : ' ' . $arr['td_class'];
					$icon_type = preg_match( '/^\(([a-z\-]+)\) (.*)/', $label, $match ) ? $match[1] : 'admin-generic';
					$icon_title = __( 'Generic feature module', 'wpsso' );
					$label_text = empty( $match[2] ) ? $label : $match[2];
					$label_text = empty( $arr['label'] ) ? $label_text : $arr['label'];
					$purchase_url = $status_key === 'rec' && ! empty( $arr['purchase'] ) ? $arr['purchase'] : '';

					switch ( $icon_type ) {
						case 'api':
							$icon_type = 'controls-repeat';
							$icon_title = __( 'Service API module', 'wpsso' );
							break;
						case 'code':
							$icon_type = 'editor-code';
							$icon_title = __( 'Meta tag and markup module', 'wpsso' );
							break;
						case 'plugin':
							$icon_type = 'admin-plugins';
							$icon_title = __( 'Plugin integration module', 'wpsso' );
							break;
						case 'sharing':
							$icon_type = 'screenoptions';
							$icon_title = __( 'Sharing functionality module', 'wpsso' );
							break;
						case 'tool':
							$icon_type = 'admin-tools';
							$icon_title = __( 'Additional functionality module', 'wpsso' );
							break;
					}

					echo '<tr>' .
					'<td><span class="dashicons dashicons-' . $icon_type . '" title="' . $icon_title . '"></span></td>' .
					'<td class="' . trim( $td_class ) . '">' . $label_text . '</td>' .
					'<td>' .
						( $purchase_url ? '<a href="' . $purchase_url . '">' : '' ).
						'<img src="' . WPSSO_URLPATH . 'images/' .
							$status_info[$status_key]['img'] . '" width="12" height="12" title="' .
							$status_info[$status_key]['title'] . '"/>' .
						( $purchase_url ? '</a>' : '' ).
					'</td>' .
					'</tr>' . "\n";
				}
			}
		}

		private static function sort_plugin_features( $feature_a, $feature_b ) {
			return strcasecmp( self::feature_priority( $feature_a ),
				self::feature_priority( $feature_b ) );
		}

		private static function feature_priority( $feature ) {
			if ( strpos( $feature, '(tool)' ) === 0 ) {
				return '(10) ' . $feature;
			} else {
				return $feature;
			}
		}

		public function show_metabox_purchase_pro() {

			$info =& $this->p->cf['plugin'][$this->p->lca];

			if ( ! empty( $info['url']['purchase'] ) ) {
				$purchase_url = add_query_arg( 'utm_source', 'column-purchase-pro', $info['url']['purchase'] );
			} else {
				$purchase_url = '';
			}

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox"><tr><td>';

			echo '<div class="column-metabox-icon">';
			echo $this->get_ext_img_icon( $this->p->lca );
			echo '</div>';

			echo '<div class="column-metabox-content has-buttons">';
			echo $this->p->msgs->get( 'column-purchase-pro' );
			echo '</div>';

			echo '<div class="column-metabox-buttons">';
			echo $this->form->get_button( _x( 'Purchase Pro Version', 'submit button', 'wpsso' ),
				'button-primary', 'column-purchase-pro', $purchase_url, true );
			echo '</div>';

			echo '</td></tr></table>';
		}

		public function show_metabox_help_support() {

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox"><tr><td>';

			$this->show_follow_icons();

			echo $this->p->msgs->get( 'column-help-support' );

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( empty( $info['version'] ) ) {	// filter out add-ons that are not installed
					continue;
				}

				$links = array();

				if ( ! empty( $info['url']['faqs'] ) ) {
					$links[] = sprintf( __( '<a href="%s">Frequently Asked Questions</a>',
						'wpsso' ), $info['url']['faqs'] ).( ! empty( $info['url']['notes'] ) ?
							' ' . sprintf( __( 'and <a href="%s">Other Notes</a>',
								'wpsso' ), $info['url']['notes'] ) : '' );
				}

				if ( ! empty( $info['url']['support'] ) && self::$pkg[$ext]['aop'] ) {
					$links[] = sprintf( __( '<a href="%s">Priority Support Ticket</a>', 'wpsso' ), $info['url']['support'] ) .
						' (' . __( 'Pro version', 'wpsso' ) . ')';
				} elseif ( ! empty( $info['url']['forum'] ) ) {
					$links[] = sprintf( __( '<a href="%s">Community Support Forum</a>', 'wpsso' ), $info['url']['forum'] );
				}

				if ( ! empty( $links ) ) {
					echo '<h4>' . $info['name'] . '</h4>' . "\n";
					echo '<ul><li>' . implode( '</li><li>', $links ) . '</li></ul>' . "\n";
				}
			}

			echo '</td></tr></table>';
		}

		public function show_metabox_rate_review() {

			echo '<table class="sucom-settings ' . $this->p->lca . ' column-metabox"><tr><td>';
			echo $this->p->msgs->get( 'column-rate-review' );
			echo '<h4>' . __( 'Rate these plugins', 'option label', 'wpsso' ) . ':</h4>' . "\n";

			$links = array();

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( empty( $info['version'] ) ) {	// filter out add-ons that are not installed
					continue;
				}

				if ( ! empty( $info['url']['review'] ) ) {
					$links[] = '<a href="' . $info['url']['review'] . '">' . $info['name'] . '</a>';
				}
			}

			if ( ! empty( $links ) ) {
				echo '<ul><li>' . implode( '</li><li>', $links ) . '</li></ul>' . "\n";
			}

			echo '</td></tr></table>';
		}

		protected function show_follow_icons() {

			echo '<div class="follow-icons">';

			$img_size = $this->p->cf['follow']['size'];

			foreach ( $this->p->cf['follow']['src'] as $img_rel => $url ) {
				echo '<a href="' . $url . '"><img src="' . WPSSO_URLPATH.$img_rel . '"
					width="' . $img_size . '" height="' . $img_size . '" border="0" /></a>';
			}

			echo '</div>';
		}

		/**
		 * Call as WpssoAdmin::get_nonce_action() to have a reliable __METHOD__ value.
		 */
		public static function get_nonce_action() {
			$salt = __FILE__.__METHOD__.__LINE__;
			foreach ( array( 'AUTH_SALT', 'NONCE_SALT' ) as $const ) {
				$salt .= defined( $const ) ? constant( $const ) : '';
			}
			return md5( $salt );
		}

		private function is_profile( $menu_id = false ) {
			return $this->is_lib( 'profile', $menu_id );
		}

		private function is_setting( $menu_id = false ) {
			return $this->is_lib( 'setting', $menu_id );
		}

		private function is_submenu( $menu_id = false ) {
			return $this->is_lib( 'submenu', $menu_id );
		}

		private function is_sitesubmenu( $menu_id = false ) {
			return $this->is_lib( 'sitesubmenu', $menu_id );
		}

		private function is_lib( $lib_name, $menu_id = false ) {
			if ( false === $menu_id ) {
				$menu_id = $this->menu_id;
			}
			return isset( $this->p->cf['*']['lib'][$lib_name][$menu_id] ) ? true : false;
		}

		public function licenses_metabox_content( $network = false ) {

			$tabindex = 0;
			$ext_num = 0;
			$ext_total = count( $this->p->cf['plugin'] );
			$charset = get_bloginfo( 'charset' );

			echo '<table class="sucom-settings ' . $this->p->lca . ' licenses-metabox" style="padding-bottom:10px">' . "\n";
			echo '<tr><td colspan="3">' . $this->p->msgs->get( 'info-plugin-tid' . ( $network ? '-network' : '' ) ) . '</td></tr>' . "\n";

			foreach ( WpssoConfig::get_ext_sorted( true ) as $ext => $info ) {

				$ext_num++;
				$ext_links = array();
				$table_rows = array();

				if ( ! empty( $info['base'] ) ) {

					$details_url = add_query_arg( array(
						'plugin' => $info['slug'],
						'tab' => 'plugin-information',
						'TB_iframe' => 'true',
						'width' => $this->p->cf['wp']['tb_iframe']['width'],
						'height' => $this->p->cf['wp']['tb_iframe']['height'],
					), is_multisite() ?
						network_admin_url( 'plugin-install.php', null ) :
						get_admin_url( null, 'plugin-install.php' ) );

					if ( SucomUtil::plugin_is_installed( $info['base'] ) ) {

						if ( SucomUtil::plugin_has_update( $info['base'] ) ) {
							$ext_links[] = '<a href="' . $details_url . '" class="thickbox" tabindex="' . ++$tabindex . '">' .
								'<font color="red">' . _x( 'Plugin Details and Update', 'plugin action link',
									'wpsso' ) . '</font></a>';
						} else {
							$ext_links[] = '<a href="' . $details_url . '" class="thickbox" tabindex="' . ++$tabindex . '">' .
								_x( 'Plugin Details', 'plugin action link', 'wpsso' ) . '</a>';
						}

					} else {
						$ext_links[] = '<a href="' . $details_url . '" class="thickbox" tabindex="' . ++$tabindex . '">' .
							_x( 'Plugin Details and Install', 'plugin action link', 'wpsso' ) . '</a>';
					}

				} elseif ( ! empty( $info['url']['home'] ) ) {
					$ext_links[] = '<a href="' . $info['url']['home'] . '" tabindex="' . ++$tabindex . '">' .
						_x( 'Plugin Description', 'plugin action link', 'wpsso' ) . '</a>';
				}

				if ( ! empty( $info['base'] ) ) {
					$ext_links = $this->append_licenses_action_links( $ext_links, $info['base'], $tabindex );
				}

				/**
				 * Plugin Name, Description, and Links
				 */
				$plugin_name_html = '<h4>' . $info['name'] . '</h4>';

				$plugin_desc_html = empty( $info['desc'] ) ?
					'' : htmlentities( _x( $info['desc'], 'plugin description', 'wpsso' ),
						ENT_QUOTES, $charset, false );

				$table_rows['plugin_name'] = '<td colspan="2" class="licenses-data-plugin_name" id="licenses-data-plugin_name-' . $ext . '">' .
					$plugin_name_html . ( empty( $plugin_desc_html ) ? '' : '<p>' . $plugin_desc_html . '</p>' ) .
					( empty( $ext_links ) ? '' : '<div class="row-actions visible">' . implode( ' | ', $ext_links ) . '</div>' ) .
					'</td>';

				/**
				 * Plugin Authentication ID and License Information
				 */
				if ( ! empty( $info['update_auth'] ) || ! empty( $this->p->options['plugin_' . $ext . '_tid'] ) ) {

					$table_rows['plugin_tid'] = $this->form->get_th_html( sprintf( _x( '%s Authentication ID',
						'option label', 'wpsso' ), $info['short'] ), 'medium nowrap' );

					if ( $this->p->lca === $ext || self::$pkg[$this->p->lca]['aop'] ) {

						$table_rows['plugin_tid'] .= '<td width="100%">' .
							$this->form->get_input( 'plugin_' . $ext . '_tid', 'tid mono', '', 0, 
								'', false, ++$tabindex ) . '</td>';

						if ( $network ) {

							$table_rows['site_use'] = self::get_option_site_use( 'plugin_' . $ext . '_tid', $this->form, $network, true );

						} elseif ( class_exists( 'SucomUpdate' ) ) {	// Required to use SucomUpdate::get_option().

							foreach ( array(
								'exp_date' => _x( 'Support and Updates Expire', 'option label', 'wpsso' ),
								'qty_used' => _x( 'License Information', 'option label', 'wpsso' ),
							) as $key => $label ) {

								$val = SucomUpdate::get_option( $ext, $key );

								if ( empty( $val ) ) {	// Skip table rows for empty values.
									
									continue;

								} elseif ( $key === 'exp_date' ) {

									if ( $val === '0000-00-00 00:00:00' ) {
										$val = _x( 'Never', 'option value', 'wpsso' );
									}

								} elseif ( $key === 'qty_used' ) {

									/**
									 * The default 'qty_used' value is a '#/#' string.
									 */
									$val = sprintf( __( '%s site addresses registered', 'wpsso' ), $val );

									/**
									 * Use a better '# of #' string translation if possible.
									 */
									if ( version_compare( WpssoUmConfig::get_version(), '1.10.1', '>=' ) ) {

										$qty_reg   = SucomUpdate::get_option( $ext, 'qty_reg' );
										$qty_total = SucomUpdate::get_option( $ext, 'qty_total' );

										if ( $qty_reg !== null && $qty_total !== null ) {
											$val = sprintf( __( '%d of %d site addresses registered', 'wpsso' ),
												$qty_reg, $qty_total );
										}
									}

									if ( ! empty( $info['url']['info'] ) ) {

										$locale = is_admin() && function_exists( 'get_user_locale' ) ?
											get_user_locale() : get_locale();

										$info_url = add_query_arg( array(
											'tid' => $this->p->options['plugin_' . $ext . '_tid'],
											'locale' => $locale,
											'TB_iframe' => 'true',
											'width' => $this->p->cf['wp']['tb_iframe']['width'],
											'height' => $this->p->cf['wp']['tb_iframe']['height'],
										), $info['url']['purchase'] . 'info/' );

										$val = '<a href="' . $info_url . '" class="thickbox">' . $val . '</a>';
									}
								}

								$table_rows[$key] = '<th class="medium nowrap">' . $label . '</th>' .
									'<td width="100%">' . $val . '</td>';
							}
						}

					} else {

						$table_rows['plugin_tid'] .= '<td class="blank">' .
							( empty( $this->p->options['plugin_' . $ext . '_tid'] ) ?
								$this->form->get_no_input( 'plugin_' . $ext . '_tid', 'tid mono' ) :
								$this->form->get_input( 'plugin_' . $ext . '_tid', 'tid mono',
									'', 0, '', false, ++$tabindex ) ) . '</td>';
					}

				} else {
					$table_rows['plugin_tid'] = '<td>&nbsp;</td><td width="100%">&nbsp;</td>';
				}

				/**
				 * Dotted Line
				 */
				if ( $ext_num < $ext_total ) {
					$table_rows['dotted_line'] = '<td style="border-bottom:1px dotted #ddd; height:5px;" colspan="2"></td>';
				}

				/**
				 * Show the Table Rows
				 */
				foreach ( $table_rows as $key => $row ) {
					echo '<tr>';
					if ( $key === 'plugin_name' ) {
						echo '<td class="licenses-data-plugin_icon" id="licenses-data-plugin_icon-' . $ext . '"' .
							' width="168" rowspan="' . count( $table_rows ) . '" valign="top" align="left">' . "\n";
						echo $this->get_ext_img_icon( $ext );
						echo '</td>';
					}
					echo $row;
					echo '</tr>';
				}
			}
			echo '</table>' . "\n";
		}

		public function add_admin_tb_notices_menu_item( $wp_admin_bar ) {

			$menu_icon = '<span class="ab-icon" id="' . $this->p->lca . '-toolbar-notices-icon"></span>';
			$menu_count = '<span id="' . $this->p->lca . '-toolbar-notices-count">0</span>';

			$no_notices_text = sprintf( __( 'No new %s notifications.', 'wpsso' ), $this->p->cf['menu']['title'] );

			$wp_admin_bar->add_node( array(	// Since wp 3.1
				'id' => $this->p->lca . '-toolbar-notices',
				'title' => $menu_icon . $menu_count,
				'parent' => false,
				'href' => false,
				'group' => false,
				'meta' => array(),
			) );

			$wp_admin_bar->add_node( array(
				'id' => $this->p->lca . '-toolbar-notices-container',
				'title' => $no_notices_text,
				'parent' => $this->p->lca . '-toolbar-notices',
				'href' => false,
				'group' => false,
				'meta' => array(),
			) );
		}

		public function conflict_warnings() {

			if ( ! is_admin() ) { 	// Just in case.
				return;
			}

			$this->conflict_check_php();
			$this->conflict_check_wp();
			$this->conflict_check_seo();
		}

		private function conflict_check_php() {

			/**
			 * Load the WP class libraries to avoid triggering a known bug in EWWW
			 * when applying the 'wp_image_editors' filter.
			 */
			require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

			$implementations = apply_filters( 'wp_image_editors', array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ) );
			$php_extensions  = $this->p->cf['php']['extensions'];

			foreach ( $php_extensions as $php_ext => $php_info ) {

				/**
				 * Skip image extensions for WordPress image editors that are not used.
				 */
				if ( ! empty( $php_info['wp_image_editor']['class'] ) ) {
					if ( ! in_array( $php_info['wp_image_editor']['class'], $implementations ) ) {
						continue;
					}
				}

				$error_msg = '';	// Clear any previous error message.

				/**
				 * Check for the extension first, then maybe check for its functions.
				 */
				if ( ! extension_loaded( $php_ext ) ) {

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'php ' . $php_ext . ' extension module is not loaded' );
					}

					/**
					 * If this is a WordPress image editing extension, add information about the WordPress image editing class.
					 */
					if ( ! empty( $php_info['wp_image_editor']['class'] ) ) {

						/**
						 * If we have a WordPress reference URL for this image editing class, link the image editor class name.
						 */
						if ( ! empty( $php_info['wp_image_editor']['url'] ) ) {
							$editor_class = '<a href="' . $php_info['wp_image_editor']['url'] . '">' .
								$php_info['wp_image_editor']['class'] . '</a>';
						} else {
							$editor_class = $php_info['wp_image_editor']['class'];
						}

						$error_msg .= sprintf( __( 'WordPress is configured to use the %1$s image editing class but the <a href="%2$s">PHP %3$s extension module</a> is not loaded:', 'wpsso' ), $editor_class, $php_info['url'], $php_info['label'] ) . ' ';

					} else {

						$error_msg .= sprintf( __( 'The <a href="%1$s">PHP %2$s extension module</a> is not loaded:', 'wpsso' ),
							$php_info['url'], $php_info['label'] ).' ';
					}

					/**
					 * Add additional / mode specific information about this check for the hosting provider.
					 */
					$error_msg .= sprintf( __( 'The <a href="%1$s">PHP %2$s function</a> for "%3$s" returned false.', 'wpsso' ),
						__( 'https://secure.php.net/manual/en/function.extension-loaded.php', 'wpsso' ),
							'<code>extension_loaded()</code>', $php_ext ).' ';


					/**
					 * If we are checking for the ImageMagick PHP extension, make sure the user knows the
					 * difference between the OS package and the PHP extension.
					 */
					if ( $php_ext === 'imagick' ) {
						$error_msg .= sprintf( __( 'Note that the ImageMagick application and the PHP "%1$s" extension are two different products &mdash; this error is for the PHP "%1$s" extension, not the ImageMagick application.', 'wpsso' ), $php_ext ).' ';
					}

					$error_msg .= sprintf( __( 'Please contact your hosting provider to have the missing PHP "%1$s" extension installed and enabled.', 'wpsso' ), $php_ext );

				/**
				 * If the PHP extension is loaded, then maybe check to make sure the extension is complete. ;-)
				 */
				} elseif ( ! empty( $php_info['functions'] ) && is_array( $php_info['functions'] ) ) {

					foreach ( $php_info['functions'] as $func_name ) {

						if ( ! function_exists( $func_name ) ) {

							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( 'php ' . $func_name . ' function is missing' );
							}

							$error_msg .= sprintf( __( 'The <a href="%1$s">PHP %2$s extension module</a> is loaded but the %3$s function is missing.', 'wpsso' ), $php_info['url'], $php_info['label'], '<code>' . $func_name . '()</code>' ).' ';
							$error_msg .= sprintf( __( 'Please contact your hosting provider to have the missing PHP function installed.', 'wpsso' ), $func_name );
						}
					}
				}

				if ( ! empty( $error_msg ) ) {
					$this->p->notice->err( $error_msg );
				}
			}
		}

		private function conflict_check_wp() {

			if ( ! get_option( 'blog_public' ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'blog_public option is disabled' );
				}

				$dismiss_key = 'wordpress-search-engine-visibility-disabled';

				if ( $this->p->notice->is_admin_pre_notices( $dismiss_key ) ) { // Don't bother if already dismissed.
					$this->p->notice->warn( sprintf( __( 'The WordPress <a href="%s">Search Engine Visibility</a> option is set to discourage search engine and social crawlers from indexing this site. This is not compatible with the purpose of sharing content on social sites &mdash; please uncheck the option to allow search engines and social crawlers to access your content.', 'wpsso' ), get_admin_url( null, 'options-reading.php' ) ), true, $dismiss_key, MONTH_IN_SECONDS * 3 );
				}
			}
		}

		private function conflict_check_seo() {

			$err_pre =  __( 'Plugin conflict detected', 'wpsso' ) . ' &mdash; ';
			$log_pre = 'plugin conflict detected - ';

			/**
			 * All in One SEO Pack
			 */
			if ( $this->p->avail['seo']['aioseop'] ) {

				$opts = get_option( 'aioseop_options' );

				if ( ! empty( $opts['modules']['aiosp_feature_manager_options']['aiosp_feature_manager_enable_opengraph'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Social Meta', 'all-in-one-seo-pack' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=all-in-one-seo-pack%2Fmodules%2Faioseop_feature_manager.php' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'All in One SEO', 'all-in-one-seo-pack' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Feature Manager', 'all-in-one-seo-pack' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'aioseop social meta feature is enabled' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please deactivate the %1$s feature in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

				if ( isset( $opts['aiosp_google_disable_profile'] ) && empty( $opts['aiosp_google_disable_profile'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Disable Google Plus Profile', 'all-in-one-seo-pack' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=all-in-one-seo-pack%2Faioseop_class.php' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'All in One SEO', 'all-in-one-seo-pack' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'General Settings', 'all-in-one-seo-pack' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Google Settings', 'all-in-one-seo-pack' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'aioseop google plus profile is enabled' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please check the %1$s option in the %2$s metabox.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

				if ( ! empty( $opts['aiosp_schema_markup'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Use Schema.org Markup', 'all-in-one-seo-pack' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=all-in-one-seo-pack%2Faioseop_class.php' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'All in One SEO', 'all-in-one-seo-pack' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'General Settings', 'all-in-one-seo-pack' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'General Settings', 'all-in-one-seo-pack' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'aioseop schema markup option is checked' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please uncheck the %1$s option in the %2$s metabox.',
						'wpsso' ), $label_transl, $settings_link ) );
				}
			}

			/**
			 * SEO Ultimate
			 */
			if ( $this->p->avail['seo']['seou'] ) {

				$opts = get_option( 'seo_ultimate' );
				$settings_url = get_admin_url( null, 'admin.php?page=seo' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'SEO Ultimate', 'seo-ultimate' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Modules', 'seo-ultimate' ) . '</a>';

				if ( ! empty( $opts['modules'] ) && is_array( $opts['modules'] ) ) {

					if ( array_key_exists( 'opengraph', $opts['modules'] ) && $opts['modules']['opengraph'] !== -10 ) {

						// translators: please ignore - translation uses a 3rd party text domain
						$label_transl = '<strong>' . __( 'Open Graph Integrator', 'seo-ultimate' ) . '</strong>';

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'seo ultimate opengraph module is enabled' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s module in the %2$s settings.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}
			}

			/**
			 * Squirrly SEO
			 */
			if ( $this->p->avail['seo']['sq'] ) {

				$opts = json_decode( get_option( 'sq_options' ), true );

				/**
				 * Squirrly SEO > SEO Settings > Social Media > Social Media Options Metabox
				 */
				$settings_url = get_admin_url( null, 'admin.php?page=sq_seo#socials' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Squirrly', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'SEO Settings', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Social Media', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Social Media Options', 'squirrly-seo' ) . '</a>';

				foreach ( array(
					'sq_auto_facebook' => '"<strong>' . __( 'Add the Social Open Graph protocol so that your Facebook shares look good.',
						'wpsso' ) . '</strong>"',
					'sq_auto_twitter' => '"<strong>' . __( 'Add the Twitter card in your tweets.',
						'wpsso' ) . '</strong>"',
				) as $opt_key => $label_transl ) {

					if ( ! empty( $opts[$opt_key] ) ) {

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'squirrly seo ' . $opt_key . ' option is enabled' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s option in the %2$s metabox.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}

				/**
				 * Squirrly SEO > SEO Settings > SEO Settings > Let Squirrly SEO Optimize This Blog Metabox
				 */
				$settings_url = get_admin_url( null, 'admin.php?page=sq_seo#seo' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Squirrly', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'SEO Settings', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'SEO Settings', 'squirrly-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Let Squirrly SEO Optimize This Blog', 'squirrly-seo' ) . '</a>';

				foreach ( array(
					'sq_auto_jsonld' => '"<strong>' . __( 'adds the Json-LD metas for Semantic SEO', 'wpsso' ) . '</strong>"',
				) as $opt_key => $label_transl ) {

					if ( ! empty( $opts[$opt_key] ) ) {

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'squirrly seo ' . $opt_key . ' option is enabled' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s option in the %2$s metabox.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}
			}

			/**
			 * The SEO Framework
			 */
			if ( $this->p->avail['seo']['autodescription'] ) {

				$the_seo_framework = the_seo_framework();

				/**
				 * The SEO Framework > Social Meta Settings Metabox
				 */
				$settings_url = get_admin_url( null, 'admin.php?page=theseoframework-settings' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'The SEO Framework', 'autodescription' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Social Meta Settings', 'autodescription' ) . '</a>';

				// translators: please ignore - translation uses a 3rd party text domain
				$posts_i18n = __( 'Posts', 'autodescription' );

				foreach ( array(
					// translators: please ignore - translation uses a 3rd party text domain
					'og_tags'       => '<strong>' . __( 'Output Open Graph meta tags?', 'autodescription' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'facebook_tags' => '<strong>' . __( 'Output Facebook meta tags?', 'autodescription' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'twitter_tags'  => '<strong>' . __( 'Output Twitter meta tags?', 'autodescription' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'post_publish_time' => '<strong>' . sprintf( __( 'Add %1$s to %2$s?', 'autodescription' ),
						'article:published_time', $posts_i18n ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'post_modify_time' => '<strong>' . sprintf( __( 'Add %1$s to %2$s?', 'autodescription' ),
						'article:modified_time', $posts_i18n ) . '</strong>',
				) as $opt_key => $label_transl ) {

					if ( $the_seo_framework->is_option_checked( $opt_key ) ) {

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'autodescription ' . $opt_key . ' option is checked' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please uncheck the %1$s option in the %2$s metabox.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}

				/**
				 * The SEO Framework > Schema Settings Metabox
				 */
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'The SEO Framework', 'autodescription' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Schema Settings', 'autodescription' ) . '</a>';

				if ( $the_seo_framework->is_option_checked( 'knowledge_output' ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Output Authorized Presence?', 'autodescription' ) . '</strong>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'autodescription knowledge_output option is checked' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please uncheck the %1$s option in the %2$s metabox.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

			}

			/**
			 * WP Meta SEO
			 */
			if ( $this->p->avail['seo']['wpmetaseo'] ) {

				$opts = get_option( '_metaseo_settings' );

				/**
				 * WP Meta SEO > Settings > Global
				 */
				$settings_url = get_admin_url( null, 'admin.php?page=metaseo_settings' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'WP Meta SEO', 'wp-meta-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Settings', 'wp-meta-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Global', 'wp-meta-seo' ) . '</a>';

				foreach ( array(
					// translators: please ignore - translation uses a 3rd party text domain
					'metaseo_showfacebook' => '<strong>' . __( 'Facebook profile URL', 'wp-meta-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'metaseo_showfbappid'  => '<strong>' . __( 'Facebook App ID', 'wp-meta-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'metaseo_showtwitter'  => '<strong>' . __( 'Twitter Username', 'wp-meta-seo' ) . '</strong>',
				) as $opt_key => $label_transl ) {

					if ( ! empty( $opts[$opt_key] ) ) {

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'wpmetaseo ' . $opt_key . ' option is not empty' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please remove the %1$s option value in the %2$s settings.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}

				if ( ! empty( $opts['metaseo_showsocial'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Social sharing block', 'wp-meta-seo' ) . '</strong>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'wpmetaseo metaseo_showsocial option is enabled' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s option in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}
			}

			/**
			 * Yoast SEO
			 */
			if ( $this->p->avail['seo']['wpseo'] ) {

				$opts = get_option( 'wpseo_social' );

				/**
				 * Yoast SEO > Social > Accounts Tab
				 */
				$settings_url = get_admin_url( null, 'admin.php?page=wpseo_social#top#accounts' );
				$settings_link = '<a href="' . $settings_url . '">' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Yoast SEO', 'wordpress-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Social', 'wordpress-seo' ) . ' &gt; ' .
					// translators: please ignore - translation uses a 3rd party text domain
					__( 'Accounts', 'wordpress-seo' ) . '</a>';

				foreach ( array(
					// translators: please ignore - translation uses a 3rd party text domain
					'facebook_site'   => '<strong>' . __( 'Facebook Page URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'twitter_site'    => '<strong>' . __( 'Twitter Username', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'instagram_url'   => '<strong>' . __( 'Instagram URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'linkedin_url'    => '<strong>' . __( 'LinkedIn URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'myspace_url'     => '<strong>' . __( 'MySpace URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'pinterest_url'   => '<strong>' . __( 'Pinterest URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'youtube_url'     => '<strong>' . __( 'YouTube URL', 'wordpress-seo' ) . '</strong>',
					// translators: please ignore - translation uses a 3rd party text domain
					'google_plus_url' => '<strong>' . __( 'Google+ URL', 'wordpress-seo' ) . '</strong>',
				) as $opt_key => $label_transl ) {

					if ( ! empty( $opts[$opt_key] ) ) {

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_pre . 'wpseo ' . $opt_key . ' option is not empty' );
						}

						$this->p->notice->err( $err_pre . sprintf( __( 'please remove the %1$s option value in the %2$s settings.',
							'wpsso' ), $label_transl, $settings_link ) );
					}
				}

				/**
				 * Yoast SEO > Social > Faceboook Tab
				 */
				if ( ! empty( $opts['opengraph'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Add Open Graph meta data', 'wordpress-seo' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=wpseo_social#top#facebook' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Yoast SEO', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Social', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Facebook', 'wordpress-seo' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'wpseo opengraph option is enabled' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s option in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

				if ( ! empty( $opts['fbadminapp'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Facebook App ID', 'wordpress-seo' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=wpseo_social#top#facebook' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Yoast SEO', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Social', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Facebook', 'wordpress-seo' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'wpseo fbadminapp option is not empty' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please remove the %1$s option value in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

				/**
				 * Yoast SEO > Social > Twitter Tab
				 */
				if ( ! empty( $opts['twitter'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Add Twitter Card meta data', 'wordpress-seo' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=wpseo_social#top#twitterbox' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Yoast SEO', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Social', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Twitter', 'wordpress-seo' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'wpseo twitter option is enabled' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please disable the %1$s option in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}

				/**
				 * Yoast SEO > Social > Google+ Tab
				 */
				if ( ! empty( $opts['plus-publisher'] ) ) {

					// translators: please ignore - translation uses a 3rd party text domain
					$label_transl = '<strong>' . __( 'Google Publisher Page', 'wordpress-seo' ) . '</strong>';
					$settings_url = get_admin_url( null, 'admin.php?page=wpseo_social#top#google' );
					$settings_link = '<a href="' . $settings_url . '">' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Yoast SEO', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Social', 'wordpress-seo' ) . ' &gt; ' .
						// translators: please ignore - translation uses a 3rd party text domain
						__( 'Google+', 'wordpress-seo' ) . '</a>';

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $log_pre . 'wpseo plus-publisher option is not empty' );
					}

					$this->p->notice->err( $err_pre . sprintf( __( 'please remove the %1$s option value in the %2$s settings.',
						'wpsso' ), $label_transl, $settings_link ) );
				}
			}
		}

		public function admin_footer_ext_name( $text ) {
			if ( isset( self::$pkg[$this->menu_ext]['name'] ) ) {
				$text = '<span class="admin-footer-ext-name">' . self::$pkg[$this->menu_ext]['name'] . '</span>';
			}
			return $text;
		}

		public function admin_footer_ext_gen( $text ) {
			if ( isset( self::$pkg[$this->menu_ext]['gen'] ) ) {
				$host = '<br/>' . preg_replace( '/^[^:]*:\/\//', '', strtolower( SucomUtilWP::raw_get_home_url() ) );
				$text = '<span class="admin-footer-ext-gen">' . self::$pkg[$this->menu_ext]['gen'] . $host. '</span>';
			}
			return $text;
		}

		/**
		 * Only show notices on the dashboard and the settings pages.
		 * Hooked to 'current_screen' filter, so return the $screen object.
		 */
		public function maybe_show_screen_notices( $screen ) {

			$screen_id = SucomUtil::get_screen_id( $screen );

			/**
			 * If adding notices in the toolbar, show the notice on all pages,
			 * otherwise only show on the dashboard and settings pages.
			 */
			if ( SucomUtil::get_const( 'WPSSO_TOOLBAR_NOTICES' ) ) {
				$this->maybe_show_rating_notice();
			} else {
				switch ( $screen_id ) {
					case 'dashboard':
					case ( strpos( $screen_id, '_page_' . $this->p->lca . '-' ) !== false ? true : false ):
						$this->maybe_show_rating_notice();
						break;
				}
			}

			return $screen;
		}

		public function maybe_show_rating_notice() {

			if ( ! $this->p->notice->can_dismiss() || ! current_user_can( 'manage_options' ) ) {
				return;	// Stop here.
			}

			$user_id        = get_current_user_id();
			$all_ext_times  = $this->p->util->get_all_times();
			$time_ago_secs  = time() - WEEK_IN_SECONDS;
			$cache_md5_pre  = $this->p->lca . '_';
			$cache_exp_secs = DAY_IN_SECONDS;
			$cache_salt     = __METHOD__ . '(user_id:' . $user_id . ')';
			$cache_id       = $cache_md5_pre . md5( $cache_salt );

			$this->get_form_object( $this->p->lca );

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				$dismiss_key  = 'timed-notice-' . $ext . '-plugin-review';
				$dismiss_time = true;
				$showing_ext  = get_transient( $cache_id );				// Returns empty string or $dismiss_key for 24 hours.

				if ( empty( $info['version'] ) ) {					// Not installed.
					continue;
				} elseif ( empty( $info['url']['review'] ) ) {				// Must be hosted on wordpress.org.
					continue;
				} elseif ( $this->p->notice->is_dismissed( $dismiss_key, $user_id ) ) {	// User has dismissed.
					if ( $showing_ext === $dismiss_key ) {				// Notice was dismissed today.
						break;
					}
					continue;
				} elseif ( ! isset( $all_ext_times[$ext . '_activate_time'] ) ) {	// Never activated.
					continue;
				} elseif ( $all_ext_times[$ext . '_activate_time'] > $time_ago_secs ) {	// Activated less than time ago.
					continue;
				} elseif ( empty( $showing_ext ) || $showing_ext === '1' ) {		// Show a notice for this plugin for 24 hours.
					set_transient( $cache_id, $dismiss_key, $cache_exp_secs );
				} elseif ( $showing_ext !== $dismiss_key ) {				// We're not showing this plugin right now.
					continue;
				}

				if ( ! empty( $info['url']['support'] ) && self::$pkg[$ext]['aop'] ) {
					$support_url = $info['url']['support'];
				} elseif ( ! empty( $info['url']['forum'] ) ) {
					$support_url = $info['url']['forum'];
				} else {
					$support_url = '';
				}

				$rate_plugin_button = '<div style="display:inline-block;vertical-align:top;margin:1em 0.8em 0 0;">' .
					$this->form->get_button( sprintf( __( 'Yes! Contribute and rate %s 5 stars!', 'wpsso' ), $info['short'] ),
						'button-primary dismiss-on-click', '', $info['url']['review'], true, false,
							array( 'dismiss-msg' => sprintf( __( 'Thank you for rating the %s plugin! You\'re awesome!',
								'wpsso' ), $info['short'] ) ) ) . '</div>';

				$already_rated_button = '<div style="display:inline-block;vertical-align:top;margin:1em 0 0 0;">' .
					$this->form->get_button( sprintf( __( 'I\'ve already rated %s', 'wpsso' ), $info['short'] ),
						'button-secondary dismiss-on-click', '', '', false, false, 
							array( 'dismiss-msg' => sprintf( __( 'Thank you for your earlier rating of %s! You\'re awesome!',
								'wpsso' ), $info['short'] ) ) ) . '</div>';

				$notice_msg = '<div style="display:table-cell;"><p style="margin-right:20px;">' .
					$this->get_ext_img_icon( $ext ) . '</p></div>' . "\n";

				$notice_msg .= '<div style="display:table-cell;vertical-align:top;">';

				$notice_msg .= '<p class="top">';
				
				$notice_msg .= '<b>' . __( 'Fantastic!', 'wpsso' ) . '</b> ' .
					sprintf( __( 'You\'ve been using <b>%s</b> for a week or more.', 'wpsso' ),
						'<a href="' . $info['url']['home'] . '" title="' . sprintf( __( 'The %s plugin description page on WordPress.org',
							'wpsso' ), $info['short'] ) . '">' . $info['name'] . '</a>' ) . ' ';

				$notice_msg .= __( 'That\'s awesome!', 'wpsso' );
				
				$notice_msg .= '</p><p>';

				$notice_msg .= '<b>' . __( 'Could you do me a small favor?', 'wpsso' ) . '</b> ';

				$notice_msg .= sprintf( __( 'Would you rate the %s plugin on WordPress.org?', 'wpsso' ), $info['short'] );

				$notice_msg .= '</p><p>';

				$notice_msg .= __( 'Your rating is a great way to encourage us and it helps other WordPress users as well!', 'wpsso' ) . ' :-)';

				$notice_msg .= '</p>';
				
				$notice_msg .= $rate_plugin_button . $already_rated_button;
					
				$notice_msg .= '</div>';

				/**
				 * The notice provides it's own dismiss button, so do not show the dismiss 'Forever' link.
				 */
				$this->p->notice->log( 'inf', $notice_msg, $user_id, $dismiss_key, $dismiss_time, array( 'dismiss_diff' => false ) );

				break;	// Show only one notice at a time.
			}
		}

		public function required_notices() {

			$has_pdir = $this->p->avail['*']['p_dir'];
			$version = $this->p->cf['plugin'][$this->p->lca]['version'];
			$um_info = $this->p->cf['plugin']['wpssoum'];
			$have_ext_tid = false;

			if ( $has_pdir && empty( $this->p->options['plugin_' . $this->p->lca . '_tid'] ) &&
				( empty( $this->p->options['plugin_' . $this->p->lca . '_tid:is'] ) ||
					$this->p->options['plugin_' . $this->p->lca . '_tid:is'] !== 'disabled' ) ) {
				$this->p->notice->nag( $this->p->msgs->get( 'notice-pro-tid-missing' ) );
			}

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				if ( ! empty( $this->p->options['plugin_' . $ext . '_tid'] ) ) {

					$have_ext_tid = true;	// found at least one plugin with an auth id

					/**
					 * If the update manager is active, the version should be available.
					 * Skip individual warnings and show nag to install the update manager.
					 */
					if ( empty( $um_info['version'] ) ) {
						break;
					} else {
						if ( ! self::$pkg[$ext]['pdir'] ) {
							if ( ! empty( $info['base'] ) && ! SucomUtil::plugin_is_installed( $info['base'] ) ) {
								$this->p->notice->warn( $this->p->msgs->get( 'notice-pro-not-installed', array( 'lca' => $ext ) ) );
							} else {
								$this->p->notice->warn( $this->p->msgs->get( 'notice-pro-not-updated', array( 'lca' => $ext ) ) );
							}
						}
					}
				}
			}

			if ( true === $have_ext_tid ) {

				// if the update manager is active, the version should be available
				if ( ! empty( $um_info['version'] ) ) {

					$um_rec_version = WpssoConfig::$cf['um']['rec_version'];

					if ( version_compare( $um_info['version'], $um_rec_version, '<' ) ) {
						$this->p->notice->err( $this->p->msgs->get( 'notice-um-version-recommended',
							array( 'um_rec_version' => $um_rec_version ) ) );
					}

				// if the update manager is not active, check if installed
				} elseif ( SucomUtil::plugin_is_installed( $um_info['base'] ) ) {

					$this->p->notice->nag( $this->p->msgs->get( 'notice-um-activate-add-on' ) );

				// update manager is not active or installed
				} else {
					$this->p->notice->nag( $this->p->msgs->get( 'notice-um-add-on-required' ) );
				}
			}

			if ( current_user_can( 'manage_options' ) ) {

				foreach ( array( 'wp', 'php' ) as $key ) {

					if ( isset( WpssoConfig::$cf[$key]['rec_version'] ) ) {

						switch ( $key ) {
							case 'wp':
								global $wp_version;
								$app_version = $wp_version;
								break;
							case 'php':
								$app_version = phpversion();
								break;
							default:
								continue 2;
						}

						$app_label = WpssoConfig::$cf[$key]['label'];
						$rec_version = WpssoConfig::$cf[$key]['rec_version'];

						if ( version_compare( $app_version, $rec_version, '<' ) ) {

							$warn_msg = $this->p->msgs->get( 'notice-recommend-version', array(
								'app_label' => $app_label,
								'app_version' => $app_version,
								'rec_version' => WpssoConfig::$cf[$key]['rec_version'],
								'version_url' => WpssoConfig::$cf[$key]['version_url'],
							) );

							$dismiss_key  = 'notice-recommend-version-' . $this->p->lca . '-' . $version . '-' . $app_label . '-' . $app_version;
							$dismiss_time = MONTH_IN_SECONDS;

							$this->p->notice->warn( $warn_msg, true, $dismiss_key, $dismiss_time, true );	// $no_unhide is true
						}
					}
				}
			}

			if ( $this->p->options['plugin_shortener'] === 'googl' && 
				! empty( $this->p->options['plugin_google_api_key'] ) &&
					empty ( $this->p->options['plugin_google_shorten'] ) ) {

				$this->p->notice->warn( sprintf( __( 'Google has been selected as your preferred URL shortening service, but the %s option is not enabled.',
					'wpsso' ), $this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_apikeys',
						_x( 'URL Shortener API is Enabled', 'option label', 'wpsso' ) ) ) );
			}
		}

		public function update_count_notice() {

			$update_count = SucomUtil::get_plugin_updates_count( $this->p->lca );

			if ( $update_count > 0 ) {

				$info = $this->p->cf['plugin'][$this->p->lca];
				$link_url = self_admin_url( 'update-core.php' );
				$dismiss_key = 'have-updates-for-' . $this->p->lca;

				$this->p->notice->inf( sprintf( _n( 'There is <a href="%1$s">%2$d pending update for the %3$s plugin and/or its add-on(s)</a>.', 'There are <a href="%1$s">%2$d pending updates for the %3$s plugin and/or its add-on(s)</a>.', $update_count, 'wpsso' ), $link_url, $update_count, $info['short'] ) . ' ' . _n( 'Please install this update at your earliest convenience.', 'Please install these updates at your earliest convenience.', $update_count, 'wpsso' ), true, $dismiss_key, DAY_IN_SECONDS * 3 );
			}
		}

		public function reset_check_head_count() {
			delete_option( WPSSO_POST_CHECK_NAME );
		}

		public function check_tmpl_head_attributes() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			/**
			 * Only check if using the default filter name.
			 */
			if ( empty( $this->p->options['plugin_head_attr_filter_name'] ) ||
				$this->p->options['plugin_head_attr_filter_name'] !== 'head_attributes' ) {
				return;	// exit early
			}

			foreach ( SucomUtil::get_header_files() as $tmpl_file ) {

				$html_stripped = SucomUtil::get_stripped_php( $tmpl_file );

				if ( empty( $html_stripped ) ) {	// empty string or false

					continue;

				} elseif ( strpos( $html_stripped, '<head>' ) !== false ) {

					if ( $this->p->notice->is_admin_pre_notices() ) {
						$error_msg = $this->p->msgs->get( 'notice-header-tmpl-no-head-attr' );
						$dismiss_key = 'notice-header-tmpl-no-head-attr-' . SucomUtil::get_theme_slug_version();
						$this->p->notice->warn( $error_msg, true, $dismiss_key, true );
					}

					break;
				}
			}
		}

		public function modify_tmpl_head_attributes() {

			$have_changes = false;
			$header_files = SucomUtil::get_header_files();
			$head_action_php = '<head <?php do_action( \'add_head_attributes\' ); ?' . '>>';	// breakup closing php for vim

			if ( empty( $header_files ) ) {
				$this->p->notice->err( __( 'No header templates found in the parent or child theme directories.', 'wpsso' ) );
				return;	// exit early
			}

			foreach ( $header_files as $tmpl_file ) {

				$tmpl_base = basename( $tmpl_file );
				$backup_file = $tmpl_file . '~backup-' . date( 'Ymd-His' );
				$backup_base = basename( $backup_file );
				$html_stripped = SucomUtil::get_stripped_php( $tmpl_file );
	
				// double check in case of reloads etc.
				if ( empty( $html_stripped ) || strpos( $html_stripped, '<head>' ) === false ) {
					$this->p->notice->err( sprintf( __( 'No %1$s HTML tag found in the %2$s template.', 'wpsso' ), '&lt;head&gt;', $tmpl_file ) );
					continue;
				}

				// make a backup of the original
				if ( ! copy( $tmpl_file, $backup_file ) ) {
					$this->p->notice->err( sprintf( __( 'Error copying %1$s to %2$s.', 'wpsso' ), $tmpl_file, $backup_base ) );
					continue;
				}

				$tmpl_contents = file_get_contents( $tmpl_file );
				$tmpl_contents = str_replace( '<head>', $head_action_php, $tmpl_contents );

				if ( ! $tmpl_fh = @fopen( $tmpl_file, 'wb' ) ) {
					$this->p->notice->err( sprintf( __( 'Failed to open template file %s for writing.', 'wpsso' ), $tmpl_file ) );
					continue;
				}

				if ( fwrite( $tmpl_fh, $tmpl_contents ) ) {
					$this->p->notice->upd( sprintf( __( 'The %1$s template has been successfully modified and saved. A backup copy of the original template is available as %2$s in the same folder.', 'wpsso' ), $tmpl_file, $backup_base ) );
					$have_changes = true;
				} else {
					$this->p->notice->err( sprintf( __( 'Failed to write the %1$s template. You may need to restore the original template saved as %2$s in the same folder.', 'wpsso' ), $tmpl_file, $backup_base ) );
				}

				fclose( $tmpl_fh );
			}

			if ( $have_changes ) {
				$dismiss_key = 'notice-header-tmpl-no-head-attr-' . SucomUtil::get_theme_slug_version();
				$this->p->notice->trunc_key( $dismiss_key, 'all' );	// Just in case.
			}
		}

		/**
		 * Called from the WpssoSubmenuGeneral and WpssoJsonSubmenuSchemaJsonLd classes.
		 */
		protected function add_schema_item_props_table_rows( array &$table_rows ) {

			$table_rows['schema_logo_url'] = $this->form->get_th_html( 
				'<a href="https://developers.google.com/structured-data/customize/logos">' .
				_x( 'Organization Logo URL', 'option label', 'wpsso' ) . '</a>',
					'', 'schema_logo_url', array( 'is_locale' => true ) ).
			'<td>' . $this->form->get_input( SucomUtil::get_key_locale( 'schema_logo_url', $this->p->options ), 'wide' ) . '</td>';

			$table_rows['schema_banner_url'] = $this->form->get_th_html( _x( 'Organization Banner URL',
				'option label', 'wpsso' ), '', 'schema_banner_url', array( 'is_locale' => true ) ).
			'<td>' . $this->form->get_input( SucomUtil::get_key_locale( 'schema_banner_url', $this->p->options ), 'wide' ) . '</td>';

			$table_rows['schema_img_max'] = $this->form->get_tr_hide( 'basic', 'schema_img_max' ).
			$this->form->get_th_html( _x( 'Maximum Images to Include', 'option label', 'wpsso' ), '', 'schema_img_max' ).
			'<td>' . $this->form->get_select( 'schema_img_max', range( 0, $this->p->cf['form']['max_media_items'] ), 'short', '', true ).
			( empty( $this->form->options['og_vid_prev_img'] ) ?
				'' : ' <em>' . _x( 'video preview images are enabled (and included first)',
					'option comment', 'wpsso' ) . '</em>' ) . '</td>';

			$table_rows['schema_img'] = $this->form->get_th_html( _x( 'Schema Image Dimensions',
				'option label', 'wpsso' ), '', 'schema_img_dimensions' ).
			'<td>' . $this->form->get_input_image_dimensions( 'schema_img' ) . '</td>';	// $use_opts = false

			$table_rows['schema_desc_len'] = $this->form->get_tr_hide( 'basic', 'schema_desc_len' ).
			$this->form->get_th_html( _x( 'Maximum Description Length', 'option label', 'wpsso' ), '', 'schema_desc_len' ).
			'<td>' . $this->form->get_input( 'schema_desc_len', 'short' ) . ' ' . _x( 'characters or less', 'option comment', 'wpsso' ) . '</td>';

			$table_rows['schema_author_name'] = $this->form->get_tr_hide( 'basic', 'schema_author_name' ).
			$this->form->get_th_html( _x( 'Author / Person Name Format', 'option label', 'wpsso' ), '', 'schema_author_name' ).
			'<td>' . $this->form->get_select( 'schema_author_name', $this->p->cf['form']['user_name_fields'] ) . '</td>';
		}

		/**
		 * Called from the WpssoSubmenuGeneral and WpssoJsonSubmenuSchemaJsonLd classes.
		 */
		protected function add_schema_item_types_table_rows( array &$table_rows, array $hide_in_view = array(), $schema_types = null ) {

			if ( ! is_array( $schema_types ) ) {
				$schema_types = $this->p->schema->get_schema_types_select( null, true );	// $add_none = true
			}

			foreach ( array( 
				'home_index' => _x( 'Item Type for Blog Front Page', 'option label', 'wpsso' ),
				'home_page' => _x( 'Item Type for Static Front Page', 'option label', 'wpsso' ),
				'user_page' => _x( 'Item Type for User / Author Page', 'option label', 'wpsso' ),
				'search_page' => _x( 'Item Type for Search Results Page', 'option label', 'wpsso' ),
				'archive_page' => _x( 'Item Type for Other Archive Page', 'option label', 'wpsso' ),
			) as $type_name => $th_label ) {

				$tr_html = '';
				$opt_key = 'schema_type_for_' . $type_name;

				if ( ! empty( $hide_in_view[$opt_key] ) ) {
					$tr_html = $this->form->get_tr_hide( $hide_in_view[$opt_key], $opt_key );
				}

				$table_rows[$opt_key] = $tr_html.$this->form->get_th_html( $th_label, '', $opt_key ).
				'<td>' . $this->form->get_select( $opt_key, $schema_types, 'schema_type' ) . '</td>';
			}

			/**
			 * Item Type by Post Type
			 */
			$type_select = '';
			$type_opt_keys = array();

			foreach ( $this->p->util->get_post_types( 'objects' ) as $pt ) {
				$type_opt_keys[] = $opt_key = 'schema_type_for_' . $pt->name;
				$type_select .= '<p>' . $this->form->get_select( $opt_key, $schema_types, 'schema_type' ) .
					' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso' ), $pt->label ) . '</p>' . "\n";
			}

			$type_opt_keys[] = $opt_key = 'schema_type_for_post_archive';
			$type_select .= '<p>' . $this->form->get_select( $opt_key, $schema_types, 'schema_type' ) .
				' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso' ),
					_x( '(Post Type) Archive Page', 'option comment', 'wpsso' ) ) . '</p>' . "\n";

			$tr_html = '';
			$tr_key = 'schema_type_for_ptn';
			$th_label = _x( 'Item Type by Post Type', 'option label', 'wpsso' );

			if ( ! empty( $hide_in_view[$tr_key] ) ) {
				$tr_html = $this->form->get_tr_hide( $hide_in_view[$tr_key], $type_opt_keys );
			}

			$table_rows[$tr_key] = $tr_html.$this->form->get_th_html( $th_label, '', $tr_key ).
			'<td>' . $type_select . '</td>';

			unset( $type_select, $type_opt_keys );	// Just in case.

			/**
			 * Item Type by Term Taxonomy
			 */
			$type_select = '';
			$type_opt_keys = array();

			foreach ( $this->p->util->get_taxonomies( 'objects' ) as $tax ) {
				$type_opt_keys[] = $opt_key = 'schema_type_for_tax_' . $tax->name;
				$type_select .= '<p>' . $this->form->get_select( $opt_key, $schema_types, 'schema_type' ) .
					' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso' ), $tax->label ) . '</p>' . "\n";
			}

			$tr_html = '';
			$tr_key = 'schema_type_for_ttn';
			$th_label = _x( 'Item Type by Term Taxonomy', 'option label', 'wpsso' );

			if ( ! empty( $hide_in_view[$tr_key] ) ) {
				$tr_html = $this->form->get_tr_hide( $hide_in_view[$tr_key], $type_opt_keys );
			}

			$table_rows[$tr_key] = $tr_html.$this->form->get_th_html( $th_label, '', $tr_key ).
			'<td>' . $type_select . '</td>';

			unset( $type_select, $type_opt_keys );	// Just in case.
		}

		/**
		 * Called from the WpssoSubmenuEssential, WpssoSubmenuGeneral, and WpssoJsonSubmenuSchemaJsonLd classes.
		 */
		protected function add_schema_knowledge_graph_table_rows( array &$table_rows ) {

			$table_rows['schema_knowledge_graph'] = $this->form->get_th_html( _x( 'Knowledge Graph for Home Page',
				'option label', 'wpsso' ), '', 'schema_knowledge_graph' ).
			'<td>' .
			'<p>' . $this->form->get_checkbox( 'schema_add_home_website' ) . ' ' .
				sprintf( __( 'Include <a href="%s">WebSite Information</a> for Google Search',
					'wpsso' ), 'https://developers.google.com/structured-data/site-name' ) . '</p>' .
			'<p>' . $this->form->get_checkbox( 'schema_add_home_organization' ) . ' ' .
				sprintf( __( 'Include <a href="%s">Organization Social Profile</a>',
					'wpsso' ), 'https://developers.google.com/structured-data/customize/social-profiles' ) . '</p>' .
			'<p>' . $this->form->get_checkbox( 'schema_add_home_person' ) . ' ' .
				sprintf( __( 'Include <a href="%s">Person Social Profile</a> for the Site Owner',
					'wpsso' ), 'https://developers.google.com/structured-data/customize/social-profiles' ) . '</p>' .
			'</td>';

			$site_owners = SucomUtil::get_user_select( array( 'administrator', 'editor' ) );

			$table_rows['schema_home_person_id'] = $this->form->get_th_html( _x( 'User for Person Social Profile',
				'option label', 'wpsso' ), '', 'schema_home_person_id' ).
			'<td>' . $this->form->get_select( 'schema_home_person_id', $site_owners, '', '', true ) . '</td>';
		}

		/**
		 * Called from the WpssoSubmenuEssential, WpssoSubmenuAdvanced, and WpssoSitesubmenuSiteadvanced classes.
		 * Note that the essential settings page will unset() some table rows to keep the options list to a minimum.
		 */
		protected function add_optional_advanced_table_rows( array &$table_rows, $network = false ) {

			$table_rows['plugin_preserve'] = '' .
			$this->form->get_th_html( _x( 'Preserve Settings on Uninstall', 'option label', 'wpsso' ), '', 'plugin_preserve' ).
			'<td>' . $this->form->get_checkbox( 'plugin_preserve' ) . '</td>' .
			self::get_option_site_use( 'plugin_preserve', $this->form, $network, true );

			$table_rows['plugin_debug'] = '' .
			$this->form->get_th_html( _x( 'Add Hidden Debug Messages', 'option label', 'wpsso' ), '', 'plugin_debug' ).
			'<td>' . ( ! $network && SucomUtil::get_const( 'WPSSO_HTML_DEBUG' ) ?
				$this->form->get_no_checkbox( 'plugin_debug' ) . ' <em>WPSSO_HTML_DEBUG constant is true</em>' :
				$this->form->get_checkbox( 'plugin_debug' ) ) . '</td>' .
			self::get_option_site_use( 'plugin_debug', $this->form, $network, true );

			if ( $network || ! $this->p->check->aop( $this->p->lca, true, $this->p->avail['*']['p_dir'] ) ) {

				$table_rows['plugin_hide_pro'] = $this->form->get_tr_hide( 'basic', 'plugin_hide_pro' ) .
				$this->form->get_th_html( _x( 'Hide All Pro Version Options', 'option label', 'wpsso' ), '', 'plugin_hide_pro' ) .
				'<td>' . $this->form->get_checkbox( 'plugin_hide_pro' ) . '</td>' .
				self::get_option_site_use( 'plugin_show_opts', $this->form, $network, true );

			} else {
				$this->form->get_hidden( 'plugin_hide_pro', 0, true );
			}

			$table_rows['plugin_show_opts'] = '' .
			$this->form->get_th_html( _x( 'Options to Show by Default', 'option label', 'wpsso' ), '', 'plugin_show_opts' ) .
			'<td>' . $this->form->get_select( 'plugin_show_opts', $this->p->cf['form']['show_options'] ) . '</td>' .
			self::get_option_site_use( 'plugin_show_opts', $this->form, $network, true );

			if ( ! empty( $this->p->cf['*']['lib']['shortcode'] ) ) {

				$table_rows['plugin_shortcodes'] = $this->form->get_tr_hide( 'basic', 'plugin_shortcodes' ) .
				$this->form->get_th_html( _x( 'Enable Plugin Shortcode(s)', 'option label', 'wpsso' ), '', 'plugin_shortcodes' ) .
				'<td>' . $this->form->get_checkbox( 'plugin_shortcodes' ) . '</td>' .
				self::get_option_site_use( 'plugin_shortcodes', $this->form, $network, true );
			}

			if ( ! empty( $this->p->cf['*']['lib']['widget'] ) ) {

				$table_rows['plugin_widgets'] = $this->form->get_tr_hide( 'basic', 'plugin_widgets' ) .
				$this->form->get_th_html( _x( 'Enable Plugin Widget(s)', 'option label', 'wpsso' ), '', 'plugin_widgets' ) .
				'<td>' . $this->form->get_checkbox( 'plugin_widgets' ) . '</td>' .
				self::get_option_site_use( 'plugin_widgets', $this->form, $network, true );
			}
		}

		public static function get_option_site_use( $name, $form, $network = false, $enabled = false ) {
			if ( $network ) {
				return $form->get_th_html( _x( 'Site Use',
					'option label (very short)', 'wpsso' ),
						'site_use' ).( $enabled || self::$pkg['wpsso']['aop'] ?
					'<td class="site_use">' . $form->get_select( $name . ':use',
						WpssoConfig::$cf['form']['site_option_use'], 'site_use' ) . '</td>' :
					'<td class="blank site_use">' . $form->get_select( $name . ':use',
						WpssoConfig::$cf['form']['site_option_use'], 'site_use', '', true, true ) . '</td>' );
			} else {
				return '';
			}
		}

		public function get_readme_info( $ext, $read_cache = true ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log_args( array( 
					'ext' => $ext,
					'read_cache' => $read_cache,
				) );
			}

			$file_name = 'readme.txt';
			$file_key = SucomUtil::sanitize_hookname( $file_name );	// Rename readme.txt to readme_txt.
			$file_dir = SucomUtil::get_const( strtoupper( $ext ) . '_PLUGINDIR' );
			$file_local = $file_dir ? trailingslashit( $file_dir ).$file_name : false;
			$file_remote = isset( $this->p->cf['plugin'][$ext]['url'][$file_key] ) ? 
				$this->p->cf['plugin'][$ext]['url'][$file_key] : false;

			static $cache_exp_secs = null;

			$cache_md5_pre = $this->p->lca . '_';

			if ( ! isset( $cache_exp_secs ) ) {
				$cache_exp_filter = $this->p->lca . '_cache_expire_' . $file_key;	// 'wpsso_cache_expire_readme_txt'
				$cache_exp_secs = (int) apply_filters( $cache_exp_filter, DAY_IN_SECONDS );
			}

			$cache_salt = __METHOD__ . '(ext:' . $ext . ')';
			$cache_id = $cache_md5_pre . md5( $cache_salt );

			$readme_info = false;
			$readme_content = false;
			$readme_from_url = false;

			if ( $cache_exp_secs > 0 ) {
				if ( $read_cache ) {
					$readme_info = get_transient( $cache_id );
					if ( is_array( $readme_info ) ) {
						return $readme_info;	// stop here
					}
				}
				if ( $file_remote && strpos( $file_remote, '://' ) ) {
					// clear the cache first if reading the cache is disabled
					if ( ! $read_cache ) {
						$this->p->cache->clear( $file_remote );
					}
					$readme_from_url = true;
					$readme_content = $this->p->cache->get( $file_remote, 'raw', 'file', $cache_exp_secs );
				}
			} else {
				delete_transient( $cache_id );	// Just in case.
			}

			if ( empty( $readme_content ) ) {
				if ( $file_local && file_exists( $file_local ) && $fh = @fopen( $file_local, 'rb' ) ) {
					$readme_from_url = false;
					$readme_content = fread( $fh, filesize( $file_local ) );
					fclose( $fh );
				}
			}

			if ( empty( $readme_content ) ) {
				$readme_info = array();	// save an empty array
			} else {
				$parser = new SuextParseReadme( $this->p->debug );
				$readme_info = $parser->parse_readme_contents( $readme_content );
	
				// Remove possibly inaccurate information from the local readme file.
				if ( ! $readme_from_url && is_array( $readme_info ) ) {
					foreach ( array( 'stable_tag', 'upgrade_notice' ) as $key ) {
						unset ( $readme_info[$key] );
					}
				}
			}

			// save the parsed readme to the transient cache
			if ( $cache_exp_secs > 0 ) {
				set_transient( $cache_id, $readme_info, $cache_exp_secs );
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'readme_info saved to transient cache for ' . $cache_exp_secs . ' seconds' );
				}
			}

			return is_array( $readme_info ) ? $readme_info : array();	// Just in case.
		}

		public function get_config_url_content( $ext, $file_name, $cache_exp_secs = null ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log_args( array( 
					'ext' => $ext,
					'file_name' => $file_name,
					'cache_exp_secs' => $cache_exp_secs,
				) );
			}

			$file_name = SucomUtil::sanitize_file_path( $file_name );
			$file_key = SucomUtil::sanitize_hookname( basename( $file_name ) );	// html/setup.html -> setup_html
			$file_dir = SucomUtil::get_const( strtoupper( $ext ) . '_PLUGINDIR' );
			$file_local = $file_dir ? trailingslashit( $file_dir ).$file_name : false;
			$file_remote = isset( $this->p->cf['plugin'][$ext]['url'][$file_key] ) ? 
				$this->p->cf['plugin'][$ext]['url'][$file_key] : false;

			if ( null === $cache_exp_secs ) {
				$cache_exp_secs = WEEK_IN_SECONDS;
			}

			$cache_exp_filter = $this->p->lca . '_cache_expire_' . $file_key;	// 'wpsso_cache_expire_setup_html'
			$cache_exp_secs = (int) apply_filters( $cache_exp_filter, $cache_exp_secs );
			$cache_content = false;

			if ( $cache_exp_secs > 0 ) {
				if ( $file_remote && strpos( $file_remote, '://' ) ) {
					$cache_content = $this->p->cache->get( $file_remote, 'raw', 'file', $cache_exp_secs );
				}
			}

			if ( empty( $cache_content ) ) {
				if ( $file_local && file_exists( $file_local ) && $fh = @fopen( $file_local, 'rb' ) ) {
					$cache_content = fread( $fh, filesize( $file_local ) );
					fclose( $fh );
				}
			}

			return $cache_content;
		}

		public function plugin_complete_actions( $actions ) {
			if ( ! empty( $this->pageref_url ) && ! empty( $this->pageref_title ) ) {
				foreach ( $actions as $action => &$html ) {
					switch ( $action ) {
						case 'plugins_page':
							$html = '<a href="' . $this->pageref_url . '" target="_parent">' .
								sprintf( __( 'Return to %s', 'wpsso' ), $this->pageref_title ) . '</a>';
							break;
						default:
							if ( preg_match( '/^(.*href=")([^"]+)(".*)$/', $html, $matches ) ) {
								$url = add_query_arg( array(
									$this->p->lca . '_pageref_url' => urlencode( $this->pageref_url ),
									$this->p->lca . '_pageref_title' => urlencode( $this->pageref_title ),
								), $matches[2] );
								$html = $matches[1].$url.$matches[3];
							}
							break;
					}
				}
			}
			return $actions;
		}

		public function plugin_complete_redirect( $url ) {
			if ( strpos( $url, '?activate=true' ) ) {
				if ( ! empty( $this->pageref_url ) ) {
					$this->p->notice->upd( __( 'Plugin <strong>activated</strong>.' ) );	// green status w check mark
					$url = $this->pageref_url;
				}
			}
			return $url;
		}

		public function get_check_for_updates_link( $only_url = false ) {

			$link_url = '';
			$link_html = '';

			if ( class_exists( 'WpssoUm' ) ) {

				$this->set_plugin_pkg_info();

				$link_url = wp_nonce_url( $this->p->util->get_admin_url( 'um-general?' . $this->p->lca . '-action=check_for_updates' ),
					WpssoAdmin::get_nonce_action(), WPSSO_NONCE_NAME );

				// translators: %1$s is the URL, %2$s is the short plugin name
				$link_html = sprintf( __( 'You may <a href="%1$s">refresh the update information for %2$s and its add-ons</a> to check if newer versions are available.', 'wpsso' ), $link_url, self::$pkg[$this->p->lca]['short'] );

			} elseif ( empty( $_GET['force-check'] ) ) {

				$link_url = self_admin_url( 'update-core.php?force-check=1' );

				// translators: %1$s is the URL
				$link_html = sprintf( __( 'You may <a href="%1$s">refresh the update information for WordPress (plugins, themes, and translations)</a> to check if newer versions are available.', 'wpsso' ), $link_url );

			}

			return $only_url ? $link_url : $link_html;
		}

		/**
		 * Returns a 128x128px image.
		 */
		public function get_ext_img_icon( $ext ) {

			/**
			 * The default image is a transparent 1px gif.
			 */
			$img_src = 'src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="';

			if ( ! empty( $this->p->cf['plugin'][$ext]['img']['icons'] ) ) {

				$icons = $this->p->cf['plugin'][$ext]['img']['icons'];

				if ( ! empty( $icons['low'] ) ) {
					$img_src = 'src="' . $icons['low'] . '"';
				}

				if ( ! empty( $icons['high'] ) ) {
					$img_src .= ' srcset="' . $icons['high'] . ' 256w"';
				}
			}

			return '<img ' . $img_src . ' width="128" height="128" style="width:128px; height:128px;"/>';
		}

		/**
		 * If an add-on is not available, return a short sentence that this add-on is required.
		 *
		 * $mixed = wpssojson, json, etc.
		 */
		public function get_ext_required_msg( $mixed ) {

			$html = '';

			if ( ! is_string( $mixed ) ) {						// Just in case.
				return $html;
			}

			if ( strpos( $mixed, $this->p->lca ) === 0 ) {				// A complete lower case acronym was provided.
				$p_ext = substr( $ext, 0, strlen( $this->p->lca ) );		// Change 'wpssojson' to 'json'
				$ext   = $mixed;
			} else {
				$p_ext = $mixed;
				$ext   = $this->p->lca . $p_ext;				// Change 'json' to 'wpssojson'
			}

			if ( $this->p->lca === $mixed ) {					// The main plugin is not considered an add-on.
				return $html;
			} elseif ( ! empty( $this->p->avail['p_ext'][$p_ext] ) ) {		// Add-on is already active.
				return $html;
			} elseif ( empty( $this->p->cf['plugin'][$ext]['short'] ) ) {		// Add-on config is not defined.
				return $html;
			}

			$short = $this->p->cf['plugin'][$ext]['short'];

			$html .= ' <span class="ext-req-msg">';

			if ( ! empty( $this->p->cf['plugin'][$ext]['url']['home'] ) ) {
				$html .= '<a href="' . $this->p->cf['plugin'][$ext]['url']['home'] . '">';
			}

			$html .= sprintf( _x( '%s add-on required', 'option comment', 'wpsso' ), $short );

			if ( ! empty( $this->p->cf['plugin'][$ext]['url']['home'] ) ) {
				$html .= '</a>';
			}

			$html .= '</span>';

			return $html;
		}
	}
}
