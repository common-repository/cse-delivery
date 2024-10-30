<?php


class CSEOrder {

	private $login,
		$password,
		$orderID,
		$recipientName,
		$geoTo,
		$recipientFullAddress,
		$recipientPhone,
		$recipientEmail,
		$urgency,
		$cargoDescription,
		$cargoPackageQty,
		$weight,
		$geoFrom,
		$clientName,
		$senderPhone,
		$tenderComment,
		$takeDate,
		$typeOfCargo,
		$typeOfPayer,
		$wayOfPayment,
		$basketItems,
		$deliveryAmount,
		$pvzGuid;
	private $paymentMethod;
	private $shippingMethod;
	private $wayBills;
	private $deliveryOfCargo;
	private $CODAmount;
	private $declaredValueRate;

	const ORDER = 'Order';
	const WAYBILL = 'Waybill';
	private $takeTime;
	private $contactPerson;
	private $deliveryTime;
	private $deliveryTimeOf;
	private $deliveryDate;
	private $senderEmail;
	private $senderAddress;

	/**
	 * @param mixed $wayBills
	 *
	 * @return CSEOrder
	 */
	public function setWayBills( $wayBills ) {
		$this->wayBills = $wayBills;

		return $this;
	}

	/**
	 * @param mixed $shippingMethod
	 *
	 * @return CSEOrder
	 */
	public function setShippingMethod( $shippingMethod ) {
		$this->shippingMethod = $shippingMethod;

		return $this;
	}

	/**
	 * @param mixed $paymentMethod
	 *
	 * @return CSEOrder
	 */
	public function setPaymentMethod( $paymentMethod ) {
		$this->paymentMethod = $paymentMethod;

		return $this;
	}

	/**
	 * CSEOrder constructor.
	 *
	 * @param $login
	 * @param $password
	 */
	public function __construct( $login, $password ) {
		$this->login    = $login;
		$this->password = $password;
	}

	/**
	 * @param mixed $orderID
	 *
	 * @return CSEOrder
	 */
	public function setOrderID( $orderID ) {
		$this->orderID = $orderID;

		return $this;
	}

	/**
	 * @param mixed $recipientName
	 *
	 * @return CSEOrder
	 */
	public function setRecipientName( $recipientName ) {
		$this->recipientName = $recipientName;

		return $this;
	}

	/**
	 * @param mixed $geoTo
	 *
	 * @return CSEOrder
	 */
	public function setGeoTo( $geoTo ) {
		$this->geoTo = $geoTo;

		return $this;
	}

	/**
	 * @param mixed $recipientFullAddress
	 *
	 * @return CSEOrder
	 */
	public function setRecipientFullAddress( $recipientFullAddress ) {
		$this->recipientFullAddress = $recipientFullAddress;

		return $this;
	}

	/**
	 * @param mixed $recipientPhone
	 *
	 * @return CSEOrder
	 */
	public function setRecipientPhone( $recipientPhone ) {
		$this->recipientPhone = $recipientPhone;

		return $this;
	}

	/**
	 * @param mixed $recipientEmail
	 *
	 * @return CSEOrder
	 */
	public function setRecipientEmail( $recipientEmail ) {
		$this->recipientEmail = $recipientEmail;

		return $this;
	}

	/**
	 * @param mixed $urgency
	 *
	 * @return CSEOrder
	 */
	public function setUrgency( $urgency ) {
		$this->urgency = $urgency;

		return $this;
	}

	/**
	 * @param mixed $cargoDescription
	 *
	 * @return CSEOrder
	 */
	public function setCargoDescription( $cargoDescription ) {
		$this->cargoDescription = $cargoDescription;

		return $this;
	}

	/**
	 * @param mixed $cargoPackageQty
	 *
	 * @return CSEOrder
	 */
	public function setCargoPackageQty( $cargoPackageQty ) {
		$this->cargoPackageQty = $cargoPackageQty;

		return $this;
	}

	/**
	 * @param mixed $weight
	 *
	 * @return CSEOrder
	 */
	public function setWeight( $weight ) {
		$this->weight = $weight;

		return $this;
	}

	/**
	 * @param mixed $geoFrom
	 *
	 * @return CSEOrder
	 */
	public function setGeoFrom( $geoFrom ) {
		$this->geoFrom = $geoFrom;

		return $this;
	}

	/**
	 * @param mixed $clientName
	 *
	 * @return CSEOrder
	 */
	public function setClientName( $clientName ) {
		$this->clientName = $clientName;

		return $this;
	}

