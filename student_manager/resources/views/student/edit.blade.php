@extends('layout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="card uper">
        <div class="card-header">
            Edit Share
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
                <form method="post" action="{{ route('students.store') }}">
                    <div class="form-group">
                        @csrf
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value={{$student->name}}>
                    </div>
                    <div class="form-group">
                        <label for="price">Date of birth :</label>
                        <input type="text" class="form-control" name="dob" value={{$student->dob}}>
                    </div>
                    <div class="form-group">
                        <label for="price">Phone number :</label>
                        <input type="text" class="form-control" name="phone_number" value={{$student->phone_number}}>
                    </div>
                    <div class="form-group">
                        <label for="price">City :</label>
                        <input type="text" class="form-control" name="city" value={{$student->city}}>
                    </div>
                    <div class="form-group">
                        <label for="price">Faculty :</label>
                        <input type="text" class="form-control" name="faculty" value={{$student->faculty}}>
                    </div>
                    <div class="form-group">
                        <label for="price">Class :</label>
                        <input type="text" class="form-control" name="class" value={{$student->class}}>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
        </div>
    </div>
@endsection
