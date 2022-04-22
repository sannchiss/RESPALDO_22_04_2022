<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\FlexibleShippingFedex
 */

namespace WPDesk\FlexibleShippingFedex;

use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use FedExVendor\WPDesk\Notice\AjaxHandler;
use FedExVendor\WPDesk\Tracker\Deactivation\TrackerFactory;
use FedExVendor\WPDesk\WooCommerceShipping\ActivePayments;
use FedExVendor\WPDesk\WooCommerceShipping\Assets;
use FedExVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
use FedExVendor\WPDesk\WooCommerceShipping\OrderMetaData\AdminOrderMetaDataDisplay;
use FedExVendor\WPDesk\WooCommerceShipping\OrderMetaData\FrontOrderMetaDataDisplay;
use FedExVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleAdminOrderMetaDataInterpreterImplementation;
use FedExVendor\WPDesk\WooCommerceShipping\PluginShippingDecisions;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
use FedExVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters\FallbackAdminMetaDataInterpreter;
use FedExVendor\WPDesk_Plugin_Info;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use FedExVendor\WPDesk\Logger\WPDeskLoggerFactory;
use FedExVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use FedExVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FedExVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FedExVendor\WPDesk\FedexShippingService\FedexShippingService;
use FedExVendor\WPDesk\WooCommerceShipping\ShopSettings;
use FedExVendor\WPDesk\RepositoryRating\RatingPetitionNotice;
use FedExVendor\WPDesk\RepositoryRating\TimeWatcher\ShippingMethodGlobalSettingsWatcher;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @package WPDesk\FlexibleShippingFedex
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );
		$this->setLogger( $this->is_debug_mode() ? ( new WPDeskLoggerFactory() )->createWPDeskLogger( 'fedex' ) : new NullLogger() );

		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
	}

	/**
	 * Returns true when debug mode is on.
	 *
	 * @return bool
	 */
	private function is_debug_mode() {
		$global_fedex_settings = $this->get_global_fedex_settings();
		return isset( $global_fedex_settings['debug_mode'] ) && 'yes' === $global_fedex_settings['debug_mode'];
	}


	/**
	 * Get global Fedex settings.
	 *
	 * @return string[]
	 */
	private function get_global_fedex_settings() {
		return get_option( 'woocommerce_' . FedexShippingService::UNIQUE_ID . '_settings', array() );
	}

	/**
	 * Init plugin
	 *
	 * @return void
	 */
	public function init() {
		$global_fedex_settings = new SettingsValuesAsArray( $this->get_global_fedex_settings() );

		// @phpstan-ignore-next-line.
		$fedex_service = apply_filters( 'flexible_shipping_fedex_shipping_service', new FedexShippingService( $this->logger, new ShopSettings( FedexShippingService::UNIQUE_ID ) ) );

		$this->add_hookable(
			new Assets( $this->get_plugin_url() . 'vendor_prefixed/wpdesk/wp-woocommerce-shipping/assets', 'fedex' )
		);
		$this->add_hookable( new SettingsSidebar() );
		$this->init_repository_rating();

		$admin_meta_data_interpreter = new AdminOrderMetaDataDisplay( FedexShippingMethod::UNIQUE_ID );
		$admin_meta_data_interpreter->add_interpreter(
			new SingleAdminOrderMetaDataInterpreterImplementation(
				WooCommerceShippingMetaDataBuilder::SERVICE_TYPE,
				__( 'Service Code', 'flexible-shipping-fedex' )
			)
		);
		$admin_meta_data_interpreter->add_interpreter( new FallbackAdminMetaDataInterpreter() );
		$admin_meta_data_interpreter->add_hidden_order_item_meta_key( WooCommerceShippingMetaDataBuilder::COLLECTION_POINT );
		$this->add_hookable( $admin_meta_data_interpreter );

		$meta_data_interpreter = new FrontOrderMetaDataDisplay( FedexShippingMethod::UNIQUE_ID );
		$this->add_hookable( $meta_data_interpreter );

		/**
		 * Handles API Status AJAX requests.
		 *
		 * @var FieldApiStatusAjax $api_ajax_status_handler .
		 */
		// @phpstan-ignore-next-line.
		$api_ajax_status_handler = new FieldApiStatusAjax( $fedex_service, $global_fedex_settings, $this->logger );
		$this->add_hookable( $api_ajax_status_handler );

		// @phpstan-ignore-next-line.
		$plugin_shipping_decisions = new PluginShippingDecisions( $fedex_service, $this->logger );
		$plugin_shipping_decisions->set_field_api_status_ajax( $api_ajax_status_handler );

		FedexShippingMethod::set_plugin_shipping_decisions( $plugin_shipping_decisions );

		$this->add_hookable( new ActivePayments\Integration( FedexShippingMethod::UNIQUE_ID ) );

		parent::init();
	}

	/**
	 * Show repository rating notice when time comes.
	 *
	 * @return void
	 */
	private function init_repository_rating() {
		$this->add_hookable( new AjaxHandler( trailingslashit( $this->get_plugin_url() ) . 'vendor_prefixed/wpdesk/wp-notice/assets' ) );

		$time_tracker = new ShippingMethodGlobalSettingsWatcher( FedexShippingService::UNIQUE_ID );
		$this->add_hookable( $time_tracker );
		$this->add_hookable(
			new RatingPetitionNotice(
				$time_tracker,
				FedexShippingService::UNIQUE_ID,
				$this->plugin_info->get_plugin_name(),
				'https://wpde.sk/fs-fedex-rate'
			)
		);
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();

		add_filter( 'woocommerce_shipping_methods', array( $this, 'woocommerce_shipping_methods_filter' ), 20, 1 );

		add_action( 'admin_init', array( $this, 'init_deactivation_tracker' ) );

		$this->hooks_on_hookable_objects();
	}

	/**
	 * Init deactivation tracker.
	 *
	 * @return void
	 */
	public function init_deactivation_tracker() {
		$deactivation_tracker = TrackerFactory::createDefaultTracker(
			'flexible-shipping-fedex',
			'flexible-shipping-fedex/flexible-shipping-fedex.php',
			__( 'Flexible Shipping For FedEx', 'flexible-shipping-fedex' )
		);
		$deactivation_tracker->hooks();
	}

	/**
	 * Adds shipping method to Woocommerce.
	 *
	 * @param string[] $methods Methods.
	 *
	 * @return string[]
	 */
	public function woocommerce_shipping_methods_filter( $methods ) {
		$methods[ FedexShippingService::UNIQUE_ID ] = FedexShippingMethod::class;

		return $methods;
	}

	/**
	 * Quick links on plugins page.
	 *
	 * @param string[] $links .
	 *
	 * @return string[]
	 */
	public function links_filter( $links ) {
		$is_pl        = 'pl_PL' === get_locale();
		$docs_link    = $is_pl ? 'https://www.wpdesk.pl/docs/fedex-woocommerce-docs/' : 'https://docs.flexibleshipping.com/category/129-fedex/';
		$docs_link   .= '?utm_source=fedex&utm_medium=link&utm_campaign=plugin-list-docs';
		$support_link = 'https://www.flexibleshipping.com/support/?utm_source=fedex&utm_medium=link&utm_campaign=plugin-list-support';
		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=flexible_shipping_fedex' );

		$external_attributes = ' target="_blank" ';

		$plugin_links = array(
			'<a href="' . $settings_url . '">' . __( 'Settings', 'flexible-shipping-fedex' ) . '</a>',
			'<a href="' . $docs_link . '"' . $external_attributes . '>' . __( 'Docs', 'flexible-shipping-fedex' ) . '</a>',
			'<a href="' . $support_link . '"' . $external_attributes . '>' . __( 'Support', 'flexible-shipping-fedex' ) . '</a>',
		);

		if ( ! defined( 'FLEXIBLE_SHIPPING_FEDEX_PRO_VERSION' ) ) {
			$upgrade_link   = $is_pl ? 'https://www.wpdesk.pl/sklep/fedex-woocommerce/' : 'https://flexibleshipping.com/products/flexible-shipping-fedex-pro/';
			$upgrade_link  .= '?utm_source=fedex&utm_medium=quick-link&utm_campaign=upgrade-quick-link';
			$plugin_links[] = '<a target="_blank" href="' . $upgrade_link . '" style="color:#d64e07;font-weight:bold;">' . __( 'Upgrade', 'flexible-shipping-fedex' ) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}
}
