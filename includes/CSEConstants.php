<?php

class CSEConstants {

	public const SOAP_URL = 'https://module.cse.ru/bitrix/ws/web1c.1cws';//  URL of WSDL

	public const SOAP_USER = "wordpress";  //  username
	public const SOAP_PASS = "jU3soxah"; // password
	public const WAY_BILLS_NUMBER = 39; // password

	public const INFO_MESSAGE_TYPE = 'info';
	public const ERROR_MESSAGE_TYPE = 'error';
	public const WARNING_MESSAGE_TYPE = 'warning';
	public const SUCCESS_MESSAGE_TYPE = 'success';

	public const CITIES_TABLE_NAME = 'wc_cse_cities';
	public const WAYBILLS_TABLE_NAME = 'wc_cse_way_bills';
	public const DEBUG_TABLE_NAME = 'wc_cse_debug';

	public const CITY_COUNT_IN_DB = 178865;

	public const AUTH_TOKEN_OPTION_NAME = 'csedelivery_auth_token';
	public const OPTIONS_NAME = 'wc_cse_options';
	public const PLUGIN_INSTALLED_OPTION_NAME = 'csedelivery_activate_finished';

}