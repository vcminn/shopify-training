<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Bundle </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="yytrKWgt7iVjrrB9xUm2z64VhGP39CI6JRftJD5c">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="/css/all.css" rel="stylesheet" type="text/css"/>
    <link href="https://bundle.local/css/fSelect.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        /* Pagination links */
        .pagination a {
            color: black;
            border: 1px solid #ddd; /* Gray */
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }

        /* Style the active/current link */
        .pagination a.active {
            background-color: dodgerblue;
            color: white;
        }

        /* Add a grey background color on mouse-over */
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        tr.noBorder td {
            border-style: hidden;
        }

        .example-modal .modal {
            position: relative;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            display: block;
            z-index: 1;
        }

        .example-modal .modal {
            background: transparent !important;
        }

        .list-item-header {
            width: 40px;
            height: 40px;
        }

        .list-item-body, {
            position: relative;
            display: inline-block;
        }

        .list-item-image-container {
            width: 40px;
            height: 40px;
        }

        .list-item-image {
            box-sizing: border-box;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 10%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img, svg {
            border-style: none;
            max-width: 100%;
            vertical-align: middle;
        }

        .selector-item-primary-text {
            font-weight: bold;
            line-height: 20px;
        }

        .selector-item.clickable {
            cursor: pointer;
        }

        .list-item-foot, .list-item-head, .list-item-part {
            position: relative;
            display: inline;
            text-align: right;
        }

        .list-group-item {
            user-select: none;
        }

        .list-group input[type="checkbox"] {
            display: none;

        }

        .list-group input[type="checkbox"] + .list-group-item {
            cursor: pointer;
        }

        .list-group input[type="checkbox"] + .list-group-item:before {
            content: "\2713";
            color: transparent;
            font-weight: bold;
            margin-right: 1em;
        }

        .list-group input[type="checkbox"]:disabled + .list-group-item:before {
            content: "\2713";
            color: gray;
            font-weight: bold;
            margin-right: 1em;
        }

        .list-group input[type="checkbox"]:checked + .list-group-item {
            background-color: #0275D8;
            color: #FFF;
        }

        .list-group input[type="checkbox"]:checked + .list-group-item:before {
            color: inherit;
        }

        .collapsible {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .collapsible:hover {
            background-color: #ccc;
        }

        /* Style the collapsible content. Note: hidden by default */
        .collapse {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f1f1f1;
        }
    </style>
    <script>
        //See https://laracasts.com/discuss/channels/vue/use-trans-in-vuejs
        window.trans = {
            "auth": {
                "failed": "These credentials do not match our records.",
                "throttle": "Too many login attempts. Please try again in :seconds seconds."
            },
            "pagination": {"previous": "&laquo; Previous", "next": "Next &raquo;"},
            "passwords": {
                "password": "Passwords must be at least six characters and match the confirmation.",
                "reset": "Your password has been reset!",
                "sent": "We have e-mailed your password reset link!",
                "token": "This password reset token is invalid.",
                "user": "We can't find a user with that e-mail address."
            },
            "validation": {
                "accepted": "The :attribute must be accepted.",
                "active_url": "The :attribute is not a valid URL.",
                "after": "The :attribute must be a date after :date.",
                "after_or_equal": "The :attribute must be a date after or equal to :date.",
                "alpha": "The :attribute may only contain letters.",
                "alpha_dash": "The :attribute may only contain letters, numbers, dashes and underscores.",
                "alpha_num": "The :attribute may only contain letters and numbers.",
                "array": "The :attribute must be an array.",
                "before": "The :attribute must be a date before :date.",
                "before_or_equal": "The :attribute must be a date before or equal to :date.",
                "between": {
                    "numeric": "The :attribute must be between :min and :max.",
                    "file": "The :attribute must be between :min and :max kilobytes.",
                    "string": "The :attribute must be between :min and :max characters.",
                    "array": "The :attribute must have between :min and :max items."
                },
                "boolean": "The :attribute field must be true or false.",
                "confirmed": "The :attribute confirmation does not match.",
                "date": "The :attribute is not a valid date.",
                "date_equals": "The :attribute must be a date equal to :date.",
                "date_format": "The :attribute does not match the format :format.",
                "different": "The :attribute and :other must be different.",
                "digits": "The :attribute must be :digits digits.",
                "digits_between": "The :attribute must be between :min and :max digits.",
                "dimensions": "The :attribute has invalid image dimensions.",
                "distinct": "The :attribute field has a duplicate value.",
                "email": "The :attribute must be a valid email address.",
                "exists": "The selected :attribute is invalid.",
                "file": "The :attribute must be a file.",
                "filled": "The :attribute field must have a value.",
                "gt": {
                    "numeric": "The :attribute must be greater than :value.",
                    "file": "The :attribute must be greater than :value kilobytes.",
                    "string": "The :attribute must be greater than :value characters.",
                    "array": "The :attribute must have more than :value items."
                },
                "gte": {
                    "numeric": "The :attribute must be greater than or equal :value.",
                    "file": "The :attribute must be greater than or equal :value kilobytes.",
                    "string": "The :attribute must be greater than or equal :value characters.",
                    "array": "The :attribute must have :value items or more."
                },
                "image": "The :attribute must be an image.",
                "in": "The selected :attribute is invalid.",
                "in_array": "The :attribute field does not exist in :other.",
                "integer": "The :attribute must be an integer.",
                "ip": "The :attribute must be a valid IP address.",
                "ipv4": "The :attribute must be a valid IPv4 address.",
                "ipv6": "The :attribute must be a valid IPv6 address.",
                "json": "The :attribute must be a valid JSON string.",
                "lt": {
                    "numeric": "The :attribute must be less than :value.",
                    "file": "The :attribute must be less than :value kilobytes.",
                    "string": "The :attribute must be less than :value characters.",
                    "array": "The :attribute must have less than :value items."
                },
                "lte": {
                    "numeric": "The :attribute must be less than or equal :value.",
                    "file": "The :attribute must be less than or equal :value kilobytes.",
                    "string": "The :attribute must be less than or equal :value characters.",
                    "array": "The :attribute must not have more than :value items."
                },
                "max": {
                    "numeric": "The :attribute may not be greater than :max.",
                    "file": "The :attribute may not be greater than :max kilobytes.",
                    "string": "The :attribute may not be greater than :max characters.",
                    "array": "The :attribute may not have more than :max items."
                },
                "mimes": "The :attribute must be a file of type: :values.",
                "mimetypes": "The :attribute must be a file of type: :values.",
                "min": {
                    "numeric": "The :attribute must be at least :min.",
                    "file": "The :attribute must be at least :min kilobytes.",
                    "string": "The :attribute must be at least :min characters.",
                    "array": "The :attribute must have at least :min items."
                },
                "not_in": "The selected :attribute is invalid.",
                "not_regex": "The :attribute format is invalid.",
                "numeric": "The :attribute must be a number.",
                "present": "The :attribute field must be present.",
                "regex": "The :attribute format is invalid.",
                "required": "The :attribute field is required.",
                "required_if": "The :attribute field is required when :other is :value.",
                "required_unless": "The :attribute field is required unless :other is in :values.",
                "required_with": "The :attribute field is required when :values is present.",
                "required_with_all": "The :attribute field is required when :values are present.",
                "required_without": "The :attribute field is required when :values is not present.",
                "required_without_all": "The :attribute field is required when none of :values are present.",
                "same": "The :attribute and :other must match.",
                "size": {
                    "numeric": "The :attribute must be :size.",
                    "file": "The :attribute must be :size kilobytes.",
                    "string": "The :attribute must be :size characters.",
                    "array": "The :attribute must contain :size items."
                },
                "starts_with": "The :attribute must start with one of the following: :values",
                "string": "The :attribute must be a string.",
                "timezone": "The :attribute must be a valid zone.",
                "unique": "The :attribute has already been taken.",
                "uploaded": "The :attribute failed to upload.",
                "url": "The :attribute format is invalid.",
                "uuid": "The :attribute must be a valid UUID.",
                "custom": {"attribute-name": {"rule-name": "custom-message"}},
                "attributes": []
            },
            "adminlte_lang_message": {
                "logged": "You are logged in!",
                "someproblems": "There were some problems with your input.",
                "siginsession": "Sign in to start your session",
                "remember": "Remember Me",
                "buttonsign": "Sign In",
                "forgotpassword": "I forgot my password",
                "registermember": "Register a new membership",
                "terms": "I agree to the terms",
                "conditions": "Terms and conditions",
                "register": "Register",
                "login": "Login",
                "membership": "I already have a membership",
                "passwordclickreset": "Click here to reset your password:",
                "signGithub": "Sign in using Github",
                "signFacebook": "Sign in using Facebook",
                "signTwitter": "Sign in using Twitter",
                "signGoogle+": "Sign in using Google+",
                "signLinkedin": "Sign in using Linkedin",
                "sendpassword": "Send Password Reset Link",
                "passwordreset": "Reset password",
                "pagenotfound": "Page not found",
                "404error": "404 Error Page",
                "notfindpage": "We could not find the page you were looking for.",
                "mainwhile": "Meanwhile, you may",
                "returndashboard": "return to dashboard",
                "usingsearch": "or try using the search form.",
                "search": "Search",
                "servererror": "Server Error",
                "500error": "500 Error Page",
                "somethingwrong": "Something went wrong.",
                "wewillwork": "We will work on fixing that right away.",
                "serviceunavailable": "Service unavailable",
                "503error": "503 Error Page",
                "level": "Level",
                "here": "Here",
                "recentactivity": "Recent Activity",
                "descriptionpackage": "A Laravel 5 package that switchs default Laravel scaffolding\/boilerplate to AdminLTE template",
                "createdby": "Created by",
                "seecode": "See code at",
                "online": "Online",
                "home": "Home",
                "header": "HEADER",
                "anotherlink": "Another Link",
                "multilevel": "Multilevel",
                "linklevel2": "Link in level2",
                "birthday": "Langdon's Birthday",
                "birthdaydate": "Will be 23 on April 24th",
                "progress": "Tasks Progress",
                "customtemplate": "Custom Template Design",
                "statstab": "Stats Tab Content",
                "generalset": "General Settings",
                "reportpanel": "Report panel usage",
                "checked": "checked",
                "informationsettings": "Some information about this general settings option",
                "togglenav": "Toggle navigation",
                "tabmessages": "You have 4 messages",
                "supteam": "Support Team",
                "awesometheme": "Why not buy a new awesome theme?",
                "allmessages": "See All Messages",
                "notifications": "You have 10 notifications",
                "newmembers": "5 new members joined today",
                "viewall": "View all",
                "tasks": "You have 9 tasks",
                "alltasks": "View all tasks",
                "desbuttons": "Design some buttons",
                "complete": "Complete",
                "membersince": "Member since",
                "followers": "Followers",
                "sales": "Sales",
                "friends": "Friends",
                "profile": "Profile",
                "signout": "Sign out",
                "landingdescription": "Laravel 5 package that switchs default Laravel scaffolding\/boilerplate to AdminLTE template with Bootstrap 3.0 and Pratt Landing page",
                "landingdescriptionpratt": "Acacha AdminLTE Laravel package template Landing page - Using Pratt",
                "description": "Description",
                "showcase": "Showcase",
                "contact": "Contact",
                "laravelpackage": "5 package that switchs default Laravel",
                "to": "to",
                "templatewith": "template with",
                "and": "and",
                "gedstarted": "Get Started!",
                "amazing": "Amazing admin template",
                "basedadminlte": "Based on adminlte bootstrap theme",
                "awesomepackaged": "Awesome packaged...",
                "by": "by",
                "at": "at",
                "readytouse": "ready to use with Laravel!",
                "designed": "Designed To Excel",
                "community": "Community",
                "see": "See",
                "githubproject": "Github project",
                "post": "post",
                "issues": "issues",
                "pullrequests": "Pull requests",
                "schedule": "Schedule",
                "monitoring": "Monitoring",
                "whatnew": "What's New?",
                "features": "Some Features",
                "design": "First Class Design",
                "retina": "Retina Ready Theme",
                "support": "Awesome Support",
                "responsive": "Responsive Design",
                "screenshots": "Some Screenshots",
                "address": "Address",
                "dropus": "Drop Us A Line",
                "yourname": "Your Name",
                "emailaddress": "Email Address",
                "enteremail": "Enter Email",
                "yourtext": "Your Text",
                "submit": "SUBMIT",
                "email": "Email",
                "username": "Username",
                "password": "Password",
                "retypepassword": "Retype password",
                "fullname": "Full Name",
                "registererror": "Error registering!",
                "loginerror": "Error loging!",
                "loggedin": "Logged in!",
                "entering": "Entering...",
                "registered": "User Registered!"
            }
        }    </script>
</head>

<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="skin-blue sidebar-mini">
<div id="app" v-cloak>
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="https://bundle.local/home" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Smart</b> Bundle</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->

                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu" id="user_menu"
                            style="max-width: 280px;white-space: nowrap;">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               style="max-width: 280px;white-space: nowrap;overflow: hidden;overflow-text: ellipsis">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs" data-toggle="tooltip" title="Erado">Erado</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <p>
                                        <span data-toggle="tooltip" title="Erado">Erado</span>
                                    </p>
                                </li>
                                <!-- Menu Body -->

                                <!-- Menu Footer-->
                                <li class="user-footer">

                                    <div class="pull-right">
                                        <a href="https://bundle.local/logout" class="btn btn-default btn-flat"
                                           id="logout"
                                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                            Sign out
                                        </a>

                                        <form id="logout-form" action="https://bundle.local/logout" method="POST"
                                              style="display: none;">
                                            <input type="hidden" name="_token"
                                                   value="yytrKWgt7iVjrrB9xUm2z64VhGP39CI6JRftJD5c">
                                            <input type="submit" value="logout" style="display: none;">
                                        </form>

                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">


                <!-- /.search form -->

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <!-- Optionally, you can add icons to the links -->
                    <li class="active"><a href="https://bundle.local/home"><span>Product Bundles</span></a></li>

                </ul><!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Create bundle
                    <small></small>
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Your Page Content Here -->
                <form method="post" action="https://bundle.local/bundles" enctype="multipart/form-data">
                    <div class="box box-default">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="yytrKWgt7iVjrrB9xUm2z64VhGP39CI6JRftJD5c">
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
                                <table class="modal-body">
                                    <p>Products not showing correctly? Sync with Shopify</p>
                                    <div class="input-group input-group-md">
                                        <input type="text" name="search_product" id="search_product"
                                               class="form-control"
                                               placeholder="Search...">
                                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-primary">
                                <i class="fa fa-search"></i></button>
                        </span>
                                    </div>
                                </table>
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
                                                        style="display: inline-block;width:100px;float:right"
                                                        placeholder="Where"
                                                        disabled="">
                                    </div>

                                    <table class="table" id="products">
                                    </table>
                                    <div id="pagination">
                                    </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Review Selecion
                                </button>
                                <button id="products-to-table" type="button" class="btn btn-primary"
                                        data-dismiss="modal">Save
                                    Selection
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
        </div>

        <div class="box box-default" id="setup">
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
        <input type="hidden" id="store_id" name="store_id" value="1">
        <div class="modal-footer">
            <button type="button" class="btn btn-default">Discard
            </button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>


        <script type="text/javascript" src="https://bundle.local/js/create-page.js"></script>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon&#039;s Birthday</h4>
                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul><!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>
                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul><!-- /.control-sidebar-menu -->

            </div><!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>
                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked/>
                        </label>
                        <p>
                            Some information about this general settings option
                        </p>
                    </div><!-- /.form-group -->
                </form>
            </div><!-- /.tab-pane -->
        </div>
    </aside><!-- /.control-sidebar

<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            <a href="https://github.com/acacha/adminlte-laravel"></a><b>admin-lte-laravel</b></a>. A Laravel 5 package
            that switchs default Laravel scaffolding/boilerplate to AdminLTE template
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2017 <a href="http://acacha.org">Acacha.org</a>.</strong> Created by <a
            href="http://acacha.org/sergitur">Sergi Tur Badenas</a>. See code at <a
            href="https://github.com/acacha/adminlte-laravel">Github</a>
    </footer>

</div><!-- ./wrapper -->
</div>
<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->

<script src="https://bundle.local/js/app.js" type="text/javascript"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->

</body>
</html>
