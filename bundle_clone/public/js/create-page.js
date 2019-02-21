function preview($file) {
    $img_preview = window.URL.createObjectURL($file);
    document.getElementById('blah').src = $img_preview;
}

function calcPrice($discount) {
    var price_array = new Array();
    var input = getSelectedProducts();
    console.log(input);
    var bundle_base = 0;
    var bundle_price = 0;
    input.forEach(function (id, index) {
        $quantity = parseInt($("#quantity" + id).val());
        console.log('quantity of' + id + ':' + $quantity);
        $reg_price = parseFloat($("#price" + id).text()).toFixed(2);
        console.log('price of' + id + ':' + $reg_price);
        $discount_price = ($quantity * $reg_price * (100 - $discount) / 100).toFixed(2);
        console.log($discount_price);
        price_array.push($discount_price);
        bundle_base += ($quantity * $reg_price);
        bundle_price = (bundle_base * (100 - $discount) / 100).toFixed(2);
    })
    return [price_array, bundle_base, bundle_price, $discount];
}

$(document).ready(function () {
    load_prices();

    function load_discount_percent(base_price, discount_price) {
        $.ajax({
            url: "/show-percent",
            method: "GET",
            data: {base_price: base_price, discount_price: discount_price},
            success: function (data) {
                console.log(data);
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
        var selected = getSelectedProducts();
        if (input !== '') {
            var price = calcPrice(input);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
            });
            load_discount_percent(price[1], input);
            document.getElementById('base_price').value = price[1];

        } else {
            var price = calcPrice(0);
            selected.forEach(function (id, index) {
                load_discounted_price(price[0], id, index);
            });
            load_discount_percent(price[1], input);
            document.getElementById('base_price').value = price[1];
        }
    }

    $('#discount_price').keyup(function () {
        var discount = $(this).val();
        load_prices(discount);
    });

    $('#discount_percent').keyup(function () {
        var input = $(this).val();
        var selected = getSelectedProducts();
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
    console.log(checked);
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
    console.log(document.getElementById('blah').src);
    if (img_style == 1) {
        img_src = document.getElementById('blah').src;
    }
    if (checked === true) {
        console.log(img_style);
        var input = getSelectedProducts();
        console.log(input);
        $.ajax({
            url: "search/load-widget",
            method: "GET",
            data: {products: input, style: img_style, img_src: img_src},
            success: function (data) {
                $('#widget').html(data);
                //alert('Loaded widget');
            }
        });
    }
}
var selected = new Array;
$(document).ready(function () {
    load_data();
    generatePagination();
    $('#search_product').keyup(function () {
        console.log('hello');
        var input = $(this).val();
        if (input !== '') {
            load_data(input);
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
        var variant_id = $(this).val();
        $selected = saveSelected(variant_id);
        console.log(selected);
    });
});

function generatePagination() {
    $.ajax({
        url: "generate-pagination",
        method: "GET",
        success: function (data) {
            $('#pagination').html(data);
        }
    });
}

function load_data(page = 1, value = '') {
    // $select = getSelectedId();
    //event.preventDefault();
    $.ajax({
        url: "search/search-products",
        method: "GET",
        data: {page: page, search: value},
        success: function (data) {

            $('#products').html(data);


            //boxCheck($select);
        }
    });
}

function getSelectedId() {
    $select = new Array();
    console.log('new');
    $selected = $.makeArray($('input[name="selected_products[]"]:checked'));
    console.log($selected);
    $selected.forEach(function (selected, index) {
        if (!$select.includes(selected['id'])) ;
        $select.push(selected['id']);
    });
    console.log($select);
    sessionStorage.setItem("selected_id", $select);
    $select = $select.join('').split('');
    return $select;
}

function refreshPrice() {
    var bundle_base = calcPrice(0)[1];
    $('#price_warning').html('Lower than ' + bundle_base);
}


function load_cate_value(value) {
    $.ajax({
        url: "search/category",
        method: "GET",
        data: {category: value},
        success: function (data) {
            $('#cateValue').html(data);
        }
    });
}

$('#category').on('change', function () {
    var input = $("#category option:selected").val();
    console.log(input);
    load_cate_value(input);
    load_widget(input);
});


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

function load_widget(input) {
    $.ajax({
        url: "/search/load-widget",
        method: "GET",
        data: {products: input},
        success: function (data) {
            $('#widget').html(data);
            //alert('Loaded widget');
        }
    });
}
$(document).on('click', '#products-to-table', function (){
    console.log('clicked');
    var input = selected;
    console.log(input);
    if (input.length === 0) {
        $('#subTable').css("visibility", "hidden");
    }
    load_data_to_table(input);
    load_widget(input);
    //$('#bundle_style').attr("checked", "checked");
});

function getSelectedProducts() {
    var total = $('input[name="selected_products[]"]:checked').length;
    $.ajax({
        url: "/save",
        data: {total: total}
    });
    //console.log(total);
    var selectedId = [];
    $('input[name="selected_products[]"]:checked').each(function () {
        selectedId.push($(this).val());
    });
    //console.log(selectedId);
    return selectedId;
}

function Clear() {
    clearRadioGroup("RadioInputName");
}

function clearRadioGroup(GroupName) {
    var ele = document.getElementsByName(GroupName);
    for (var i = 0; i < ele.length; i++)
        ele[i].checked = false;
}

$.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
