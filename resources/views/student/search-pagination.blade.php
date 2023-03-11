
                    <div class="test">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($students as $student)
                            <tr>

                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->course }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td><button type="button" value="{{$student->id}}" class="btn btn-primary editbtn btn-sm">Edit</button></td>
                                <td><button type="button" value="{{$student->id}}" class="btn btn-danger deletebtn btn-sm">Delete</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$students->links()}}
                    </div>
                    