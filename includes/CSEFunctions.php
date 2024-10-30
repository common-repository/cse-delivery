<?php

class CSEFunctions {

	public static function log( $date, $request, $response, $error, $calledFunction ) {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;
		$response   = base64_encode( $response );
		$request    = base64_encode( $request );
		$error      = base64_encode( $error );
		$wpdb->query( "INSERT INTO {$table_name} VALUES (null, '{$date}', '{$request}', '{$response}', '{$error}', '{$calledFunction}');" );
	}


	/**
	 * @param string $type
	 * @param $message
	 */
	public static function setMessage( $type = CSEConstants::INFO_MESSAGE_TYPE, $message ) {
		WC()->initialize_session();
		$notices = WC()->session->get( 'wc_cse_notice' ) ?? [];
		array_push( $notices, compact( 'type', 'message' ) );
		WC()->session->set( 'wc_cse_notice', $notices );
	}

	public static function getMessages() {
		WC()->initialize_session();
		$result = WC()->session->get( 'wc_cse_notice' ) ?? [];
		WC()->session->set( 'wc_cse_notice', [] );

		return $result;
	}

	public static function getOptions() {
		return get_option( CSEFunctions::getOptionName() );
	}

	public static function getOptionName() {
		return 'wc_cse_options';
	}

	public static function isDebug() {
		$result = get_option( CSEFunctions::getOptionName() )['wc_cse_debug'] ?? false;

		return $result == 'on';
	}

	public static function getTimesRange() {
		$times = [];
		for ( $i = 0; $i < 24; $i ++ ) {
			$timeStart = strtotime( "$i:00" );
			$timeEnd   = strtotime( ( $i + 1 ) . ":00" );
			$times[]   = date( 'H:i', $timeStart ) . ' - ' . date( 'H:i', $timeEnd );
		}

		return $times;
	}


	public static function getTimes() {
		$times = [];
		for ( $i = 0; $i < 24; $i ++ ) {
			$timeStart = strtotime( "$i:00" );
			$times[]   = date( 'H:i', $timeStart );
		}

		return $times;
	}

	public static function formatDeliveryRange( $minDays, $maxDays ) {
		$options              = CSEFunctions::getOptions();
		$appendedDeliveryDays = isset( $options['wc_cse_delivery_days'] ) && ! empty( $options['wc_cse_delivery_days'] ) ? intval( $options['wc_cse_delivery_days'] ) : 0;
		$result               = '';
		if ( $minDays == $maxDays ) {
			$result = CSEFunctions::getDayName( $minDays + $appendedDeliveryDays );
		} elseif ( $minDays < $maxDays ) {
			$result = 'от ' . CSEFunctions::getDayRangeName( $minDays + $appendedDeliveryDays ) . ' до ' . CSEFunctions::getDayRangeName( $maxDays + $appendedDeliveryDays );
		}

		return $result;
	}

	public static function getDayRangeName( $number ) {
		$name = '';
		switch ( $number ) {
			case 1:
				$name = 'дня';
				break;
			default:
				$name = 'дней';
				break;
		}

		return $number . ' ' . $name;
	}

	public static function getDayName( $number ) {
		$name = '';
		switch ( $number % 10 ) {
			case 1:
				$name = 'день';
				break;
			case 2:
			case 3:
			case 4:
				$name = 'дня';
				break;
			default:
				$name = 'дней';
				break;
		}

		return $number . ' ' . $name;
	}
}