	/**
	 * @param mixed $senderPhone
	 *
	 * @return CSEOrder
	 */
	public function setSenderPhone( $senderPhone ) {
		$this->senderPhone = $senderPhone;

		return $this;
	}

	/**
	 * @param mixed $tenderComment
	 *
	 * @return CSEOrder
	 */
	public function setTenderComment( $tenderComment ) {
		$this->tenderComment = $tenderComment;

		return $this;
	}

	/**
	 * @param mixed $takeDate
	 *
	 * @return CSEOrder
	 */
	public function setTakeDate( $takeDate ) {
		$this->takeDate = $takeDate;

		return $this;
	}

	/**
	 * @param mixed $typeOfCargo
	 *
	 * @return CSEOrder
	 */
	public function setTypeOfCargo( $typeOfCargo ) {
		$this->typeOfCargo = $typeOfCargo;

		return $this;
	}

	/**
	 * @param mixed $typeOfPayer
	 *
	 * @return CSEOrder
	 */
	public function setTypeOfPayer( $typeOfPayer ) {
		$this->typeOfPayer = $typeOfPayer;

		return $this;
	}

	/**
	 * @param mixed $wayOfPayment
	 *
	 * @return CSEOrder
	 */
	public function setWayOfPayment( $wayOfPayment ) {
		$this->wayOfPayment = $wayOfPayment;

		return $this;
	}

	/**
	 * @param mixed $basketItems
	 *
	 * @return CSEOrder
	 */
	public function setBasketItems( $basketItems ) {
		$this->basketItems = $basketItems;

		return $this;
	}

