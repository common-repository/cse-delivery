<?php


class CSEHelper {
	/**
	 * @param $xmlPostString
	 *
	 * @param bool $hasNotice
	 *
	 * @return bool|string
	 */
	public static function getData( $xmlPostString, $hasNotice = true ) {

		$headers = array(
			"Content-type"    => "text/xml;charset=\"utf-8\"",
			"Accept"          => "text/xml",
			"Cache-Control"   => "no-cache",
			"Pragma"          => "no-cache",
			"SOAPAction"      => CSEConstants::SOAP_URL,
			'Authorization'   => 'Basic ' . base64_encode( CSEConstants::SOAP_USER . ":" . CSEConstants::SOAP_PASS ),
			'accept-encoding' => 'deflate, br',
		);

		$response = wp_remote_post( CSEConstants::SOAP_URL, [
			'timeout'     => 10,
			'redirection' => 5,
			'compress'    => false,
			'decompress'  => true,
			'sslverify'   => false,
			'stream'      => false,
			'headers'     => $headers,
			'body'        => $xmlPostString
		] );

		if ( CSEFunctions::isDebug() ) {
			$timeZone = empty( get_option( 'timezone_string', 'Europe/Moscow' ) ) ? 'Europe/Moscow' : get_option( 'timezone_string' );
			date_default_timezone_set( $timeZone );
			$calledFunction = debug_backtrace()[1]['function'] ?? '';

			$httpError = is_wp_error( $response ) ? implode( ', ', $response->get_error_messages() ) : '';
			$responseBody = !is_wp_error( $response ) ? $response['body'] : '';
			CSEFunctions::log( date( 'd.m.Y H:i:s' ), $xmlPostString, $responseBody, $httpError, $calledFunction );
		}

		$result = false;
		if ( is_wp_error( $response ) ) {
			CSEFunctions::setMessage( CSEConstants::ERROR_MESSAGE_TYPE, implode( ', ', $response->get_error_messages() ) );
		} elseif ( $hasNotice && ( $response['body'] ?? false ) && $errorCode = self::getError( new SimpleXMLElement( $response['body'] ) ) ) {
			$message = self::getErrorByCode( $errorCode );
			CSEFunctions::setMessage( 'error', $message );
		} else {
			$result = $response['body'];
		}

		return $result;
	}

	/**
	 * @param $soapResult
	 *
	 * @return array|mixed
	 */
	public static function formatXml( $soapResult ) {

		$soapResult = preg_replace( "/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $soapResult );
		if ( $soapResult ) {
			$xml   = new SimpleXMLElement( $soapResult );
			$body  = $xml->xpath( '//mGetReferenceDataResponse' )[0];
			$array = json_decode( json_encode( (array) $body ), true );
			$array = $array['mreturn'] ?? [];
			$array = $array['mList'] ?? [];
		}

		return $array;
	}

	/**
	 * @param $soapResult
	 *
	 * @return array|mixed
	 */
	public static function formatXml2( $soapResult ) {

		$soapResult = preg_replace( "/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $soapResult );
		$array      = [];
		if ( $soapResult ) {
			$xml   = new SimpleXMLElement( $soapResult );
			$body  = $xml->xpath( '//mCalcResponse' )[0];
			$array = json_decode( json_encode( (array) $body ), true );
			$array = $array['mreturn'] ?? [];
			$array = $array['mList'] ?? [];
		}

		return $array;
	}

	/**
	 * @param $soapResult SimpleXMLElement
	 *
	 * @return mixed
	 */
	public static function getError( $soapResult ) {
		$soapResult = $soapResult->children( 'soap', true )
		                         ->children( 'm', true );
		$error      = @$soapResult->xpath( '//m:Properties[contains(m:Key, "Error") and contains(m:Value, "true")]' );
		if ( $error && count( $error ) > 0 ) {
			$error = reset( $error );
			$error = $error->children( 'm', true );

			return strval( $error->List->Value ?? '' );
		}

		return false;
	}

	public static function getErrors() {
		$sxe       = new SimpleXMLElement( @file_get_contents( CSEDELIVERY_ROOT_DIR . 'assets/errors.xml' ) );
		$text      = $sxe->children( 'soap', true );
		$text      = $text->children( 'm', true );
		$xmlErrors = $text->GetReferenceDataResponse->return->List ?? [];
		$errors    = [];
		foreach ( $xmlErrors as $error ) {
			$code            = strval( $error->Key );
			$value           = strval( $error->Value );
			$errors[ $code ] = $value;
		}

		return $errors;
	}

	public static function getErrorByCode( $code = '' ) {
		return self::getErrors()[ $code ] ?? 'Неизвестная ошибка';
	}

	public static function dateToCseDate( $date ) {
		return $date . 'T00:00:00';
	}

	public static function getCityName( string $cityGuid ) {
		global $wpdb;
		$tableName = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$result    = $wpdb->get_row( "SELECT name FROM $tableName WHERE guid = '$cityGuid'", "ARRAY_A" );

		return $result["name"] ?? '';
	}

	public static function getCityGuid( string $name ) {
		global $wpdb;
		$tableName = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$result    = $wpdb->get_row( "SELECT guid FROM $tableName WHERE name = '$name'", "ARRAY_A" );

		return $result["guid"];
	}

	public static function getNoOrderWayBills() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		return $wpdb->get_results( "SELECT * FROM $table_name WHERE cse_order_id IS NULL ;", 'ARRAY_A' );
	}

	public static function getWayBillsByNumber( $numbers ) {
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

		return $wpdb->get_results( "SELECT * FROM $table_name WHERE number IN $queryNumbers ;", 'ARRAY_A' );
	}
}