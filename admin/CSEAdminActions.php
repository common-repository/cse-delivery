<?php


class CSEAdminActions {

	private $options;

	public function __construct( $options ) {
		$this->options = $options;
	}

	/* AJAX ACTIONS */
	public function getCities() {
		/** @var $wpdb wpdb */
		global $wpdb;
		$tableName = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$search    = sanitize_text_field( $_REQUEST["search"] );

		$query = "SELECT guid, name FROM $tableName INNER JOIN WHERE name LIKE LOWER('$search%') ORDER BY priority DESC";
		$query = "SELECT t.guid, t.name, t1.name as parent FROM $tableName as t INNER JOIN $tableName as t1 ON t1.guid = t.parent_guid WHERE t.name LIKE LOWER('$search%') ORDER BY t.priority DESC LIMIT 20";

		$rows = $wpdb->get_results( $query, "ARRAY_A" );

		$result = [];
		foreach ( $rows as $row ) {
			$result[] = [
				"id"   => $row["guid"],
				"text" => $row["name"] . ( ! empty( $row['parent'] ) ? ' ( ' . $row['parent'] . ' )' : '' )
			];
		}
		echo wp_json_encode( [
			"results" => $result
		] );
		die();
	}

	public function getDebugInfo() {
		$id   = sanitize_text_field( $_REQUEST['id'] );
		$type = sanitize_text_field( $_REQUEST['type'] );

		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;
		$row        = $wpdb->get_row( "SELECT {$type} FROM {$table_name } WHERE id = {$id};", 'ARRAY_A' );
		$response   = $row[ $type ];
		ob_flush();
		header( 'Content-Type: text/xml' );
		echo base64_decode( $response );
		die();
	}

	public function getWaybillCreateForm() {
		$orderID = sanitize_text_field( $_GET['order_id'] );
		$order   = wc_get_order( $orderID );

		$totalWeight   = 0;
		$totalQuantity = 0;

		foreach ( $order->get_items() as $item ) {
			/** @var $product WC_Product */
			$product       = wc_get_product( $item->get_product_id() );
			$productWeight = ! empty( $product->get_weight() ) ? $product->get_weight() : $this->options['wc_cse_weight'] ?? 0;
			$quantity      = intval( $item->get_quantity() );

			$width  = ! empty( $product->get_width() ) ? $product->get_width() : 0;
			$height = ! empty( $product->get_height() ) ? $product->get_height() : 0;
			$length = ! empty( $product->get_length() ) ? $product->get_length() : 0;

			$volumeWeight  = round( ( ( floatval( $width ) * floatval( $height ) * floatval( $length ) ) / 5000 ) * $quantity, 2 );
			$weight        = round( floatval( $productWeight ) * $quantity, 2 );
			$totalWeight   = $volumeWeight > $weight ? $volumeWeight : $weight;
			$totalQuantity += $quantity;
		}

		include __DIR__ . '/partials/kce_send_order_to_cse_form.php';
		die();
	}