	public function SetWaybill() {

		$this->updateClientProducts( $this->basketItems );

		$rand = rand( 0, 1598458 );

		//приводим тип доставки в соответствие с накладными
		$deliveryOfCargo = 0;
		switch ( $this->deliveryOfCargo ) {
			case 0:
				//Дверь-Дверь
				$deliveryOfCargo = 1;
				break;
			case 1:
				//Склад-Дверь
				$deliveryOfCargo = 6;
				break;
			case 2:
				//Дверь-Склад (самовывоз)
				$deliveryOfCargo = 5;
				break;
			case 3:
				//Склад-Склад (самовывоз)
				$deliveryOfCargo = 7;
				break;
			case 4:
				//Склад-Склад (самовывоз)
				$deliveryOfCargo = 2;
				break;
		}

		$XmlData = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:car="http://www.cargo3.ru"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema">
         <soap:Header/>
         <soap:Body>
             <car:SaveWaybillOffice>
                 <car:Language/>
                 <car:Login>' . $this->login . '</car:Login>
                 <car:Password>' . $this->password . '</car:Password>
                 <car:Company/>
                 <car:Number/>
                 <car:ClientNumber>' . $this->orderID . '-' . $rand . '</car:ClientNumber>
                 <car:OrderData>
                     <car:ClientContact/>
                     ' . ( $this->deliveryDate ? '<car:DeliveryDate>' . $this->deliveryDate . '</car:DeliveryDate>' : '' ) . '
                     <car:Recipient>
                         <car:Client>' . $this->recipientName . '</car:Client>
                         <car:Official></car:Official>
                         <car:Address>
                             <car:Geography>' . $this->geoTo . '</car:Geography>
                             <car:Info>' . $this->recipientFullAddress . '</car:Info>
                             <car:FreeForm>true</car:FreeForm>
                         </car:Address>
        				 <car:Phone>' . $this->recipientPhone . '</car:Phone>
        				 <car:EMail>' . $this->recipientEmail . '</car:EMail>
        				 <car:Urgency>' . $this->urgency . '</car:Urgency>
                         <car:Cargo>
                             <car:CargoDescription>' . $this->cargoDescription . '</car:CargoDescription>
                             <car:CargoPackageQty>' . $this->cargoPackageQty . '</car:CargoPackageQty>
                             <car:Weight>' . $this->weight . '</car:Weight>        
                             <car:DeclaredValueRate>' . $this->declaredValueRate . '</car:DeclaredValueRate>   
                             <car:COD>' . $this->CODAmount . '</car:COD>                  
                         </car:Cargo>';

		foreach ( $this->basketItems as $Item ) {
			$sku     = $this->getSKU( $Item );
			$XmlData .= '<car:Products>
                             <car:Article>' . $sku . '</car:Article>  
                             <car:Price>' . $Item['PRICE'] . '</car:Price>
                             <car:PackageQty>' . $Item['QTY'] . '</car:PackageQty>
                             <car:Qty>' . $Item['QTY'] . '</car:Qty>
                             <car:Comment>' . $Item['NAME'] . '</car:Comment>
                         </car:Products>';
		}

		$XmlData .= '<car:Products>
                             <car:Article>ДОСТАВКА</car:Article>  
                             <car:Price>' . $this->deliveryAmount . '</car:Price>
                             <car:PackageQty>1</car:PackageQty>
                             <car:Qty>1</car:Qty>
                             <car:Comment>Услуга доставки</car:Comment>
                         </car:Products>';
		if ( $this->pvzGuid ) {
			$XmlData .= '<car:PVZ>' . $this->pvzGuid . '</car:PVZ>';
		}
		$XmlData .= '</car:Recipient>
					 <car:ReplyEMail>' . $this->recipientEmail . '</car:ReplyEMail>
                     <car:ReplySMSPhone>' . $this->recipientPhone . '</car:ReplySMSPhone>
                     <car:Sender>
                         <car:Client>' . $this->clientName . '</car:Client>
                         <car:Address>
                             <car:Geography>' . $this->geoFrom . '</car:Geography>
                             <car:Info>' . $this->senderAddress . '</car:Info>
                             <car:FreeForm>true</car:FreeForm>
                         </car:Address>
                         <car:Phone>' . $this->senderPhone . '</car:Phone>
                         <car:EMail>' . $this->senderEmail . '</car:EMail>';
		$XmlData .= '</car:Sender>
        			 <car:TakeDate>' . $this->takeDate . '</car:TakeDate>
        			 <car:TypeOfCargo>' . $this->typeOfCargo . '</car:TypeOfCargo>
        			 <car:TypeOfPayer>' . $this->typeOfPayer . '</car:TypeOfPayer>
        			 <car:WayOfPayment>' . $this->wayOfPayment . '</car:WayOfPayment>
                     <car:Comment>' . $this->tenderComment . '</car:Comment>
                     <car:DeliveryOfCargo>' . $deliveryOfCargo . '</car:DeliveryOfCargo>             
                 </car:OrderData>
                 <car:Office/>
             </car:SaveWaybillOffice>
         </soap:Body>
        </soap:Envelope>';


		$search_result = CSEHelper::getData( $XmlData );

		if ( ! $search_result ) {
			return false;
		}

		$sxe  = new SimpleXMLElement( $search_result );
		$text = $sxe->children( 'soap', true );
		$text = $text->children( 'm', true );

		if ( isset( $text->SaveWaybillOfficeResponse->return->Items ) && $text->SaveWaybillOfficeResponse->return->Items->Error != 'true' ) {
			$WayBills = json_decode( json_encode( $text->SaveWaybillOfficeResponse->return->Items ) );
		}
		$WayBillID = (string) $WayBills->Value ?? false;

		if ( $WayBillID ) {
			CSEFunctions::setMessage( CSEConstants::INFO_MESSAGE_TYPE, __( 'Накладная сформирована. Номер ' ) . $WayBillID );
		}

		return $WayBillID;
	}

	/**
	 * @param mixed $deliveryAmount
	 *
	 * @return CSEOrder
	 */
	public function setDeliveryAmount( $deliveryAmount ) {
		$this->deliveryAmount = $deliveryAmount;

		return $this;
	}

