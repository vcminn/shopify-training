function preview($file) {
    $img_preview = window.URL.createObjectURL($file);
    document.getElementById('blah').src = $img_preview;
}
$('.quantity').on('change',function(){
    console.log('pro');
    loadQuantity();
});
$(document).ready(function () {
    load_prices();

    function load_discount_percent(base_price, discount_price) {
        $.ajax({
            url: "/show-percent",
            method: "GET",
            data: {base_price: base_price, discount_price: discount_price},
            success: function (data) {

                $('#discount_percent').val(data);
            }
        });
    }

    function load_discounted_price(value, id, index) {
        $.ajax({
            url: "/show-price",
            method: "GET",
            data: {price: value, index: index},
            success: function (data) {
                $('#discount-price' + id).html(data);
            }
        });
    }

    function load_prices(discount) {
        var input = discount;
        if (input !== '') {
            var price = calcPrice(input);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
            });
            load_discount_percent(price[1], input);
            if (price[1] !== 0 ) {
                document.getElementById('base_price').value = price[1];
            }
        } else {
            var price = calcPrice(0);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
            });
            load_discount_percent(price[1], input);
            if (price[1] !== 0 ) {
                document.getElementById('base_price').value = price[1];
            }
        }

    }

    $('#discount_price').keyup(function () {
        var discount_price = $(this).val();
        var bundle_base = calcPrice(0)[1];
        var discount_percent = (discount_price / bundle_base * 100).toFixed(2);
        load_prices((100 - discount_percent));
    });

    $('#discount_percent').keyup(function () {
        var input = $(this).val();
        if (input !== '') {
            var price = calcPrice(input);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
            });
            document.getElementById('base_price').value = price[1];
            $('#discount_price').val((price[1] * (100 - price[3]) / 100).toFixed(2));
        } else {
            var price = calcPrice(0);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
                document.getElementById('base_price').value = price[1];
            });
        }
    });
});

function reload_widget(value, checked) {
    var clicked_value = $('input[name="bundle_style"]:checked').val();
    load_widget();
    load_style(clicked_value, true);
}

function load_style(clicked_value, checked) {

    if (checked === true) {
        var input = $('#discount_percent').val();
        var price = calcPrice(input);
        $.ajax({
            url: "/load-style",
            method: "GET",
            data: {value: clicked_value, bundle_base: price[1], bundle_price: price[2], discount: price[3]},
            success: function (data) {
                $('#style_announce').html(data);
            }
        });
    }
}

function load_widget(img_style, checked) {
    var img_src = '';
    if (img_style == 1) {
        if ($("#image").val()) {
            img_src = document.getElementById('blah').src;
        } else {
            img_src = "/images/" + bundle_image;
        }
    }
    if (checked === true) {
        var input = getSelectedProducts();
        $.ajax({
            url: "/search/load-widget",
            method: "GET",
            data: {products: input, style: img_style, img_src: img_src},
            success: function (data) {
                $('#widget').html(data);
                //alert('Loaded widget');
            }
        });
    }
}


$(document).ready(function () {
    load_data();
    generatePagination();
    $('#search_product').keyup(function () {

        var input = $(this).val();
        if (input !== '') {
            load_data(1, input);
        }
        else {
            load_data();
        }
    });

    function saveSelected(variant_id) {
        if (selected.indexOf(variant_id) === -1) {
            selected[selected.length] = variant_id;
        }
        return selected;
    }

    $(document).on('click', 'button[name="add-product"]', function () {
        var properties = $(this).val();

        var variant_id = $(this).val().split('&')[0].replace("id=", '');
        var reg_price = $(this).val().split('&')[1].replace("price=", '')
        $selected = saveSelected(variant_id);

        for ($i = 0; $i < selected.length; $i++) {
            if (prices[selected[$i]] === undefined) {
                prices[selected[$i]] = reg_price;
            }
        }

    });
});

function generatePagination() {
    $.ajax({
        url: "/generate-pagination",
        method: "GET",
        success: function (data) {
            $('#pagination').html(data);
        }
    });
}

function load_data(page = 1, value = '') {
    $.ajax({
        url: "/search/search-products",
        method: "GET",
        data: {page: page, search: value},
        success: function (data) {
            $('#products').html(data);
        }
    });

}


function load_data_to_table(value) {
    $.ajax({
        url: "/search/table-products",
        method: "GET",
        data: {products: value},
        success: function (data) {
            $('#subTable').css("visibility", "visible");
            $('#subTable2').css("visibility", "visible");
            $('#setup-products').html(data);
            var bundle_base = calcPrice(0)[1];
            $('#price_warning').html('Lower than ' + bundle_base);
        }
    });
}

$(document).on('click', '#products-to-table', function () {
    var input = selected;

    if (input.length === 0) {
        $('#subTable').css("visibility", "hidden");
    }
    load_data_to_table(input);
    load_widget(input);
    $(document).ready(function () {
        for ($i = 0; $i < selected.length; $i++) {
            if (prices[selected[$i]] === undefined) {
                prices[selected[$i]] = $('#price' + selected[$i]).html();
            }
        }
    });
});

$(document).on('change', '.quantity', function () {
    var price = calcPrice(0);
    var bundle_base = price[1];
    $('#price_warning').html('Lower than ' + bundle_base);
    document.getElementById('base_price').value = price[1];
    console.log(document.getElementById('base_price').value);
    var variant_id = this.id.replace('quantity', '');
    var quantity = this.options[this.selectedIndex].value;
    var reg_price = prices[variant_id];
    var new_reg_price = quantity * reg_price;

    $('#price' + variant_id).html(new_reg_price);
});

function refreshPrice() {
    var price = calcPrice($('#discount_percent').val());

    $('#discount_price').val((price[1] * (100 - price[3]) / 100).toFixed(2));
}

function calcPrice($discount) {
    var price_array = new Array();
    var input = selected;
    var bundle_base = 0;
    var bundle_price = 0;
    input.forEach(function (id, index) {
        $quantity = parseInt($("#quantity" + id).val());
        $reg_price = parseFloat(prices[id]).toFixed(2);
        $discount_price = ($quantity * $reg_price * (100 - $discount) / 100).toFixed(2);
        price_array.push($discount_price);
        bundle_base += ($quantity * $reg_price);
        bundle_price = (bundle_base * (100 - $discount) / 100).toFixed(2);
    })
    return [price_array, bundle_base, bundle_price, $discount];
}

function getSelectedProducts() {
    var total = selected.length;
    $.ajax({
        url: "/save",
        data: {total: total}
    });
    return selected;
}

function Clear() {
    clearRadioGroup("RadioInputName");
}

function clearRadioGroup(GroupName) {
    var ele = document.getElementsByName(GroupName);
    for (var i = 0; i < ele.length; i++)
        ele[i].checked = false;
}

function removeSelected(variant_id) {
    var index = selected.indexOf(variant_id);
    if (index !== -1) {
        selected.splice(index, 1);
    }
    load_data_to_table(selected);

}

$(document).on('click', '#add-products', function () {
// Get the container element
    var btnContainer = document.getElementById("pagination");

// Get all buttons with class="btn" inside the container
    var btns = btnContainer.getElementsByClassName("page");

// Loop through the buttons and add the active class to the current/clicked button
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function () {
            var current = document.getElementsByClassName("page active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
        });
    }
});
$.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
