(function ($) {
    'use strict';

    !function (e, t, n, c, s, a, i) {
        e.CsePvzWidget = s, e[s] = e[s] || function () {
            (e[s].q = e[s].q || []).push(arguments)
        }, e[s].l = 1 * new Date, a = t.createElement(n), i = t.getElementsByTagName(n)[0], a.async = 1, a.src = c, i.parentNode.insertBefore(a, i)
    }(window, document, 'script', 'https://lk.cse.ru/dist/js/widget.js', 'csepvzwidget');

    let isSelectPZV = false;
    $(document).ready(function () {
        let selectWooParams = {
            placeholder: "Введите название населенного пункта",
            language: "ru",
            minimumInputLength: 3,
            ajax: {
                url: wc_cse_ajax.url,
                dataType: "json",
                type: "GET",
                delay: 200,
                data: function (params) {
                    let query = {
                        action: 'get_cities',
                        search: params.term,
                        wpnonce: wc_cse_ajax.wpnonce
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

        let $wc_cse_address = $('[autocomplete="wc_cse_address"]');
        $wc_cse_address.selectWoo(selectWooParams);
        csepvzwidget('init', {
            token: '38a0abb7099ea700c597b17764ffe6a9',
            city: wc_cse_ajax.city_guid,
        });

        let csePVZ = $('input.shipping_method[value=cse_pvz]:checked');
        if ( csePVZ.length > 0){
            csePVZ.click();
        }
    });

    $(document).on("select2:select", function (e) {
        if (e.target.name == 'billing_city' || e.target.name == 'shipping_city') {
            csepvzwidget('init', {
                token: '38a0abb7099ea700c597b17764ffe6a9',
                city: e.params.data.id
            });
            getAddress().val('');
            $('input.shipping_method:not("[value=cse_pvz]")').click();
        }
    });

    $(document).on('click', 'input.shipping_method', function (e) {
        let $address = getAddress();
        if ($(this).val() == 'cse_pvz' && $(this).prop('checked')) {
            $address.val('');
            $address.prop('readonly', true);
            isSelectPZV = false;
            csepvzwidget('open', {
                callback: function (params) {
                    $address.val(params.address);
                    $('#cse_pvz').val(params.guid);
                    isSelectPZV = true;
                },
                onclose: function () {
                    if (!isSelectPZV) {
                        $('input.shipping_method:not("[value=cse_pvz]")').click();
                        $address.val('');
                    }
                }
            });
        } else {
            $address.prop('readonly', false);
        }
    });

    const getAddress = function () {
        return $('#shipping_address_1:visible').length > 0 ? $('#shipping_address_1') : $('#billing_address_1');
    }
})(jQuery);
