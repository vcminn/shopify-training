<?php
?>

<script type="text/javascript">
    $('#ProductSelect-product-template-option-1, #ProductSelect-product-template-option-0').on('change', function () {
        console.log('changed');
        loadWidget();
    });

    $(document).ready(function () {
        loadWidget();
    });

    function moneyFormat(price) {
        formatted_price = "{{ shop.money_format }}";
        formatted_price = formatted_price.replace("{" + "{amount_no_decimals_with_comma_separator}" + "}", price);
        return formatted_price;
    }

    function loadWidget() {
        var variant_id = {{current_variant.id}};
        var domain = '{{ shop.domain }}';
        if (variant_id) {
            $.ajax({
                type: 'GET',
                url: '//bundle.local/generate-bundle',
                data: {variant_id: variant_id},
                success: function (data) {
                    //console.log(data);
                    if (!jQuery.isEmptyObject(data)) {
                        $("#full_widget").css("display", "contents");
                        var bundle = $.parseJSON(JSON.stringify(data));
                        if (bundle['bundle_image'] == 0) {
                            document.getElementById('bundle_image').src = "https://bundle.local/images/" + bundle['image'];
                        } else {
                            document.getElementById('bundle_image').src = "https://bundle.local/images/" + bundle['image'];

                        }

                        if (bundle['bundle_style'] == 0) {
                            var button = "Add Bundle to Cart <br><strike>" + moneyFormat(bundle['base_total_price'].toFixed(2)) + "</strike>&nbsp;" + moneyFormat((bundle['base_total_price'] * (100 - bundle['discount']) / 100).toFixed(2)) + "<br> Save " + moneyFormat((bundle['base_total_price'] * bundle['discount'] / 100).toFixed(2));
                        } else {
                            var button = "Add Bundle to Cart <br><strike>" + moneyFormat(bundle['base_total_price'].toFixed(2)) + "</strike>&nbsp;" + moneyFormat((bundle['base_total_price'] * (100 - bundle['discount']) / 100).toFixed(2)) + "<br> Save " + bundle['discount'].toFixed(2) + "%";

                        }
                        $('#bundle_button').html(button);
                        $.ajax({
                            type: 'GET',
                            url: '//bundle.local/add-visitors',
                            data: {domain: domain, variant_id: variant_id},
                            success: function (data) {
                                console.log('+1 visistor');
                            },
                        })
                    }
                }, fail: function () {
                    alert('Something was wrong!');
                },
            });
        }
    }

    $(document).ready(function () {
        var variant_id = {{ current_variant.id }};
        var domain = '{{ shop.domain }}';
        $("#bundle_button").click(function () {
            $.ajax({
                type: 'GET',
                url: '//bundle.local/added-to-cart',
                data: {domain: domain, variant_id: variant_id},
                success: function (data) {
                    console.log('+1 added to cart');
                },
            })
        });
    });

    function getVariants() {
        variant_id = {{ current_variant.id }};
        $.ajax({
            type: 'GET',
            url: '//bundle.local/get-variants',
            data: {variant_id: variant_id},
            success: function (data) {
                addAllItems(data[0], data[1][0]);
            }
        });

    }

    function addAllItems(variants, bundle) {
        Shopify.queue = [];
        var quantity = {{ cart.item_count }};

        var newArray = variants;
        for (var i = 0; i < newArray.length; i++) {
            product = newArray[i];
            Shopify.queue.push({
                variantId: product,
            });
        }
        Shopify.moveAlong = function () {
            // If we still have requests in the queue, let's process the next one.
            if (Shopify.queue.length) {
                var request = Shopify.queue.shift();
                var data = 'id=' + request.variantId.variant_id + '&quantity=' + request.variantId.quantity + '&properties[_bundle_id]='
                    + bundle.id + '&properties[_discount]=' + bundle.discount + '&properties[_variants_count]=' + newArray.length;
                $.ajax({
                    type: 'POST',
                    url: '/cart/add.js',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        Shopify.moveAlong();
                        quantity += 1;
                    },
                    error: function () {
                        // if it's not last one Move Along else update the cart number with the current quantity
                        if (Shopify.queue.length) {
                            Shopify.moveAlong()
                        } else {
                            $('#cart-number').replaceWith('<a href="/cart" id="cart-number">View cart (" + quantity + ")</a>');
                        }
                    }
                });
            }
            // If the queue is empty, we add 1 to cart
            else {
                quantity += 1;
                addToCartOk(quantity);
            }
        };
        Shopify.moveAlong();

    };

    function addToCartOk(quantity) {
        $('#cart-number').replaceWith('<a href="/cart" id="cart-number">View cart (" + quantity + ")</a>');
    }


</script>

