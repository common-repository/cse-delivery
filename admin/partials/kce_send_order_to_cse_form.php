<?php
/**
 * @var $this CSEDeliveryAdmin
 * @var $order WC_Order
 * @var $weight float
 * @var $qty int
 * @var $totalWeight float
 */
$defaultDate = date( 'Y-m-d', strtotime( '+1day' ) );
$timeRanges  = CSEFunctions::getTimesRange();
$times       = CSEFunctions::getTimes();
?>
<div class="wrap">
    <form id="send_new_waybill_to_cse" method="post" enctype="multipart/form-data"
          action="<?= admin_url( 'admin-ajax.php' ) ?>">
        <input type="hidden" name="action" value="send_new_waybill_to_cse">
		<?= wp_nonce_field( 'send_new_waybill_to_cse' ) ?>
        <input type="hidden" name="_wp_http_referer" value="<?= $_SERVER['HTTP_REFERER'] ?>">
        <h4>Информация об отправителе</h4>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="wc_cse_date">Дата забора груза</label>
                </th>
                <td>
                    <input class="regular-text" type="date" id="wc_cse_date" name="data[date]"
                           value="<?= $defaultDate ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_time">Время забора груза</label>
                </th>
                <td>
                    <select class="regular-text" id="wc_cse_time" name="data[time]" required="required">
						<?php foreach ( $times as $time ): ?>
                            <option value="<?php echo $time ?>"><?php echo $time ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_qty">Количество грузовых мест</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_qty" name="data[qty]" value="1"
                           required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_weight">Общий вес заказа, кг</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_weight" name="data[weight]"
                           value="<?= $totalWeight ?>" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>
        <h4>Информация о получателе</h4>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="wc_cse_recipient_address">Адрес доставки</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_recipient_address" name="data[recipient_address]"
                           value="<?= $order->get_shipping_address_1() ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_recipient_date">Дата доставки</label>
                </th>
                <td>
                    <input class="regular-text" type="date" id="wc_cse_recipient_date" name="data[recipient_date]"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_recipient_name">Имя получателя</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_recipient_name" name="data[recipient_name]"
                           value="<?= $order->get_formatted_shipping_full_name() ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_recipient_phone">Телефон получателя</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_recipient_phone" name="data[recipient_phone]"
                           value="<?= $order->get_billing_phone() ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_comment">Комментарий для курьера</label>
                </th>
                <td>
                    <textarea class="regular-text" type="text" id="wc_cse_comment" name="data[comment]"></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_total">Получить наличные на точке (руб.)</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_total" name="data[total]"
                           value="<?= $order->get_total() ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wc_cse_order_id">Идентификатор заказа магазина</label>
                </th>
                <td>
                    <input class="regular-text" type="text" id="wc_cse_order_id" name="data[order_id]"
                           value="<?= $order->get_order_number() ?>" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php echo __( 'Сформировать накладную' ) ?>">
        </p>
    </form>
</div>