	public function getOrders( $dateFrom, $dateTo ) {

		$XmlData = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
					<soap:Header/>
						<soap:Body>
							<m:Report xmlns:m="http://www.cargo3.ru">
							<m:login xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $this->login . '</m:login>
							<m:password xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $this->password . '</m:password>
							<m:name xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">SummaryOfPayments</m:name>
							<m:parameters xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
								<m:Key>Parameters</m:Key>
									<m:List>
										<m:Key>FromDate</m:Key>
										<m:Value xsi:type="xs:dateTime">' . $dateFrom . '</m:Value>
										<m:ValueType>dateTime</m:ValueType>
									</m:List>
									<m:List>
										<m:Key>ToDate</m:Key>
										<m:Value xsi:type="xs:dateTime">' . $dateTo . '</m:Value>
										<m:ValueType>dateTime</m:ValueType>
									</m:List>
							</m:parameters>
							</m:Report>
						</soap:Body>
					</soap:Envelope>';

		$search_result = CSEHelper::getData( $XmlData );

		if ( ! $search_result ) {
			return [
				'client' => [],
				'orders' => [],
			];
		}

		$sxe  = new SimpleXMLElement( $search_result );
		$text = $sxe->children( 'soap', true )
		            ->children( 'm', true );

		//Получаем информацию о клиенте
		$ResultArrClient = json_decode( json_encode( $text->ReportResponse->return ) );
		$ResultArrClient = $ResultArrClient->List[0]->Fields ?? false;
		if ( $ResultArrClient ) {
			foreach ( $ResultArrClient as $Prop ) {
				if ( $Prop->Key == 'Client' ) {
					$arOrdersKCE['client']['name'] = (string) $Prop->Value;
				}
				if ( $Prop->Key == 'Contract' ) {
					$arOrdersKCE['client']['contract'] = (string) $Prop->Value;
				}
				if ( $Prop->Key == 'ContractCurrency' ) {
					$arOrdersKCE['client']['currency'] = (string) $Prop->Value;
				}
			}
		}

		//Получаем информацию о заказах клиента
		$ResultArrOrders       = json_decode( json_encode( $text->ReportResponse->return ) );
		$arOrdersKCE['orders'] = [];
		$orderRows             = [];
		if ( $ResultArrOrders ) {
			foreach ( $ResultArrOrders->List ?? [] as $list ) {
				$isOrderEmpty = false;
				foreach ( $list->Fields as $field ) {
					if ( $field->Key == 'Total' && $field->Value == 0 ) {
						$isOrderEmpty = true;
					}
				}
				if ( ! $isOrderEmpty ) {
					foreach ( $list->List as $fieldList ) {
						if ( is_array( $fieldList->Rows ) ) {
							$orderRows = array_merge( $orderRows, $fieldList->Rows );
						}
					}
				}
			}
		}
		uasort( $orderRows, function ( $a, $b ) {
			return strtotime( $a->Cells[4] ) > strtotime( $b->Cells[4] ) ? - 1 : 1;
		} );
		foreach ( $orderRows as $row ) {
			$cells                   = $row->Cells;
			$arOrdersKCE['orders'][] = [
				'numberbill' => $cells[2],
				'number'     => is_string( $cells[3] ) ? $cells[3] : '',
				'date'       => @date( 'd.m.Y', strtotime( $cells[4] ) ),
				'from'       => $cells[6],
				'to'         => $cells[7],
				'type'       => $cells[8],
				'gruz'       => $cells[9],
				'delivdate'  => is_string( $cells[10] ) && ! empty( $cells[10] ) ? @date( 'd.m.Y', strtotime( $cells[10] ) ) : '',
				'poluchatel' => is_string( $cells[11] ) ? $cells[11] : '',
				'mesta'      => $cells[12],
				'vesFakt'    => $cells[13],
				'cost'       => $cells[14],
			];
		}

		return $arOrdersKCE;
	}

