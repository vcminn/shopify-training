@extends('layouts.app')

@section('content')
    @guest
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h3>Create a new account</h3>
                                <p class="text-muted">Get our 30-day free trial and start increasing your sales today</p>
                            </div>
                            <hr class="mb-4">
                            <form method="GET" action="{{ route('login.shopify') }}" aria-label="{{ __('Register') }}">
                                <div class="form-group">
                                    <label for="domain">Domain</label>

                                    <div class="input-group mb-3">
                                        <input id="domain" type="text" class="form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" name="domain" value="{{ old('domain') }}" placeholder="yourshop" aria-describedby="myshopify" required autofocus>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="myshopify">myshopify.com</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">Continue</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p class="text-center text-muted">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="flex-left position-ref full-height">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <ul>
                        <li><a href="/">Dashboard</a></li>
                        <li><a href="/freegift/cart">Freegifts on Cart</a></li>
                        <li><a href="/freegift/catalog">Freegifts on Catalog</a></li>
                        <li><a href="/freegift/report">Report</a></li>
                        <li><a class="active" href="/freegift/setting">Settings</a></li>
                    </ul>
                </div>
                <div class="col-md-3">

                </div>
            </div>/
        </div>
    @endguest
@endsection
<style>
    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 200px;
        background-color: #f1f1f1;
        border: 1px solid #555;
    }

    li a {
        display: block;
        color: #000;
        padding: 8px 16px;
        text-decoration: none;
    }

    li {
        text-align: center;
        border-bottom: 1px solid #555;
    }

    li:last-child {
        border-bottom: none;
    }

    li a.active {
        background-color: #4CAF50;
        color: white;
    }

    li a:hover:not(.active) {
        background-color: #555;
        color: white;
    }
</style>