	/**
	 */
	public function sendNewWaybillToCSE() {
		$login    = $this->options['wc_cse_login'];
		$password = $this->options['wc_cse_password'];
		$wayBill  = new CSEOrder( $login, $password );

		$order = wc_get_order( sanitize_text_field( $_POST["data"]["order_id"] ) );

		if ( ! $order ) {
			wp_redirect( $_SERVER["HTTP_REFERER"] );
		}

		$recepientName    = sanitize_text_field( $_POST["data"]["recipient_name"] );
		$deliveringTo     = $order->get_meta( '_shipping_city_guid', true );
		$deliveringFrom   = $this->options["wc_cse_city"];
		$recepientAddress = $order->get_shipping_address_1();
		$recepientEmail   = $order->get_billing_email();
		$recepientPhone   = sanitize_text_field( $_POST["data"]["recipient_phone"] );
		$urgency          = $this->options['wc_cse_urgency_code'];
		$cargoDescr       = '';
		$cargoPackageQty  = sanitize_text_field( $_POST["data"]["qty"] );
		$weight           = sanitize_text_field( $_POST["data"]["weight"] );
		$orderProducts    = [];
		/** @var WC_Order_Item_Product $product */
		foreach ( $order->get_items() as $product ) {
			$storeProduct    = $product->get_product();
			$orderProducts[] = [
				"ID"    => $storeProduct->get_id(),
				"SKU"   => $storeProduct->get_sku(),
				"NAME"  => $storeProduct->get_name(),
				"PRICE" => $storeProduct->get_price(),
				"CURR"  => $order->get_currency(),
				"QTY"   => $product->get_quantity(),
				"UNIT"  => '',
			];
		}

		$CODAmount         = sanitize_text_field( $_POST['data']['total'] );
		$declaredValueRate = floatval( $order->get_total() - floatval( $order->get_shipping_total() ) );

		$shippingMethods = $order->get_items( 'shipping' );
		$deliveryOfCargo = 0;
		$shippingMethod  = reset( $shippingMethods );
		/**
		 * @var $shippingMethod WC_Order_Item_Shipping
		 */
		if ( $shippingMethod ) {
			if ( $shippingMethod->get_method_id() == 'cse' ) {
				$rate_id = $shippingMethod->get_meta( 'rate_id' );
				switch ( $rate_id ) {
					case 'courier':
						$deliveryOfCargo = $this->options['wc_cse_kurierka'];
						break;
					case 'pvz':
						$deliveryOfCargo = $this->options['wc_cse_pvz'];
						$csePVZGuid      = get_post_meta( $order->get_id(), '_cse_pvz_guid', true );
						if ( $csePVZGuid ) {
							$wayBill->setPvzGuid( $csePVZGuid );
						}
						break;
				}
			} else {
				wp_die( 'Курьер Сервис Экспресс не является выбранным методом доставки' );
			}
		} else {
			wp_die( 'Курьер Сервис Экспресс не является выбранным методом доставки' );
		}

		$typeOfCargo    = $this->options["wc_cse_cargo_type"];
		$typeOfPayer    = $this->options["wc_cse_payer_code"];
		$wayOfPayment   = $this->options["wc_cse_payment_method"];
		$orderId        = $order->get_order_number();
		$takeDate       = sanitize_text_field( $_POST["data"]["date"] ) . 'T' . sanitize_text_field( $_POST['data']['time'] ) . ':00';
		$deliveryAmount = $order->get_shipping_total();

		$wayBill->setOrderID( $orderId )
		        ->setRecipientName( $recepientName )
		        ->setRecipientFullAddress( $recepientAddress )
		        ->setRecipientPhone( $recepientPhone )
		        ->setRecipientEmail( $recepientEmail )
		        ->setGeoTo( $deliveringTo )
		        ->setGeoFrom( $deliveringFrom )
		        ->setTypeOfCargo( $typeOfCargo )
		        ->setCargoDescription( $cargoDescr )
		        ->setCargoPackageQty( $cargoPackageQty )
		        ->setUrgency( $urgency )
		        ->setClientName( $this->options['wc_cse_name_company'] )
		        ->setSenderEmail( $this->options['wc_cse_email'] )
		        ->setSenderAddress( $this->options['wc_cse_address'] )
		        ->setSenderPhone( $this->options["wc_cse_phone"] )
		        ->setTakeDate( $takeDate )
		        ->setTypeOfPayer( $typeOfPayer )
		        ->setWayOfPayment( $wayOfPayment )
		        ->setBasketItems( $orderProducts )
		        ->setWeight( $weight )
		        ->setTenderComment( sanitize_textarea_field( $_POST["data"]["comment"] ) )
		        ->setDeliveryOfCargo( $deliveryOfCargo )
		        ->setCODAmount( $CODAmount )
		        ->setDeclaredValueRate( $declaredValueRate )
		        ->setDeliveryAmount( $deliveryAmount )
		        ->setDeliveryDate( sanitize_text_field( $_POST["data"]['recipient_date'] ) );
		$wayBillNumber = $wayBill->SetWaybill();

		if ( $wayBillNumber ) {
			global $wpdb;
			$table_name  = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;
			$queryData   = [
				'number'       => $wayBillNumber,
				'order_id'     => $orderId,
				'date'         => $takeDate,
				'deliveryFrom' => CSEHelper::getCityName( $deliveringFrom ),
				'weight'       => $weight,
				'qty'          => $cargoPackageQty
			];
			$queryFormat = [
				'%s',
				'%d',
				'%s',
				'%s',
				'%f',
				'%f'
			];
			$id          = $wpdb->insert( $table_name, $queryData, $queryFormat );
			if ( $id === false ) {
				CSEFunctions::setMessage( CSEConstants::SUCCESS_MESSAGE_TYPE, $wpdb->last_error );
			}
		}
		wp_redirect( wp_sanitize_redirect( $_REQUEST['_wp_http_referer'] ) );
		die();
	}

