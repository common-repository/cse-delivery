<?php

/**
 * @link              https://cse.ru
 * @since             1.0.1
 * @package           CSEDbImporter
 */

class CSEDbImporter {

	const URL_PATH = 'csedelivery_import_cities';

	public function run() {
		$token = get_option( CSEConstants::AUTH_TOKEN_OPTION_NAME, wp_generate_password( 24 ) );
		if ( isset( $_GET[ CSEDbImporter::URL_PATH ] ) && $_GET[ CSEDbImporter::URL_PATH ] == $token ) {
			$this->importCities();
		}
	}

	public function importCities() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$iteration  = 5000;
		$error      = false;

		$offset = 0;
		if ( isset( $_GET["offset"] ) ) {
			$offset = intval( sanitize_text_field( $_GET["offset"] ) );
		}

		$file = fopen( CSEDELIVERY_ROOT_DIR . "assets/CSE_GUID.xml", "r" );
		for ( $i = 0; $i < $offset; $i ++ ) {
			fgets( $file );
		}
		$query = $wpdb->prepare( "INSERT INTO $table_name (guid ,parent_guid ,type ,cladr ,alter_code ,priority ,name ,name_eng) VALUES ");
		for ( $i = 0; $i < $iteration; $i ++ ) {
			if ( ( $str = fgets( $file ) ) ) {
				if ( preg_match( "/<Объект/", $str ) ) {
					preg_match( '/GUID="([\S]*)" /i', $str, $m );
					$GUID = $m[1] ?? '';
					preg_match( '/РодительGUID="([\S]*)" /i', $str, $m );
					$parentGUID = $m[1] ?? '';
					preg_match( '/ТипОбъекта="([\d]*)" /i', $str, $m );
					$objType = $m[1] ?? 0;
					preg_match( '/КЛАДР="([\d]*)" /i', $str, $m );
					$cladr = $m[1] ?? 0;
					preg_match( '/АльтернативныйКод="([\S]*)" /i', $str, $m );
					$alterCladr = $m[1] ?? '';
					preg_match( '/Приоритет="([\d]*)" /i', $str, $m );
					$priority = $m[1] ?? 0;
					preg_match( '/Наименование="([\S\ ]*)" /i', $str, $m );
					$name = $m[1] ?? '';
					preg_match( '/НаименованиеENG="([\S\ ]*)" ?\/>/i', $str, $m );
					$nameENG = $m[1] ?? '';

					$query  .= $wpdb->prepare( "(%s,%s,%d,%d,%s,%s,%s,%s)", [
						$GUID,
						$parentGUID,
						$objType,
						$cladr,
						$alterCladr,
						$priority,
						$name,
						$nameENG
					] );

					$query .= ",";
				}
			} else {
				break;
			}
		}

		$query = substr($query, 0, -1);
		$row_id = $wpdb->query( $query );
		if ( $row_id === false ) {
			$error = 'КСЕ: Непредвиденная ошибка';
		}

		if ( $str === false ) {
			update_option( 'csedelivery_activate_finished', true );
		} elseif ( $error ) {
			CSEFunctions::setMessage( 'error', $error );
		} else {
			$token = get_option( CSEConstants::AUTH_TOKEN_OPTION_NAME, wp_generate_password( 24 ) );
			wp_remote_get( home_url() . '/?' . CSEDbImporter::URL_PATH . '=' . urlencode( $token ) . "&offset=" . ( $iteration + $offset + 1 ), [
				'timeout'             => 1,
				'sslverify'           => false,
				'limit_response_size' => 1,
			] );
		}
		fclose( $file );

		$wpdb->close();
	}
}