	public function setOrder() {
		$xmlData = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:m="http://www.cargo3.ru">
                	<soap:Header/>
                	<soap:Body>
                    	<m:SaveDocuments>
                    	<m:login>' . $this->login . '</m:login>
                    	<m:password>' . $this->password . '</m:password>
                    	<m:data>
                    		<m:Key>Orders</m:Key>
                    		<m:List>
                    			<m:Key>Order</m:Key>
                    			<m:Fields>
                    				<m:Key>TakeDate</m:Key>
                    				<m:Value>' . $this->takeDate . 'T00:00:00</m:Value>
                    				<m:ValueType>dateTime</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>TakeTime</m:Key>
                    				<m:Value>' . $this->takeTime . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>TakeAtOffice</m:Key>
                    				<m:Value>false</m:Value>
                    				<m:ValueType>boolean</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>Sender</m:Key>
                    				<m:Value>' . $this->clientName . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>SenderOfficial</m:Key>
                    				<m:Value>' . $this->contactPerson . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>SenderGeography</m:Key>
                    				<m:Value>' . $this->geoFrom . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>SenderAddress</m:Key>
                    				<m:Value>' . $this->recipientFullAddress . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>SenderPhone</m:Key>
                    				<m:Value>' . $this->senderPhone . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>SenderEMail</m:Key>
                    				<m:Value>' . $this->recipientEmail . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>Comment</m:Key>
                    				<m:Value>' . $this->tenderComment . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>Urgency</m:Key>
                    				<m:Value>' . $this->urgency . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
        				            <m:Key>IsDistribution</m:Key>
                    				<m:Value>true</m:Value>
                    				<m:ValueType>boolean</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>Payer</m:Key>
                    				<m:Value>' . $this->typeOfPayer . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>PaymentMethod</m:Key>
                    				<m:Value>' . $this->paymentMethod . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>ShippingMethod</m:Key>
                    				<m:Value>' . $this->shippingMethod . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>TypeOfCargo</m:Key>
                    				<m:Value>' . $this->typeOfCargo . '</m:Value>
                    				<m:ValueType>string</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>WithReturn</m:Key>
                    				<m:Value>false</m:Value>
                    				<m:ValueType>boolean</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>Weight</m:Key>
                    				<m:Value>' . $this->weight . '</m:Value>
                    				<m:ValueType>float</m:ValueType>
                    			</m:Fields>
                    			<m:Fields>
                    				<m:Key>CargoPackageQty</m:Key>
                    				<m:Value>' . $this->cargoPackageQty . '</m:Value>
                    				<m:ValueType>int</m:ValueType>
                    			</m:Fields>
                               <m:Tables>
                        			<m:Key>Waybills</m:Key>';
		foreach ( $this->wayBills as $waybill ) {
			$xmlData .= '            <m:List>
                        			    <m:Key>Items</m:Key>
                        			    <m:Fields>
                            				<m:Key>Number</m:Key>
                            				<m:Value>' . $waybill . '</m:Value>
                           					<m:ValueType>string</m:ValueType>
                        				</m:Fields>
                       				</m:List>';
		}
		$xmlData .= '            </m:Tables>
                    		</m:List>
                    	</m:data>
                    	<m:parameters>
                    		<m:Key>Parameters</m:Key>
                    		<m:List>
                    			<m:Key>DocumentType</m:Key>
                    			<m:Value>order</m:Value>
                    			<m:ValueType>string</m:ValueType>
                    		</m:List>
                    	</m:parameters>
                    </m:SaveDocuments>
                </soap:Body>
                </soap:Envelope>';

		$searchResult = CSEHelper::getData( $xmlData );

		if ( ! $searchResult ) {
			return false;
		}

		$sxe     = new SimpleXMLElement( $searchResult );
		$text    = $sxe->children( 'soap', true );
		$text    = $text->children( 'm', true );
		$orders  = json_decode( json_encode( $text->SaveDocumentsResponse->return->List ?? (object) [] ) );
		$orders  = $orders->Properties ?? [];
		$orderID = '';
		foreach ( $orders as $order ) {
			if ( $order->Key == 'Number' ) {
				$orderID = strval( $order->Value );
			}
		}

		return $orderID;
	}

	public function getWayBillPDF( $docType, $number, $name ) {
		$xmlData = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                    <soap:Header/>
                    <soap:Body>
						<m:GetFormsForDocuments xmlns:m="http://www.cargo3.ru">
							<m:login>' . $this->login . '</m:login>
							<m:password>' . $this->password . '</m:password>
							<m:documents>
								<m:Key>Documents</m:Key>
								<m:List>
									<m:Key>' . $number . '</m:Key>
								</m:List>
							</m:documents>
							<m:parameters>
								<m:Key>Parameters</m:Key>
								<m:List>
									<m:Key>DocumentType</m:Key>
									<m:Value>' . $docType . '</m:Value>
									<m:ValueType>string</m:ValueType>
								</m:List>
								<m:List>
									<m:Key>Type</m:Key>
									<m:Value>print</m:Value>
									<m:ValueType>string</m:ValueType>
								</m:List>
								<m:List>
									<m:Key>OnlyPath</m:Key>
									<m:Value>false</m:Value>
									<m:ValueType>boolean</m:ValueType>
								</m:List>
								<m:List>
									<m:Key>Format</m:Key>
									<m:Value>PDF</m:Value>
									<m:ValueType>string</m:ValueType>
								</m:List>
								<m:List>
									<m:Key>Name</m:Key>
									<m:Value>' . $name . '</m:Value>
									<m:ValueType>string</m:ValueType>
								</m:List>
							</m:parameters>
						</m:GetFormsForDocuments>
                    </soap:Body>
                </soap:Envelope>';

		$result = CSEHelper::getData( $xmlData );

		if ( $result ) {
			$sxe  = new SimpleXMLElement( $result );
			$text = $sxe->children( 'soap', true );
			$text = $text->children( 'm', true );

			return base64_decode( $text->GetFormsForDocumentsResponse->return->List->BData ?? false );
		}
	}

