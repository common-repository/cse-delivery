<?php


class CSE {

	private $login;
	private $password;

	/**
	 * CSE constructor.
	 *
	 * @param $login string Login for access to CSE API
	 * @param $password string Login for access to CSE API
	 *
	 * @throws Requests_Exception_HTTP_502
	 */
	public function __construct( $login, $password ) {
		if ( class_exists( 'SoapClient' ) ):
			ini_set( "soap.wsdl_cache_enabled", "0" );    //Отключаем кэширование
		else:
			throw new Requests_Exception_HTTP_502( 'SOAP client not installed' );
		endif;
		$this->login    = $login;
		$this->password = $password;
	}

	/**
	 * @return array|mixed available urgency
	 */
	public function getUrgencies() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru"><soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
                     <ns1:login>' . $this->login . '</ns1:login>
                     <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>Urgencies</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );
		$data   = [];
		if ( $result ) {
			$fields = CSEHelper::formatXml( $result );
			if ( is_array( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( isset( $field['mKey'] ) ) {
						$data[ $field['mKey'] ] = $field['mValue'] ?? '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|mixed available payer codes
	 */
	public function getPayerCode() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                     <soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
                     <ns1:login>' . $this->login . '</ns1:login>
                     <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>Payers</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );
		$data   = [];
		if ( $result ) {
			$fields = CSEHelper::formatXml( $result );
			if ( is_array( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( isset( $field['mKey'] ) ) {
						$data[ $field['mKey'] ] = $field['mValue'] ?? '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|mixed available payment methods
	 */
	public function getPayMethods() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                     <soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
                     <ns1:login>' . $this->login . '</ns1:login>
                     <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>PaymentMethods</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );
		$data   = [];
		if ( $result ) {
			$fields = CSEHelper::formatXml( $result );
			if ( is_array( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( isset( $field['mKey'] ) ) {
						$data[ $field['mKey'] ] = $field['mValue'] ?? '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|mixed available shipping methods
	 */
	public function getShippingMethods() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                     <soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
                     <ns1:login>' . $this->login . '</ns1:login>
                     <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>ShippingMethods</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );
		$data   = [];
		if ( $result ) {
			$fields = CSEHelper::formatXml( $result );
			if ( is_array( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( isset( $field['mKey'] ) ) {
						$data[ $field['mKey'] ] = $field['mValue'] ?? '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|mixed available cargo types
	 */
	public function getCargoTypes() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                     <soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
                     <ns1:login>' . $this->login . '</ns1:login>
                     <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>TypesOfCargo</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );
		$data   = [];
		if ( $result ) {
			$fields = CSEHelper::formatXml( $result );
			if ( is_array( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( isset( $field['mKey'] ) ) {
						$data[ $field['mKey'] ] = $field['mValue'] ?? '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|mixed available delivery methods
	 */
	public function getDeliveryMethods() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
         <soap:Header/>
         <soap:Body>
         <ns1:GetReferenceData>
		 <ns1:login>' . $this->login . '</ns1:login>
		 <ns1:password>' . $this->password . '</ns1:password>
         <ns1:parameters>
         <ns1:Key>parameters</ns1:Key>
         <ns1:List>
         <ns1:Key>Reference</ns1:Key>
         <ns1:Value>DeliveryType</ns1:Value>
         <ns1:ValueType>string</ns1:ValueType>
         </ns1:List>
         </ns1:parameters>
         </ns1:GetReferenceData>
         </soap:Body>
        </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );


		$data = false;
		if ( $result ) {
			$data = [
				'courier' => [],
				'pvz'     => [],
			];

			$sxe  = new SimpleXMLElement( $result );
			$text = $sxe->children( 'soap', true );
			$text = $text->children( 'm', true );

			$ResultArr = json_decode( json_encode( $text->GetReferenceDataResponse->return ?? [] ) );
			$ResultArr = $ResultArr->List ?? [];
			foreach ( $ResultArr as $delivType ) {
				switch ( $delivType->Value ) {
					case 0:
					case 1:
					case 4:
						$data['courier'][ $delivType->Value ] = $delivType->Fields[0]->Value;
						break;
					case 2:
					case 3:
						$data['pvz'][ $delivType->Value ] = $delivType->Fields[0]->Value;
						break;
				}
			}
		}

		return $data;
	}

	/**
	 * @return string user repository
	 */
	public function getUserRepository() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                     <soap:Header/>
                     <soap:Body>
                     <ns1:GetReferenceData>
        		      <ns1:login>' . $this->login . '</ns1:login>
        		      <ns1:password>' . $this->password . '</ns1:password>
                     <ns1:parameters>
                     <ns1:Key>parameters</ns1:Key>
                     <ns1:List>
                     <ns1:Key>Reference</ns1:Key>
                     <ns1:Value>Repository</ns1:Value>
                     <ns1:ValueType>string</ns1:ValueType>
                     </ns1:List>
                     </ns1:parameters>
                     </ns1:GetReferenceData>
                     </soap:Body>
                    </soap:Envelope>';

		$result     = CSEHelper::getData( $xmlData );
		$repository = '';
		if ( $result ) {
			$sxe  = new SimpleXMLElement( $result );
			$text = $sxe->children( 'soap', true );
			$text = $text->children( 'm', true );

			$GuidGeography = "";
			$repository    = "";
			$NameGeography = "";
			$repositories  = json_decode( json_encode( $text->GetReferenceDataResponse->return->List ) );
			$repositories  = $repositories->Fields;

			foreach ( $repositories as $repository_item ) {
				if ( $repository_item->Key == 'GuidGeography' ) {
					$GuidGeography = "" . $repository_item->Value;
				}
				if ( $repository_item->Key == 'NameGeography' ) {
					$NameGeography = "" . $repository_item->Value;
				}
			}

			//Получаем GUID код склада
			$repository = $this->getGeographyID( $NameGeography );
		}

		return $repository;

	}

	public function getDeliveryCost( $regionFrom, $regionTo, $Weight, $Qty, $DeliveryMethod, $Urgency = '18c4f207-458b-11dc-9497-0015170f8c09', $VolumeWeight = '', $Volume = '', $DeclaredValueRate = '0', $InsuranceRate = '0', $TypeOfCargo = '4aab1fc6-fc2b-473a-8728-58bcd4ff79ba' ) {

		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:m="http://www.cargo3.ru">
		<soap:Header/>
		<soap:Body>
			<m:Calc>
				<m:login>' . $this->login . '</m:login>
				<m:password>' . $this->password . '</m:password>
				<m:data>
				<m:Key>Destinations</m:Key>
				<m:List>
					<m:Key>Destination</m:Key>
					<m:Fields>
						<m:Key>SenderGeography</m:Key>
						<m:Value>' . $regionFrom . '</m:Value>
						<m:ValueType>string</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>RecipientGeography</m:Key>
						<m:Value>' . $regionTo . '</m:Value>
						<m:ValueType>string</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>TypeOfCargo</m:Key>
						<m:Value>' . $TypeOfCargo . '</m:Value>
						<m:ValueType>string</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>Urgency</m:Key>
						<m:Value>' . $Urgency . '</m:Value>
						<m:ValueType>string</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>Weight</m:Key>
						<m:Value>' . $Weight . '</m:Value>
						<m:ValueType>float</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>Qty</m:Key>
						<m:Value>' . $Qty . '</m:Value>
						<m:ValueType>int</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>VolumeWeight</m:Key>
						<m:Value>' . $VolumeWeight . '</m:Value>
						<m:ValueType>float</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>Volume</m:Key>
						<m:Value>' . $Volume . '</m:Value>
						<m:ValueType>float</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>DeliveryType</m:Key>
						<m:Value>' . $DeliveryMethod . '</m:Value>
						<m:ValueType>decimal</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>DeclaredValueRate</m:Key>
						<m:Value>' . $DeclaredValueRate . '</m:Value>
						<m:ValueType>float</m:ValueType>
					</m:Fields>
					<m:Fields>
						<m:Key>InsuranceRate</m:Key>
						<m:Value>' . $InsuranceRate . '</m:Value>
						<m:ValueType>float</m:ValueType>
					</m:Fields>
					</m:List>
				</m:data>
				<m:parameters>
					<m:Key>Parameters</m:Key>
				</m:parameters>
			</m:Calc>
		</soap:Body>
		</soap:Envelope>';

		$search_result = CSEHelper::getData( $xmlData );
		$text          = CSEHelper::formatXml2( $search_result );

		//Init
		$cost     = 0;
		$currency = '';
		$days     = 0;
		$mindays  = 0;

		if ( $text ) {
			if ( isset( $text['mList'][0] ) && $text['mList'][0] ) {
				$ResultArr = $text['mList'][0];
			} else {
				$ResultArr = $text['mList'] ?? false;
			}

			if ( $ResultArr ) {
				foreach ( $ResultArr['mFields'] as $Fields ) {
					if ( $Fields['mKey'] == 'Total' ) {
						$cost = "" . $Fields['mValue'];
					}
					if ( $Fields['mKey'] == 'Currency' ) {
						$currency = "" . $Fields['mValue'];
						if ( $currency == 'ff3f7c38-4430-11dc-9497-0015170f8c09' ) {
							$currency = 'руб';
						}
					}
					if ( $Fields['mKey'] == 'MinPeriod' ) {
						$mindays = "" . $Fields['mValue'];
					}
					if ( $Fields['mKey'] == 'MaxPeriod' ) {
						$days = "" . $Fields['mValue'];
					}
				}
			}
		}

		return array( 'cost' => $cost, 'currency' => $currency, 'days' => $days, 'mindays' => $mindays );
	}

	/**
	 * @param $search
	 *
	 * @return string geography code by name
	 */
	private function getGeographyID( $search ) {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
          <soap:Header/>
          <soap:Body>
         <ns1:GetReferenceData>
          <ns1:login>' . $this->login . '</ns1:login>
          <ns1:password>' . $this->password . '</ns1:password>
          <ns1:parameters>
           <ns1:Key>parameters</ns1:Key>
           <ns1:List>
            <ns1:Key>Reference</ns1:Key>
            <ns1:Value>Geography</ns1:Value>
            <ns1:ValueType>string</ns1:ValueType>
           </ns1:List>
             <ns1:List>
            <ns1:Key>Search</ns1:Key>
            <ns1:Value>' . $search . '</ns1:Value>
            <ns1:ValueType>string</ns1:ValueType>
            </ns1:List>
           </ns1:parameters>
         </ns1:GetReferenceData>
          </soap:Body>
        </soap:Envelope>';

		$searchResult = CSEHelper::getData( $xmlData );
		$sxe          = new SimpleXMLElement( $searchResult );
		$text         = $sxe->children( 'soap', true );
		$text         = $text->children( 'm', true );

		$resultArr = json_decode( json_encode( $text->GetReferenceDataResponse->return->List ?? [] ) );

		$geographyID = "" . $resultArr->Key ?? '';

		return $geographyID;
	}

	public function getContactPersons() {
		$xmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
					 <soap:Header/>
					 <soap:Body>
						 <ns1:GetReferenceData>
							 <ns1:login>' . $this->login . '</ns1:login>
							 <ns1:password>' . $this->password . '</ns1:password>
							 <ns1:parameters>
							 <ns1:Key>parameters</ns1:Key>
								 <ns1:List>
									 <ns1:Key>Reference</ns1:Key>
									 <ns1:Value>Contacts</ns1:Value>
									 <ns1:ValueType>string</ns1:ValueType>
							     </ns1:List>
							 </ns1:parameters>
						 </ns1:GetReferenceData>
					 </soap:Body>
					</soap:Envelope>';

		$searchResult = CSEHelper::getData( $xmlData );
		$sxe          = new SimpleXMLElement( $searchResult );
		$text         = $sxe->children( 'soap', true );
		$text         = $text->children( 'm', true );

		$contactPersons = $text->xpath( '//m:return/m:List' );
		$result         = [];
		if ( $contactPersons ) {
			foreach ( $contactPersons as $contactPerson ) {
				$contactPerson                            = $contactPerson->children( 'm', true );
				$result[ (string) $contactPerson->Value ] = (string) $contactPerson->Value ?? '';
			}
		}

		return $result;
	}
}