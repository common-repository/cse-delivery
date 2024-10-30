<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.03.2020
 * Time: 11:05
 */

class WCShippingCSE extends WC_Shipping_Method {


	private $optionsName = "wc_cse_options";
	private $options = [];

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {

		parent::__construct($instance_id);

		$this->id                 = 'cse';
		$this->method_title       = "Доставка КСЕ";
		$this->title              = "Доставка КСЕ";
		$this->method_description = "Доставка Курьер Сервис Экспресс";
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->enabled            = 'yes';
		$this->options            = get_option( $this->optionsName );
	}

	/**
	 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
	 *
	 * @access public
	 *
	 * @param mixed $package
	 *
	 * @return void
	 * @throws Requests_Exception_HTTP_502
	 */
	public function calculate_shipping( $package = [] ) {

		$city = $package["destination"]["city"];
		if ( ! empty( $city ) ) {
			$login    = $this->options["wc_cse_login"];
			$password = $this->options["wc_cse_password"];

			if ( ! $login && ! $password ) {
				return;
			}

			$totalWeight   = 0;
			$totalQuantity = 0;

			foreach ( $package['contents'] as $content ) {

				$convertRate = $this->getConvertRate();

				/** @var $product WC_Product */
				$product       = $content['data'];
				$productWeight = ! empty( $product->get_weight() ) ? ($product->get_weight() * $convertRate['weight']) : $this->options['wc_cse_weight'] ?? 0;
				$quantity      = $content["quantity"];

				$width  = ! empty( $product->get_width() ) ? ($product->get_width() * $convertRate['length']): 0;
				$height = ! empty( $product->get_height() ) ? ($product->get_height() * $convertRate['length']) : 0;
				$length = ! empty( $product->get_length() ) ? ($product->get_length() * $convertRate['length']) : 0;

				$volumeWeight  = round( ( ( floatval( $width ) * floatval( $height ) * floatval( $length ) ) / 5000 ) * $quantity, 2 );
				$weight        = round( floatval( $productWeight ) * $quantity, 2 );
				$totalWeight   = $volumeWeight > $weight ? $volumeWeight : $weight;
				$totalQuantity += $quantity;
			}

			$obKCE = new CSE( $login, $password );

			$to             = $city;
			$from           = $this->options["wc_cse_city"] ?? '';
			$deliveryMethod = $this->options["wc_cse_kurierka"] ?? '';
			$urgency        = $this->options['wc_cse_urgency_code'] ?? '';

			$result = $obKCE->getDeliveryCost( $from,
				$to, $totalWeight, $totalQuantity, $deliveryMethod, $urgency
			);

			if ( $result['currency'] ) {
				$rate = array(
					'id'        => $this->id . '_courier',
					'label'     => $this->title . ' (Курьер) ' . CSEFunctions::formatDeliveryRange( $result['mindays'], $result['days'] ),
					'cost'      => $this->getDeliveryCost( $result['cost'] ),
					'meta_data' => [
						'rate_id' => 'courier'
					]
				);
				$this->add_rate( $rate );
			}

			$deliveryMethod = $this->options["wc_cse_pvz"];
			$result         = $obKCE->GetDeliveryCost( $from,
				$to, $totalWeight, $totalQuantity, $deliveryMethod, $urgency );
			if ( $result['currency'] ) {
				$rate = array(
					'id'        => $this->id . '_pvz',
					'label'     => $this->title . ' (ПВЗ) ' . CSEFunctions::formatDeliveryRange( $result['mindays'], $result['days'] ),
					'cost'      => $this->getDeliveryCost( $result['cost'] ),
					'meta_data' => [
						'rate_id' => 'pvz'
					]
				);
				$this->add_rate( $rate );
			}

		}
	}

	private function getDeliveryCost( $cost ) {
		$extraChargeType  = $this->options["wc_cse_extra_charge_type"] ?? '';
		$extraChargeValue = $this->options["wc_cse_extra_charge_value"] ?? 0;
		$extraChargeValue = floatval( $extraChargeValue );
		$cost             = floatval( $cost );
		$result           = 0;
		switch ( $extraChargeType ) {
			case 'percent':
				$result = $cost + ( $cost * ( $extraChargeValue / 100 ) );
				break;
			case 'fix':
				$result = $cost + $extraChargeValue;
				break;
			case 'fixprice':
				$result = $extraChargeValue;
				break;
			default:
				$result = $cost;
				break;
		}

		return $result < 0 ? 0 : $result;
	}

	private function getConvertRate() {

		$weightUnit    = get_option( 'woocommerce_weight_unit' );
		$dimensionUnit = get_option( 'woocommerce_dimension_unit' );

		switch ( $weightUnit ) {
			case 'g':
				$weightRate = 0.001;
				break;
			default:
				$weightRate = 1;
		}

		switch ( $dimensionUnit ) {
			case 'm':
				$dimensionRate = 100;
				break;
			default:
				$dimensionRate = 1;
		}

		return [
			'length'  => $dimensionRate,
			'weight' => $weightRate,
		];
	}
}