@extends('layout')

@section('content')

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">All students</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Phone number</th>
                    <th>City</th>
                    <th>Faculty</th>
                    <th>Class</th>
                    <th colspan="2">Action</th>
                </tr>
                @foreach($students as $student)
                    <tr>
                        <td>{{$student->id}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{$student->dob}}</td>
                        <td>{{$student->phone_number}}</td>
                        <td>{{$student->city}}</td>
                        <td>{{$student->faculty}}</td>
                        <td>{{$student->class}}</td>
                        <td><a href="student\{{$student->id}}" class="btn btn-primary">Edit</a></td>
                        <td>
                            <form action="delete\{{$student->id}}" method="get">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>


@endsection
