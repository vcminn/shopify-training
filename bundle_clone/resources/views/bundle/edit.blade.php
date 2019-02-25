@extends('adminlte::layouts.app')
@section('contentheader_title', 'Create bundle')
@section('main-content')
    @php
        $store_id = session()->get('store_id');
        $selected_ids = array();
        $quantities = array();
        foreach ($bundle_products as $key => $bundle_product){
            $selected_ids[] = $bundle_product->variant_id;
            $quantities[$selected_ids[$key]] = $bundle_product->quantity;
        }
        //var_dump($quantities);
    @endphp
    <form method="post" action="{{ route('bundles.update',$bundle->id) }}" enctype="multipart/form-data">
        @method('PATCH')
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
                           placeholder="Enter ..." value={{$bundle->internal_name}}>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea type="text" rows="5" name="description" class="form-control"
                              placeholder="Enter ...">{{$bundle->description}}</textarea>
                </div>
            </div>
        </div>


        <div class="box box-default">
            <div class="box-body">
                <div class="form-group">
                    <label>Choose products</label>
                    <div class="box-body">
                        <button type="button" id="add-products" class="btn btn-default" data-toggle="modal"
                                data-target="#modal-default">Add
                            Products
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default" data-backdrop="static">
            <div class="modal-dialog modal-lg">
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


                    </div>

                    <div class="list-group" id="products">

                    </div>

                    <div class="pagination" id="pagination">
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
                                           style="padding: 5px;" class="form-control" value="{{$bundle->discount}}">
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="noBorder" id="subTable2" style="visibility: hidden;">
                                <td>
                                    <button type="button" id="add-products" class="btn btn-default" data-toggle="modal"
                                            data-target="#modal-default">Add
                                        Products
                                    </button>
                                </td>
                                <td></td>
                                <td colspan="2" align="right">Set discount bundle price</td>
                                <td>
                                    <input type="text" name="discount_price" id="discount_price" placeholder="Enter ..."
                                           style="padding: 5px;" class="form-control"
                                           value="{{$bundle->discount_price}}">

                                    <small id="price_warning"></small>
                                </td>
                                <td>
                                    <button id="sync" style="float:right" class="btn btn-secondary" type="button"
                                            onClick="refreshPrice()"><i
                                            class="fas fa-refresh"></i>
                                    </button>
                                </td>
                                <td>

                                </td>
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
                        <input type="file" name="image" id="image" onchange="preview(this.files[0]);">
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
                    <i class="fas fa-refresh"></i></button>
                <div class="form-group">
                    <label>Title</label>

                    <input type="text" name="widget_title" id="widget_title" class="form-control"
                           placeholder="Enter ..." value={{$bundle->widget_title}}>
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
        <input type="hidden" id="base_price" name="base_price" value="{{$bundle->base_total_price}}">
        <input type="hidden" id="store_id" name="store_id" value="{{{$store_id}}}">
        <div class="modal-footer">
            <button type="button" class="btn btn-default">Discard
            </button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    <script type="text/javascript">
        var selected = new Array;
        var prices = new Array;
        var bundle_image = '{{ $bundle->image }}';
        var bundle_id = {{ $bundle->id }};

        function loadQuantity() {
            @php
                $selected_ids = array();
            $quantities = array();
            foreach ($bundle_products as $key => $bundle_product){
                $selected_ids[] = $bundle_product->variant_id;
                $quantities[$selected_ids[$key]] = $bundle_product->quantity;
            }
                $js_array = json_encode($quantities);
            echo "quantities = ". $js_array . ";";
            @endphp
                for (var id in quantities) {
            $("#quantity" + id).val(quantities[id]);
            console.log(id+':'+quantities[id]);
        }
        console.log(quantities);
        }

        $(document).ready(function () {
            $.ajax({
                url: "/get-bundle/" + bundle_id,
                method: "GET",
                success: function (data) {
                    load_data_to_table(data);
                    load_widget(data);
                    selected = data;
                }
            });
            load_style($('bundle_style').val(), true);
            $("input[name=bundle_style][value=" + {{$bundle->bundle_style}} +"]").attr('checked', 'checked');
            $("input[name=image_style][value=" + {{$bundle->image_style}} +"]").attr('checked', 'checked');
        });

        $(document).ready(function () {
            $.ajax({
                url: "/get-prices/" + bundle_id,
                method: "GET",
                success: function (data) {
                    prices = data;
                }
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/edit-page.js') }}"></script>
@endsection
