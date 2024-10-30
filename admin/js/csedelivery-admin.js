(function ($) {
    'use strict';

    $(document).ready(
        function () {

            const select2config = {
                placeholder: "Введите название населенного пункта",
                language: "ru",
                minimumInputLength: 3,
                ajax: {
                    url: wp.ajax.settings.url,
                    dataType: "json",
                    type: "GET",
                    delay: 200,
                    data: function (params) {
                        let query = {
                            action: 'get_cities',
                            search: params.term,
                        };
                        return query;
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results
                        }
                    },
                    cache: true
                },
            };

            let $wc_cse_address = $('#sender_address');
            let $wc_cse_city = $('#wc_cse_city');
            if ($wc_cse_address.length > 0) {
                $wc_cse_address.select2(select2config);
            }
            if ($wc_cse_city.length > 0) {
                $wc_cse_city.select2(select2config);
            }
            $('#actions .button').on('click', function (event) {
                let $select = $(this).parent().find('select');
                if ($select.val() == 'send_new_waybill_to_cse') {
                    event.preventDefault();
                    tb_show("Формирование накладной КСЕ", woocommerce_admin.ajax_url + "?action=get_waybill_create_form&order_id=" + woocommerce_admin_meta_boxes.post_id);
                }
            });
        }
    );

})(jQuery);