	private function updateClientProducts( $items ) {

		$xmlData = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                    <soap:Header/>
                    <soap:Body>
                        <m:UpdateClientProducts xmlns:m="http://www.cargo3.ru">
                            <m:Login>' . $this->login . '</m:Login>
                            <m:Password>' . $this->password . '</m:Password>
                            <m:data>
                                <m:Key>Products</m:Key>';
		foreach ( $items as $item ) {

			switch ( $item['UNIT'] ) {
				case 'ч':
					$unit = '601632e1-976d-11dc-986e-0015170f8c09';
					break;
				case 'упк':
					$unit = '0232d433-c84d-11dd-927a-0015170f8c09';
					break;
				case 'см':
					$unit = '37dec53a-e399-11dd-927a-0015170f8c09';
					break;
				case 'м3':
					$unit = '601632de-976d-11dc-986e-0015170f8c09';
					break;
				case 'кг':
					$unit = '601632df-976d-11dc-986e-0015170f8c09';
					break;
				case 'шт':
				default:
					$unit = '601632e2-976d-11dc-986e-0015170f8c09';
					break;
			}

			$sku     = $this->getSKU( $item );
			$xmlData .= '             <m:List>
                                    <m:Key>' . $sku . '</m:Key>
                                    <m:Fields>
                                        <m:Key>Article</m:Key>
                                        <m:Value>' . $sku . '</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>Name</m:Key>
                                        <m:Value>' . $item['NAME'] . '</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>FullName</m:Key>
                                        <m:Value>' . $item['NAME'] . '</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>BaseUnit</m:Key>
                                        <m:Value>' . $unit . '</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>TypeOfProduct</m:Key>
                                        <m:Value>0</m:Value>
                                        <m:ValueType>decimal</m:ValueType>
                                    </m:Fields>
                                </m:List>';
		}
		$xmlData .= '             <m:List>
                                    <m:Key>ДОСТАВКА</m:Key>
                                    <m:Fields>
                                        <m:Key>Article</m:Key>
                                        <m:Value>ДОСТАВКА</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>Name</m:Key>
                                        <m:Value>Услуга доставки</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>FullName</m:Key>
                                        <m:Value>Услуга доставки</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>BaseUnit</m:Key>
                                        <m:Value>a227bc37-904c-11dd-9204-0015170f8c09</m:Value>
                                        <m:ValueType>string</m:ValueType>
                                    </m:Fields>
                                    <m:Fields>
                                        <m:Key>TypeOfProduct</m:Key>
                                        <m:Value>0</m:Value>
                                        <m:ValueType>decimal</m:ValueType>
                                    </m:Fields>
                                </m:List>';

		$xmlData .= '                               
                            </m:data>
                            <m:parameters>
                                <m:Key>Parameters</m:Key>
                            </m:parameters>
                        </m:UpdateClientProducts>
                    </soap:Body>
                </soap:Envelope>';
		CSEHelper::getData( $xmlData );
	}

