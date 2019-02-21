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

                        <div class="form-group">
                            <select name="cateValue" id="cateValue" class="form-control"
                                    style="display: inline-block;width:185px;float:right">

                            </select>
                            <input type="text" class="form-control"
                                   style="display: inline-block;width:100px;float:right"
                                   placeholder="equals" disabled=""><select name="category" id="category"
                                                                            class="form-control"
                                                                            style="display: inline-block;width:auto;float:right">
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
    <script type="text/javascript" src="{{ asset('js/create-page.js') }}"></script>

@endsection
