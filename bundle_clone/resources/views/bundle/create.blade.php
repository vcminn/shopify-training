@extends('adminlte::layouts.app')
@section('contentheader_title', 'Create bundle')
@section('main-content')
    @php
        $store_id = session()->get('store_id');
    @endphp
    <form method="post" action="{{ route('bundles.store') }}" enctype="multipart/form-data">
        <div class="box box-default">
            <div class="box-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br/>
                @endif
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="internal_name" id="internal_name" class="form-control"
                           placeholder="Enter ...">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea type="text" rows="5" name="description" class="form-control"
                              placeholder="Enter ..."></textarea>
                </div>
            </div>
        </div>


        <div class="box box-default">
            <div class="box-body">
                <div class="form-group">
                    <label>Choose products</label>
                    <div class="box-body">
                        <button type="button" id="add-products" class="btn btn-default" data-toggle="modal"
                                data-target="#modal-default" onClick="getSelectedId();">Add
                            Products
                        </button>
                        <div id="msg" class="ajax_response">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" id="1" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="Clear();"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Select individual products</h4>
                    </div>
                    <div class="modal-body">
                        <p>Products not showing correctly? Sync with Shopify</p>
                        <div class="input-group input-group-md">
                            <input type="text" name="search_product" id="search_product" class="form-control"
                                   placeholder="Search...">
                            <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-primary">
                                <i class="fa fa-search"></i></button>
                        </span>
                        </div>

                        <div class="form-group">
                            <select name="cateValue" id="cateValue" class="form-control"
                                    style="display: inline-block;width:185px;float:right">

                            </select>
                            <input type="text" class="form-control"
                                   style="display: inline-block;width:100px;float:right"
                                   placeholder="equals" disabled=""><select name="category" id="category"
                                                                            class="form-control"
                                                                            style="display: inline-block;width:185px;float:right">
                                <option>--</option>
                                <option value="product_type">Product Types</option>
                                <option value="vendor">Vendors</option>
                            </select><input type="text" class="form-control"
                                            style="display: inline-block;width:100px;float:right" placeholder="Where"
                                            disabled="">
                        </div>
                    </div>

                    <div class="list-group" id="products">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Review Selecion
                        </button>
                        <button id="products-to-table" type="button" class="btn btn-primary" data-dismiss="modal">Save
                            Selection
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="box box-default">
            <div class="box-body">
                <div class="form-group">
                    <label>Setup</label>
                    <div class="box-body">
                        <table class="table" id="setup-products">

                        </table>
                        <table class="table">
                            <tr class="noBorder" id="subTable" style="visibility: hidden;">
                                <td></td>
                                <td></td>
                                <td colspan="2" align="right">Set discount percentage</td>
                                <td><input type="text" name="discount" id="discount_percent" placeholder="Enter ..."
                                           style="padding: 5px;" class="form-control"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="noBorder" id="subTable2" style="visibility: hidden;">
                                <td>
                                    <button type="button" id="add-products" class="btn btn-default" data-toggle="modal"
                                            data-target="#modal-default" onClick="getSelectedId();">Add
                                        Products
                                    </button>
                                </td>
                                <td></td>
                                <td colspan="2" align="right">Set discount bundle price</td>
                                <td>
                                    <input type="text" name="discount_price" id="discount_price" placeholder="Enter ..."
                                           style="padding: 5px;" class="form-control">
                                    <small id="price_warning"></small>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-body">
                <div class="form-group">
                    <label>Images</label>
                    <div class="box-body">
                        <input type="file" name="image" id="fileToUpload" onchange="preview(this.files[0]);">
                        <small>Add your own bundle image if there are more than 4 products.</small>
                        <div class="form-group">
                            <img id="blah" alt="your image" width="200" height="200"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-body">
                <label>Widget</label>
                <button id="sync" style="float:right" class="btn btn-secondary" type="button"
                        onClick="reload_widget();">
                    <i
                        class="fas fa-refresh"></i></button>
                <div class="form-group">
                    <label>Title</label>

                    <input type="text" name="widget_title" id="widget_title" class="form-control"
                           placeholder="Enter ...">
                    <small>Tell your customers about your deal. Eg: Buy 1 Get 1 FREE!</small>
                </div>
                <div class="form-group">
                    <label>Style</label><br>
                    <input type="radio" name="bundle_style" id="bundle_style" value="0"
                           onClick="load_style(this.value, this.checked);"> Basic bundle &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="bundle_style" value="1" onClick="load_style(this.value, this.checked);">
                    Percent saved
                    <br>
                    <input type="radio" name="image_style" id="image_style" value="0"
                           onClick="load_widget(this.value, this.checked);"> Combination &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="image_style" value="1" onClick="load_widget(this.value, this.checked);">
                    One image
                    <div class="container" style="width: auto; height:auto">
                        <div class="row justify-content-md-center">
                            <div class="col col-lg-6" style="border:solid rgba(0,0,0,0.47) 2px;" id="widget">

                            </div>
                        </div>
                        <div class="row row justify-content-md-center">
                            <div class="col align-self-center" id="style_announce">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="base_price" name="base_price">
        <input type="hidden" id="store_id" name="store_id" value="{{{$store_id}}}">
        <div class="modal-footer">
            <button type="button" class="btn btn-default">Discard
            </button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    <script type="text/javascript">
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
                $reg_price = parseFloat($("#price" + id).text());
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
            function load_discount_percent(base_price, discount_price) {
                $.ajax({
                    url: "{{route('show-percent')}}",
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
                    url: "{{route('show-price')}}",
                    method: "GET",
                    data: {price: value, index: index},
                    success: function (data) {
                        $('#discount-price' + id).html(data);
                    }
                });
            }

            $('#discount_price').keyup(function () {
                var input = $(this).val();
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
                    $('#discount_price').val((price[1]*(100-price[3])/100).toFixed(2));
                } else {
                    var price = calcPrice(0);
                    selected.forEach(function (id, index) {
                        load_discounted_price(price[0], id, index);
                        document.getElementById('base_price').value = price[1];
                    });
                }

            });
        });
    </script>
    <script type="text/javascript">
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
                    url: "{{route('load-style')}}",
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
                    url: "{{route('load-widget')}}",
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

            //console.log($.makeArray($('input[name="selected_products[]"]:checked')));

            function load_data(value) {
                // $select = getSelectedId();
                $.ajax({
                    url: "{{route('search-products')}}",
                    method: "GET",
                    data: {search: value},
                    success: function (data) {

                        $('#products').html(data);


                        //boxCheck($select);
                    }
                });
            }

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
        });

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

        function boxCheck(boxId) {
            boxId.forEach(function (id, index) {
                $('#' + id).attr('checked', true);
            });
        }
    </script>

    <script type="text/javascript">
        function refreshPrice() {
            var bundle_base = calcPrice(0)[1];
            $('#price_warning').html('Lower than ' + bundle_base);
        }

        $(document).ready(function () {
            function load_cate_value(value) {
                $.ajax({
                    url: "{{route('category')}}",
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
        });
        $(document).ready(function () {
            function load_data_to_table(value) {
                $.ajax({
                    url: "{{route('table-products')}}",
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
                    url: "{{route('load-widget')}}",
                    method: "GET",
                    data: {products: input},
                    success: function (data) {
                        $('#widget').html(data);
                        //alert('Loaded widget');
                    }
                });
            }

            $('#products-to-table').click(function () {
                console.log($.makeArray($('input[name="selected_products[]"]:checked')));
                var input = getSelectedProducts();
                console.log(input);
                if (input.length === 0) {
                    $('#subTable').css("visibility", "hidden");
                }
                load_data_to_table(input);
                load_widget(input);
                //$('#bundle_style').attr("checked", "checked");
            });
        });

        function getSelectedProducts() {
            var total = $('input[name="selected_products[]"]:checked').length;
            $.ajax({
                url: "{{route('save')}}",
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
    </script>
    <script>
        function Clear() {
            clearRadioGroup("RadioInputName");
        }

        function clearRadioGroup(GroupName) {
            var ele = document.getElementsByName(GroupName);
            for (var i = 0; i < ele.length; i++)
                ele[i].checked = false;
        }
    </script>
    <script type="text/javascript">

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});

    </script>

@endsection