	/**
	 * @param $docType string 'Order' for order, 'Waybill' for waybill
	 * @param $number string|array doc number
	 *
	 * @return mixed
	 */
	public function getDocumentStatus( $docType, $number ) {

		$xmlData = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.cargo3.ru">
                        <SOAP-ENV:Body>
                            <ns1:Tracking>
                                <ns1:login>' . $this->login . '</ns1:login>
                                <ns1:password>' . $this->password . '</ns1:password>
                                <ns1:documents>
                                    <ns1:Key>Documents</ns1:Key>
                                        <ns1:Properties>
                                         <ns1:Key>DocumentType</ns1:Key>
                                         <ns1:Value>' . $docType . '</ns1:Value>
                                         <ns1:ValueType>string</ns1:ValueType>
                                        </ns1:Properties>
                                        <ns1:Properties>
                                         <ns1:Key>OnlySelectedType</ns1:Key>
                                         <ns1:Value>true</ns1:Value>
                                         <ns1:ValueType>boolean</ns1:ValueType>
                                        </ns1:Properties>
                                        ';
		if ( is_array( $number ) ) {
			foreach ( $number as $num ) {
				$xmlData .= '<ns1:List><ns1:Key>' . $num . '</ns1:Key></ns1:List>';
			}
		} else {
			$xmlData .= '<ns1:List><ns1:Key>' . $number . '</ns1:Key></ns1:List>';
		}

		$xmlData .= '
                                </ns1:documents>
                                <ns1:parameters>
                                    <ns1:Key>Parameters</ns1:Key>
                                </ns1:parameters>
                            </ns1:Tracking>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>';

		$searchResult = CSEHelper::getData( $xmlData, false );
		if ( $searchResult ) {
			$sxe  = new SimpleXMLElement( $searchResult );
			$text = $sxe->children( 'soap', true );
			$text = $text->children( 'm', true );

			$arStatuses   = array(
				'ee9fc99e-53e5-4253-8b80-b582294ef526',
				'b7b5f799-94c7-4588-bae4-c14df35c9752',
				'b2af9ad9-22bd-4476-9393-7b51ffdab6f7',
				'1c3ed878-48d2-4192-bbe6-513727535f21',
				'0997e505-7ccf-42cd-b5e3-dda20d26da27',
				'8e5ded66-a8f5-4fa8-b863-03e1e0406df5',
				'e71eb2c1-36db-4b9c-9559-6a6350030d41',
				'56f72f4b-60c4-49a8-8a86-730bad6cd07a',
			);
			$arExceptions = array(
				'7f72c526-076b-11ea-80d7-7cd30aec6901',
				//	Количество доставок истекло
				'd25a2ec5-a2be-11e7-875d-001e67086478',
				//	Перенос по просьбе заказчика/отправителя
				'b357981f-a2be-11e7-875d-001e67086478',
				//	Утилизация отправления
				'67e3b5b1-b402-11df-a1ff-00237dd28494',
				//	Неудачная попытка вручения
				'bdd08c0c-d6a3-11e8-b321-005056b649b2',
				//	Отправление задержано на складе
				'91b16527-8d30-11dc-86de-0015170f8c09',
				//	Переадресация
				'87f14612-05cc-11e8-a2b1-001e67086478',
				//	Проблема при выполнении заявки
				'3d686e47-515f-11e1-8562-001e67086478',
				//	Груз на таможенном оформлении
				'66b22dc2-0659-11e8-a2b1-001e67086478',
				//	Груз на таможенном оформлении
				'99f37df7-82fe-11dc-86de-0015170f8c09',
				//	Перенос доставки
				'c9eca960-8ea4-11dc-86de-0015170f8c09',
				//	самовывоз из офиса КС
				'91b16526-8d30-11dc-86de-0015170f8c09',
				//	Вопрос по оплате
				'44048d1f-05cc-11e8-a2b1-001e67086478',
				//	Уточнение данных по доставке
				'ac33d1ee-4880-11e4-bb98-001e67086478',
				//	Загрузка док-тов некоррек
				'67e3b5b3-b402-11df-a1ff-00237dd28494',
				//	Задержка прибытия жд/авто, невылет
				'5a45fb8b-0659-11e8-a2b1-001e67086478',
				//	Задержка прибытия жд/авто, невылет
				'67e3b5b2-b402-11df-a1ff-00237dd28494',
				//	Попытка вручения не состоялась
				'67e3b5b0-b402-11df-a1ff-00237dd28494',
				//	некорректные сопров.докты
				'771e4809-9131-11dc-86de-0015170f8c09',
				//	Отказ при попытке вручения
				'f76287cd-0782-11ea-80ee-7cd30aebf951',
				//	Отказ через WEB
				'6e4ec5ed-1f58-11ea-80ee-7cd30aebf951',
				//	Переадресация через виджет
				'd8f7cf2d-ae45-11e2-a158-001e67086478',
				//	Отказ при предварительном согласовании
				'8fefa51f-9114-11dc-86de-0015170f8c09',
				//	Возврат отправления по требованию клиента
				'99f37dfb-82fe-11dc-86de-0015170f8c09',
				//	Отправление изъято
				'21aef387-0659-11e8-a2b1-001e67086478',
				//	Отправление изъято
				'4d3cd573-0659-11e8-a2b1-001e67086478',
				//	Требуется дополнительная упаковка
				'5aecc954-e791-11dd-927a-0015170f8c09',
				//	_Возврат обратных документов не произведен по причине их отсутствия

			);
			$resultArr    = $text->xpath( '//m:TrackingResponse/m:return/m:List' );
			if ( ! is_array( $resultArr ) ) {
				$resultArr = [ $resultArr ];
			}
			foreach ( $resultArr as $elements ) {
				$elements = $elements->children( 'm', true );
				foreach ( $elements->List ?? [] as $arRes ) {
					if ( in_array( $arRes->Properties[0]->Value, $arStatuses ) ) {
						$number = strval( $elements->Key );
						if ( $arRes->Properties[4]->Value != 'Расконсолидация' ) {
							$arStatusResult[ $number ]['NAME'] = $arRes->Properties[1]->Value;
							$arStatusResult[ $number ]['GUID'] = $arRes->Properties[0]->Value;
						}
						if ( ( $arRes->Properties[0]->Value == '8e5ded66-a8f5-4fa8-b863-03e1e0406df5' ) or ( $arRes->Properties[0]->Value == '56f72f4b-60c4-49a8-8a86-730bad6cd07a' ) ) {
							$arStatusResult[ $number ]['NAME'] = $arRes->Properties[1]->Value;
							$arStatusResult[ $number ]['GUID'] = $arRes->Properties[0]->Value;
							break;
						}
					} elseif ( in_array( $arRes->Properties[0]->Value, $arExceptions ) ) {
						$arStatusResult[ $number ]['NAME'] = 'Внимание! Информация по доставке';
						$arStatusResult[ $number ]['GUID'] = 'e71eb2c1-36db-4b9c-9559-6a6350030d41';
					}
				}
			}
		}

		return $arStatusResult ?? false;
	}