	public function getWaybillPdf() {
		$login    = $this->options['wc_cse_login'];
		$password = $this->options['wc_cse_password'];
		$obKSE    = new CSEOrder( $login, $password );

		$type   = sanitize_text_field( $_GET['type'] );
		$number = sanitize_text_field( $_GET['number'] );

		$pdf = $obKSE->getWayBillPDF( $type, $number, $this->options['wc_cse_payform_name'] );

		ob_end_flush();
		header( 'Content-Type: application/pdf; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="' . $this->options['wc_cse_payform_name'] . '_' . $number . '.pdf"' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
		echo $pdf;
		die();
	}
	/* END AJAX ACTIONS */

	/* POST ACTIONS */
	public function createCSEOrder() {
		$data = $this->getPostData();
		if ( $data ) {
			$this->sendOrder( $data );
		}
		wp_redirect( wp_sanitize_redirect( $_POST['_wp_http_referer'] ) );
	}

	/* END POST ACTIONS */

	private function getPostData() {
		if ( ! empty( $_POST ) ) {
			foreach ( $_POST as $postKey => $post ) {
				if ( is_array( $post ) ) {
					foreach ( $post as $itemKey => $item ) {
						if ( is_string( $item ) ) {
							$_POST[ $postKey ][ $itemKey ] = sanitize_text_field( $item );
						}
					}
				} else {
					$_POST[ $postKey ] = sanitize_text_field( $post );
				}
			}

			return $_POST;
		} else {
			return false;
		}
	}

	private function sendOrder( $data ) {
		$obKCE = new CSEOrder( $this->options['wc_cse_login'], $this->options['wc_cse_password'] );

		$weight = 0;
		$qty    = 0;
		if ( ! empty( $data["numbers"] ) ) {
			$wayBills = CSEHelper::getWayBillsByNumber( $data["numbers"] );
			foreach ( $wayBills as $bill ) {
				$weight += floatval( $bill["weight"] );
				$qty    += intval( $bill["qty"] );
			}
		} else {
			return false;
		}

		$obKCE->setTakeDate( $data["deliveryDate"] )
		      ->setTakeTime( $data['deliveryTime'] )
		      ->setDeliveryTime( $data['recipient_time_from'] )
		      ->setDeliveryTimeOf( $data['recipient_time_to'] )
		      ->setClientName( $data["sender_name"] )
		      ->setGeoFrom( $data["sender_geo"] )
		      ->setRecipientFullAddress( $data["sender_address"] )
		      ->setSenderPhone( $data["sender_phone"] )
		      ->setRecipientEmail( $data["sender_email"] )
		      ->setUrgency( $data["urgency"] )
		      ->setTypeOfPayer( $data["payer"] )
		      ->setPaymentMethod( $data["payment_type"] )
		      ->setShippingMethod( $data["delivery_type"] )
		      ->setTypeOfCargo( $data["cargo_type"] )
		      ->setWeight( $weight )
		      ->setCargoPackageQty( $qty )
		      ->setTenderComment( $data['comment'] )
		      ->setContactPerson( $this->options['wc_cse_contact_person'] ?? '' )
		      ->setWayBills( $data["numbers"] );

		$cseOrderID = $obKCE->setOrder();
		if ( $cseOrderID ) {
			CSEFunctions::setMessage( CSEConstants::SUCCESS_MESSAGE_TYPE, 'Заказ сформирован. Номер заказа: ' . $cseOrderID );
			$this->updateCSEOrderIDWayBills( $data['numbers'], $cseOrderID );
		}
	}

	private function updateCSEOrderIDWayBills( $numbers, $cseOrderID ) {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		$queryNumbers = '(';
		$i            = 0;
		foreach ( $numbers as $number ) {
			$queryNumbers .= "'$number'";
			if ( $i < count( $numbers ) - 1 ) {
				$queryNumbers .= ",";
			}
			$i ++;
		}
		$queryNumbers .= ')';

		$wpdb->query( "UPDATE $table_name SET cse_order_id='{$cseOrderID}' WHERE number IN $queryNumbers;" );
	}

}