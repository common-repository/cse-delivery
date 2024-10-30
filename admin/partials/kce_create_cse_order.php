<?php
/**
 * @var $wayBills array|null|object
 * @var $this CSEDeliveryAdmin
 * @var $times array
 * @var $urgency array
 * @var $payer array
 * @var $paymentTypes array
 * @var $shippingTypes array
 * @var $cargoTypes array
 * @var $table CSEWaybillsTableList
 */
?>

<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>
	<?php if ( count( $wayBills ) > 0 ): ?>
        <form method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ) ?>">
            <input type="hidden" name="action" value="create_cse_order">
            <h2>Ваши заказы и накладные</h2>
			<?php $this->cseWaybilsList->display(); ?>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="take_date">Дата забора груза:</label>
                    </th>
                    <td>
                        <input class="regular-text" type="date" name="deliveryDate" id="take_date"
                               value="<?php echo date( 'Y-m-d', strtotime( "+3day" ) ) ?>" required="required">
                        <br>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="take_time">Время забора груза:</label>
                    </th>
                    <td>
                        <select class="regular-text" name="deliveryTime" id="take_time">
							<?php foreach ( $times as $time ): ?>
                                <option value="<?php echo str_replace( '-', ' ', $time ) ?>"><?php echo $time ?></option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sender_name">Отправитель груза:</label></th>
                    <td>
                        <input class="regular-text" type="text" name="sender_name" id="sender_name"
                               value='<?php echo $this->options['wc_cse_name_company'] ?>'>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sender_city">Город отправителя (начните вводить):</label></th>
                    <td>
                        <select class="regular-text" name="sender_geo" id="sender_city" style="min-width: 25em">
                            <option value="<?php echo $this->options['wc_cse_city'] ?>"><?php echo CSEHelper::getCityName( $this->options['wc_cse_city'] ) ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sender_address">Адрес отправителя:</label></th>
                    <td>
                        <input class="regular-text" type="text" name="sender_address" id="sender_address"
                               value="<?php echo $this->options['wc_cse_address'] ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sender_phone">Телефон отправителя:</label></th>
                    <td>
                        <input class="regular-text" type="text" name="sender_phone" id="sender_phone"
                               value="<?php echo $this->options['wc_cse_phone'] ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sender_email">Email отправителя:</label></th>
                    <td>
                        <input class="regular-text" type="text" name="sender_email" id="sender_email"
                               value="<?php echo $this->options['wc_cse_email'] ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="urgency">Срочность:</label></th>
                    <td>
                        <select class="regular-text" name="urgency" id="urgency">
							<?php foreach ( $urgency as $key => $item ): ?>
                                <option <?php echo selected( $this->options['wc_cse_urgency_code'], $key ) ?>
                                        value="<?php echo $key ?>">
									<?php echo $item ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="payer">Плательщик:</label></th>
                    <td>
                        <select class="regular-text" name="payer" id="payer">
							<?php foreach ( $payer as $key => $item ): ?>
                                <option <?php echo selected( $this->options['wc_cse_payer_code'], $key ) ?>
                                        value="<?php echo $key ?>">
									<?php echo $item ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="payment_type">Способ оплаты:</label></th>
                    <td>
                        <select class="regular-text" name="payment_type" id="payment_type">
							<?php foreach ( $paymentTypes as $key => $item ): ?>
                                <option <?php echo selected( $this->options['wc_cse_payment_method'], $key ) ?>
                                        value="<?php echo $key ?>">
									<?php echo $item ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="delivery_type">Способ доставки:</label></th>
                    <td>
                        <select class="regular-text" name="delivery_type" id="delivery_type">
							<?php foreach ( $shippingTypes as $key => $item ): ?>
                                <option <?php echo selected( $this->options['wc_cse_shipping_method'], $key ) ?>
                                        value="<?php echo $key ?>">
									<?php echo $item ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cargo_type">Тип груза:</label></th>
                    <td>
                        <select class="regular-text" name="cargo_type" id="cargo_type">
							<?php foreach ( $cargoTypes as $key => $item ): ?>
                                <option <?php echo selected( $this->options['wc_cse_cargo_type'], $key ) ?>
                                        value="<?php echo $key ?>">
									<?php echo $item ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="comment">Комментарий отправителя:</label></th>
                    <td>
                        <textarea name="comment" id="comment" cols="50" rows="5"></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php echo __( 'Отправить заказ' ) ?>"/>
            </p>
        </form>
	<?php else: ?>
        <div class="notice notice-error">
            <p>Нет накладных для формирования заказа</p>
        </div>
	<?php endif; ?>
</div>