	private function getSKU( $item ) {
		$result = $item['SKU'];
		if ( empty( $item['SKU'] ) ) {
			$host   = $this->escapeHost( home_url() );
			$result = $host . $item['ID'];
		}

		return $result;
	}

	private function escapeHost( $homeUrl ) {
		$homeUrl = preg_replace( '~https?:\/\/~', '', $homeUrl );
		$homeUrl = preg_replace( '~[\:\-\,\.\/\_]~', '', $homeUrl );

		return $homeUrl;
	}

	/**
	 * @param mixed $deliveryOfCargo
	 *
	 * @return CSEOrder
	 */
	public function setDeliveryOfCargo( $deliveryOfCargo ) {
		$this->deliveryOfCargo = $deliveryOfCargo;

		return $this;
	}

	/**
	 * @param mixed $pvzGuid
	 *
	 * @return CSEOrder
	 */
	public function setPvzGuid( $pvzGuid ) {
		$this->pvzGuid = $pvzGuid;

		return $this;
	}

	/**
	 * @param mixed $declaredValueRate
	 *
	 * @return CSEOrder
	 */
	public function setDeclaredValueRate( $declaredValueRate ) {
		$this->declaredValueRate = $declaredValueRate;

		return $this;
	}

	/**
	 * @param mixed $CODAmount
	 *
	 * @return CSEOrder
	 */
	public function setCODAmount( $CODAmount ) {
		$this->CODAmount = $CODAmount;

		return $this;
	}

	/**
	 * @param mixed $takeTime
	 *
	 * @return CSEOrder
	 */
	public function setTakeTime( $takeTime ) {
		$this->takeTime = $takeTime;

		return $this;
	}

	/**
	 * @param mixed $contactPerson
	 *
	 * @return CSEOrder
	 */
	public function setContactPerson( $contactPerson ) {
		$this->contactPerson = $contactPerson;

		return $this;
	}

	/**
	 * @param mixed $deliveryTime
	 *
	 * @return CSEOrder
	 */
	public function setDeliveryTime( $deliveryTime ) {
		$this->deliveryTime = $deliveryTime;

		return $this;
	}

	/**
	 * @param mixed $deliveryTimeOf
	 *
	 * @return CSEOrder
	 */
	public function setDeliveryTimeOf( $deliveryTimeOf ) {
		$this->deliveryTimeOf = $deliveryTimeOf;

		return $this;
	}

	/**
	 * @param mixed $deliveryDate
	 *
	 * @return CSEOrder
	 */
	public function setDeliveryDate( $deliveryDate ) {
		$this->deliveryDate = $deliveryDate;

		return $this;
	}

	/**
	 * @param mixed $senderEmail
	 *
	 * @return CSEOrder
	 */
	public function setSenderEmail( $senderEmail ) {
		$this->senderEmail = $senderEmail;

		return $this;
	}

	/**
	 * @param mixed $senderAddress
	 *
	 * @return CSEOrder
	 */
	public function setSenderAddress( $senderAddress ) {
		$this->senderAddress = $senderAddress;

		return $this;
	}

}