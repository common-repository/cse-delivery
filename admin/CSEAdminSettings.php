<?php


class CSEAdminSettings {

	/**
	 * @var false|mixed|void
	 */
	private $options;
	private $optionsName;

	public function __construct( $optionsName ) {
		$this->optionsName = $optionsName;
		$this->options     = get_option( $optionsName );
	}

	public function addOptionPage() {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->optionsName ) {
			?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title() ?></h2>
                <form method="post" enctype="multipart/form-data" action="options.php">
					<?php
					settings_fields( $this->optionsName ); // меняем под себя только здесь (название настроек)
					do_settings_sections( $this->optionsName );
					?>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
                    </p>
                </form>
            </div>
			<?php
		}
	}

	public function optionSettings() {
		register_setting( $this->optionsName, $this->optionsName, array( $this, 'wc_cse_options_settings' ) );

		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->optionsName ) {
			$section = "wc_cse_section_main";
			$fields  = $this->getMainOptions();
			$this->addSection( $section, "Основные параметры", $fields );

			$section = "wc_cse_section_delivery_cost";
			$fields  = $this->getDeliveryCostOptions();
			$this->addSection( $section, "Алгоритм расчета стоимости доставки", $fields );

			if ( $this->options["wc_cse_login"] && $this->options["wc_cse_password"] ) {
				$obKCE = new CSE( $this->options["wc_cse_login"], $this->options["wc_cse_password"] );

				$fields = $this->getDeliveryNoteOptions( $obKCE );
				if ( $fields ) {
					$this->addSection( "wc_cse_section_delivery_notes", "Данные для формирования накладных на доставку", $fields );
				}

				$fields = $this->getOrderOptions( $obKCE );
				if ( $fields ) {
					$this->addSection( "wc_cse_section_order", "Параметры заказа", $fields );
				}

				$fields = $this->getOrderStatusesOptions();
				if ( $fields ) {
					$this->addSection( "wc_cse_section_order_statuses", "Соответствие статусов заказа", $fields );
				}
			}
		}
	}

	private function getMainOptions() {
		$fields = array(
			array(
				'type'      => 'text',
				'id'        => 'wc_cse_login',
				'desc'      => 'Логин от кабинета КСЕ',
				'label_for' => 'wc_cse_login',
				'label'     => 'Логин',
				'require'   => 'required=required',
			),
			array(
				'type'      => 'password',
				'id'        => 'wc_cse_password',
				'desc'      => 'Пароль от кабинета КСЕ',
				'label_for' => 'wc_cse_password',
				'label'     => 'Пароль',
				'require'   => 'required=required',
			),
			array(
				'type'      => 'number',
				'id'        => 'wc_cse_weight',
				'desc'      => 'Вес товара по умолчанию, кг',
				'label_for' => 'wc_cse_weight',
				'label'     => 'Вес'
			),
			array(
				'type'      => 'number',
				'id'        => 'wc_cse_delivery_days',
				'desc'      => '',
				'label_for' => 'wc_cse_delivery_days',
				'label'     => 'Срок доставки до склада КСЕ (дней)'
			),
			array(
				'type'      => 'checkbox',
				'id'        => 'wc_cse_debug',
				'desc'      => 'Режим отладки',
				'label_for' => 'wc_cse_debug',
				'label'     => 'Отладка'
			),
		);

		return $fields;
	}

	private function getDeliveryCostOptions() {
		$fields = array(
			array(
				'type'      => 'number',
				'id'        => 'wc_cse_extra_charge_value',
				'desc'      => 'Скидка / наценка (отрицательное - скидка, положительное - наценка)',
				'label_for' => 'wc_cse_extra_charge_value',
				'label'     => 'Скидка / наценка'
			),
			array(
				'type'      => 'select',
				'id'        => 'wc_cse_extra_charge_type',
				'desc'      => '',
				'label_for' => 'wc_cse_extra_charge_type',
				'label'     => 'Единица измерения',
				'vals'      => [
					'percent'  => '% скидки / наценки',
					'fix'      => 'фикс. скидка / наценка',
					'fixprice' => 'фикс. стоимость доставки'
				]
			),
		);

		return $fields;
	}

	public function addSection( $section_id = "", $section_name = "", $fields = [] ) {
		add_settings_section( $section_id, $section_name, '', $this->optionsName );
		$this->addFields( $fields, $section_id );
	}

	public function addFields( $fields, $section ) {
		foreach ( $fields as $field ) {
			add_settings_field( $field["id"], $field["label"], array(
				$this,
				'optionDisplaySettings'
			), $this->optionsName, $section, $field );
		}
	}

	/**
	 * @param $obKCE CSE
	 *
	 * @return array|bool
	 */
	private function getDeliveryNoteOptions( $obKCE ) {
		//Код склада
		$storeGUID = $obKCE->getUserRepository();
		if ( $storeGUID ) {

			//Срочность
			$arUrgency = $obKCE->getUrgencies();

			//Получаем код плательщика
			$PayerCode = $obKCE->getPayerCode();

			//Получаем возможные способы доставки
			$ShippingMethod = $obKCE->getShippingMethods();

			//Получаем возможные способы оплаты
			$PayMethod = $obKCE->getPayMethods();

			//Получаем возможные типы груза
			$CargoType = $obKCE->getCargoTypes();

			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_name_company',
				'desc'      => 'Юридическое название вашей компании',
				'label_for' => 'wc_cse_name_company',
				'label'     => 'Название компании',
				'require'   => 'required=required',
			);

			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_contact_person',
				'desc'      => '',
				'label_for' => 'wc_cse_contact_person',
				'label'     => 'Контактное лицо',
				'require'   => 'required=required',
			);

			$cityGUID = CSEFunctions::getOptions()['wc_cse_city'] ?? '';
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_city',
				'desc'      => 'Город забора груза по умолчанию',
				'label_for' => 'wc_cse_city',
				'label'     => 'Город',
				'require'   => 'required=required',
				'vals'      => [
					$cityGUID => CSEHelper::getCityName( $cityGUID )
				]
			);
			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_address',
				'desc'      => 'Адрес забора груза по умолчанию',
				'label_for' => 'wc_cse_address',
				'label'     => 'Адрес',
				'require'   => 'required=required',
			);
			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_phone',
				'desc'      => 'Контактный телефон отправителя    ',
				'label_for' => 'wc_cse_phone',
				'label'     => 'Телефон',
				'require'   => 'required=required',
			);
			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_email',
				'desc'      => 'Контактный E-mail отправителя    ',
				'label_for' => 'wc_cse_email',
				'label'     => 'E-mail',
				'require'   => 'required=required',
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_urgency_code',
				'desc'      => 'Срочность доставки по умолчанию',
				'label_for' => 'wc_cse_urgency_code',
				'label'     => 'Срочность доставки',
				'vals'      => $arUrgency
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_payer_code',
				'desc'      => '',
				'label_for' => 'wc_cse_payer_code',
				'label'     => 'Код плательщика',
				'vals'      => $PayerCode
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_payment_method',
				'desc'      => '',
				'label_for' => 'wc_cse_payment_method',
				'label'     => 'Способы оплаты',
				'vals'      => $PayMethod
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_shipping_method',
				'desc'      => '',
				'label_for' => 'wc_cse_shipping_method',
				'label'     => 'Способы доставки',
				'vals'      => $ShippingMethod
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_cargo_type',
				'desc'      => '',
				'label_for' => 'wc_cse_cargo_type',
				'label'     => 'Типы груза',
				'vals'      => $CargoType
			);
			$fields[] = array(
				'type'      => 'text',
				'id'        => 'wc_cse_payform_name',
				'desc'      => '(уточните у своего менеджера)',
				'label_for' => 'wc_cse_payform_name',
				'label'     => 'Название печатной формы',
				'require'   => 'required=required',
			);
		} else {
			$fields = false;
		}

		return $fields;
	}

	/**
	 * @param $obKCE CSE
	 *
	 * @return array|bool
	 */
	private function getOrderOptions( $obKCE ) {
		$arDeliveryTypes = $obKCE->getDeliveryMethods();
		if ( $arDeliveryTypes ) {
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_kurierka',
				'desc'      => 'Тип доставки: курьерская',
				'label_for' => 'wc_cse_kurierka',
				'label'     => 'Курьер',
				'vals'      => $arDeliveryTypes['courier']
			);
			$fields[] = array(
				'type'      => 'select',
				'id'        => 'wc_cse_pvz',
				'desc'      => 'Тип доставки: Пункт Выдачи Заказа',
				'label_for' => 'wc_cse_pvz',
				'label'     => 'ПВЗ',
				'vals'      => $arDeliveryTypes['pvz']
			);
		} else {
			$fields = false;
		}

		return $fields;
	}

	/**
	 * @return array|bool
	 */
	private function getOrderStatusesOptions() {
		$orderStatuses = wc_get_order_statuses();
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_ee9fc99e-53e5-4253-8b80-b582294ef526',
			'desc'      => '',
			'label_for' => 'wc_cse_status_ee9fc99e-53e5-4253-8b80-b582294ef526',
			'label'     => 'Зарегистрирована накладная',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_b7b5f799-94c7-4588-bae4-c14df35c9752',
			'desc'      => '',
			'label_for' => 'wc_cse_status_b7b5f799-94c7-4588-bae4-c14df35c9752',
			'label'     => 'Груз получен на склад КС',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_b2af9ad9-22bd-4476-9393-7b51ffdab6f7',
			'desc'      => '',
			'label_for' => 'wc_cse_status_b2af9ad9-22bd-4476-9393-7b51ffdab6f7',
			'label'     => 'Груз передан на доставку',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_1c3ed878-48d2-4192-bbe6-513727535f21',
			'desc'      => '',
			'label_for' => 'wc_cse_status_1c3ed878-48d2-4192-bbe6-513727535f21',
			'label'     => 'Отправление прибыло в город',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_0997e505-7ccf-42cd-b5e3-dda20d26da27',
			'desc'      => '',
			'label_for' => 'wc_cse_status_0997e505-7ccf-42cd-b5e3-dda20d26da27',
			'label'     => 'Доставка завершена',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_8e5ded66-a8f5-4fa8-b863-03e1e0406df5',
			'desc'      => '',
			'label_for' => 'wc_cse_status_8e5ded66-a8f5-4fa8-b863-03e1e0406df5',
			'label'     => 'Отправление доставлено получателю',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_e71eb2c1-36db-4b9c-9559-6a6350030d41',
			'desc'      => '',
			'label_for' => 'wc_cse_status_e71eb2c1-36db-4b9c-9559-6a6350030d41',
			'label'     => 'Внимание! Информация по доставке',
			'vals'      => $orderStatuses
		);
		$fields[]      = array(
			'type'      => 'select',
			'id'        => 'wc_cse_status_56f72f4b-60c4-49a8-8a86-730bad6cd07a',
			'desc'      => '',
			'label_for' => 'wc_cse_status_56f72f4b-60c4-49a8-8a86-730bad6cd07a',
			'label'     => 'Оформлен возврат отправления',
			'vals'      => $orderStatuses
		);

		return $fields;
	}

	public function optionDisplaySettings( $args ) {
		$id      = '';
		$type    = '';
		$desc    = '';
		$require = '';
		$vals    = [];
		extract( $args );

		$option_name = $this->optionsName;
		$o           = get_option( $option_name );

		if ( isset( $value ) ) {
			$o[ $id ] = $value;
		}

		if ( ! isset( $o[ $id ] ) ) {
			$o[ $id ] = '';
		}

		switch ( $type ) {
			case 'text':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<input class='regular-text' type='text' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' $require/>";
				echo ( $desc != '' ) ? "<br/><span class='description'>$desc</span>" : "";
				break;
			case 'hidden':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<input class='regular-text' type='hidden' id='$id' name='" . $option_name . "[$id]' value='$o[$id]'/>";
				break;
			case 'hidden_text':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<span>$o[$id]</span>";
				echo "<input class='regular-text' type='hidden' id='$id' name='" . $option_name . "[$id]' value='$o[$id]'/>";
				echo ( $desc != '' ) ? "<br/><span class='description'>$desc</span>" : "";
				break;
			case 'password':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<input class='regular-text' type='password' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' $require/>";
				echo ( $desc != '' ) ? "<br/><span class='description'>$desc</span>" : "";
				break;
			case 'number':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<input class='regular-text' type='number' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' $require/>";
				echo ( $desc != '' ) ? "<br/><span class='description'>$desc</span>" : "";
				break;
			case 'textarea':
				$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
				echo "<textarea class='code large-text' cols='50' rows='10' type='text' id='$id' name='" . $option_name . "[$id]'>$o[$id]</textarea>";
				echo ( $desc != '' ) ? "<br/><span class='description'>$desc</span>" : "";
				break;
			case 'checkbox':
				$checked = ( isset( $o[ $id ] ) && $o[ $id ] == 'on' ) ? " checked='checked'" : '';
				echo "<label><input type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked/> ";
				echo ( $desc != '' ) ? $desc : "";
				echo "</label>";
				break;
			case 'select':
				echo "<select id='$id' class='regular-text' name='" . $option_name . "[$id]' $require>";
				foreach ( $vals as $v => $l ) {
					$selected = ( $o[ $id ] == $v ) ? "selected='selected'" : '';
					echo "<option value='$v' $selected>$l</option>";
				}
				echo ( $desc != '' ) ? $desc : "";
				echo "</select>";
				break;
			case 'radio':
				echo "<fieldset>";
				foreach ( $vals as $v => $l ) {
					$checked = ( $o[ $id ] == $v ) ? "checked='checked'" : '';
					echo "<label><input type='radio' name='" . $option_name . "[$id]' value='$v' $checked/>$l</label><br/>";
				}
				echo "</fieldset>";
				break;
		}
	}
}