<!-- @extends('shopify-app::layouts.default')

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/course.css') }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="success_message"></div>
    <div class="paginate"> -->
        <table>
            <thead>
                <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Description</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                <tr>
                    <td><span class="highlight-warning">{{ $course->id }}</span></td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->code }}</td>
                    <td>{{ $course->description }}</td>
                    <td><button value="{{$course->id}}" class="secondary icon-edit edit"></button>
                        <a href="#DeleteModal"><button value="{{$course->id}}" class="secondary icon-trash deletebtn"></button></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- <div class="test"> -->
        {{$courses->links('pagination.pagination')}}
        <!-- </div> -->
    <!-- </div> -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- </body> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/course.js') }}"></script>

</html>