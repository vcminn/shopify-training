@extends('adminlte::layouts.app')
@section('contentheader_title', 'Product Bundle Groups')
@section('main-content')

    <div class="box">
        <div class="box-header">
            <a href="/bundle">
                <button style="float:right" class="btn btn-success" type="button"><i class="fas fa-plus"></i>&nbspAdd
                    Bundle
                </button>
            </a>
            <button id="sync" style="float:right" class="btn btn-secondary" type="button" onClick="sync();"><i
                    class="fas fa-refresh"></i>&nbspSync
            </button>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Active</th>
                    <th>Name</th>
                    <th>Visitors</th>
                    <th>Added to cart</th>
                    <th>Sales</th>
                    <th>Preview</th>
                    <th>Delete</th>
                </tr>
                @foreach($bundles as $key => $bundle)
                    <tr>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="active{{$bundle->id}}" class="active" id="{{$bundle->id}}"
                                       value="{{$bundle->active}}" onClick="changeState(this.id, this.checked);">
                                <span class="slider round">

                                </span>
                            </label>
                        </td>
                        <td><a href="bundle\{{$bundle->id}}">{{$bundle->internal_name}}</a></td>
                        <td>{{$bundle_response[$key]->visitors}}</td>
                        <td>{{$bundle_response[$key]->added_to_cart}}</td>
                        <td>{{$bundle_response[$key]->sales}}</td>
                        <td><a href=""><i class="fas fa-eye"></i></a></td>
                        <td>
                            <form action="delete\{{$bundle->id}}" method="get">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete this bundle?');"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    <script type="text/javascript" src="{{ asset('js/index-page.js') }}"></script>

@endsection
