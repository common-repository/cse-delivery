<?php
/**
 * @var $data array
 * @var $this CSEDeliveryAdmin
 */
?>
<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>
	<?php if ( ! empty( $data['client'] ) ): ?>
        <h2>Информация о клиенте:</h2>
        <ul>
            <li><strong>Клиент</strong> <?php echo $data['client']['name']; ?>
            </li>
            <li>
                <strong>Договор</strong> <?php echo $data['client']['contract']; ?>
            </li>
            <li>
                <strong>Валюта договора</strong> <?php echo $data['client']['currency']; ?>
            </li>
        </ul>
		<?php
		if ( ! empty( $data['orders'] ) ) {
			$this->cseStatisticList->display();
		}
		?>
	<?php else: ?>
        <div class="notice notice-info">
            <p>Нет данных о статистике</p>
        </div>
	<?php endif; ?>
</div>
