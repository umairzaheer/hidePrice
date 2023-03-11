@extends('shopify-app::layouts.default')

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/teacher.css') }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body id="test">
    <a href="#openModal"><button class="warning">Add Teacher Record</button></a>
    <div id="openModal" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <h5>Add Teacher Data</h5>
            <div id="save_msgList"></div>


            <label for="">Full Name</label>
            <input type="text" required class="name">

            <label for="">Course</label>
            <input type="text" required class="course">

            <label for="">Email</label>
            <input type="text" required class="email">

            <label for="">Phone No</label>
            <input type="text" required class="phone">
            <button type="button" class="add_student">Save</button>
        </div>
    </div>
    <hr />

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="2"><b>Teachers Records</b></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th><b>ID</b></th>
                <th><b>Name</b></th>
                <th><b>Course</b></th>
                <th><b>Email</b></th>
                <th><b>Phone</b></th>
                <th><b>Edit</b></th>
                <th><b>Delete</b></th>
            </tr>
        </thead>

        <tbody>

            @foreach ($teachers as $teacher)
            <tr>
                <td><b>{{ $teacher->id }}</b></td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->course }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->phone }}</td>
                <td><a href="#editModal{{ $teacher->id }}"><button class="">Edit</button></a></td>
                <td><a href="#DeleteModal"><button value="{{$teacher->id}}" class="warning deletebtn">Delete</button></a></td>
            </tr>

            <div id="editModal{{ $teacher->id }}" class="modalDialog editModal">
                <a href="#close" title="Close" class="close">X</a>
                <h5 class="modal-title">Update Teacher Data</h5>
                <ul id="save_msgList"></ul>
                    <div class="test">
                        <input type="hidden" value="{{$teacher->id}}" id="teacher_id" class="teacher_id" />
                        <label for="">Full Name</label>
                        <input type="text" value="{{ $teacher->name }}" id="name1" required class="name">

                        <label for="">Course</label>
                        <input type="text" value="{{ $teacher->course }}" id="course1" required class="course">

                        <label for="">Email</label>
                        <input type="text" value="{{ $teacher->email }}" id="email1" required class="email">

                        <label for="">Phone No</label>
                        <input type="text" value="{{ $teacher->phone }}" id="phone1" required class="phone">
                        <button type="submit" class="update_teacher">Update</button>
                        <a href="#cancel" title="Close" class="cancel"><button>Cancel</button></a>
                    </div>
            </div>
            @endforeach
        </tbody>
    </table>
    {{$teachers->links()}}

    <!-- Delete Model -->
    <div id="DeleteModal" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <h5 class="modal-title" id="AddStudentModalLabel">Delete Teacher Data</h5>
            <ul id="save_msgList"></ul>
            <h4>Confirm Delete ?</h4>
            <input type="hidden" id="deleteing_id">
            <button class="delete_teacher">Delete</button>
            <a href="#cancel" title="Close" class="cancel"><button>Cancel</button></a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>
<script type="text/javascript" src="{{ URL::asset('js/teacher.js') }}"></script>

</html>