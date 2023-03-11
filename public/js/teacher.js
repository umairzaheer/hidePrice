function fetchteacher() {
    $.ajax({
        type: "GET",
        url: "/fetch-teachers",
        dataType: "json",
        success: function(response) {
            $('tbody').html("");
            $.each(response.teachers.data, function(key, item) {
                $('tbody').append('<tr>\
                <td>' + item.id + '</td>\
                <td>' + item.name + '</td>\
                <td>' + item.course + '</td>\
                <td>' + item.email + '</td>\
                <td>' + item.phone + '</td>\
                <td><a href="#editModal{{ $teacher->id }}"><button value="' + item.id + '"  class="">Edit</button></a></td>\
                <td><button value="' + item.id + '" class="warning deletebtn">Delete</button></a></td>\
            \</tr>');
            });
        }
    });
}

$(document).on('click', '.add_student', function(e) {
    e.preventDefault();

    var data = {
        'name': $('.name').val(),
        'course': $('.course').val(),
        'email': $('.email').val(),
        'phone': $('.phone').val(),
    }

    $.ajax({
        type: "POST",
        url: "/teachers",
        data: data,
        dataType: "json",
        success: function(response) {
            // console.log(response);
            if (response.status == 400) {
                $('#save_msgList').html("");
                $('#save_msgList').addClass('alert danger');
                $.each(response.errors, function(key, err_value) {
                    $('#save_msgList').append('<dl><dt>' + err_value + '</dt></dl>');
                });
                $('.add_student').text('Save');
            } else {
                $('#save_msgList').html("");
                $('#success_message').addClass('alert success');
                $('#success_message').text(response.message);
                $('.add_student').text('Save');
                $('#openModal').hide();
                fetchteacher();
            }
        }
    });
});



// $(document).on('click', '.editbtn', function(e) {
//     e.preventDefault();
//     //var teacher_id = $(this).val();
//     $.ajax({
//         type: "GET",
//         url: "/edit-teacher/" + teacher_id,
//         success: function(response) {
//             if (response.status == 404) {
//                 $('#success_message').addClass('alert success');
//                 $('#success_message').text(response.message);
//             } else {
//                  //console.log(response.teacher.name);
//                 $('#name').val(response.teacher.name);
//                 $('#course').val(response.teacher.course);
//                 $('#email').val(response.teacher.email);
//                 $('#phone').val(response.teacher.phone);
//                 $('#teacher_id').val(teacher_id);
//                 $('#edit').append('<a href = "#editModal"></a>');
                
            
                
//             }
//         }
//     });
//     $('.btn-close').find('input').val('');
// });



$(document).on('click', '.update_teacher', function(e) {
    e.preventDefault();
    var id = ($(this).parent(".test").find(".teacher_id").val());
     console.log(id);
    var data = {
        'name': $('#name1').val(),
        'course': $('#course1').val(),
        'email': $('#email1').val(),
        'phone': $('#phone1').val(),
    }

    $.ajax({
        type: "PUT",
        url: "/update-teacher/" + id,
        data: data,
        dataType: "json",
        success: function(response) {
            if (response.status == 400) {
                $('#update_msgList').html("");
                $('#update_msgList').addClass('alert danger');
                $.each(response.errors, function(key, err_value) {
                    $('#update_msgList').append('<li>' + err_value +
                        '</li>');
                });
                $('.update_teacher').text('Update');
            } else {
                $('#update_msgList').html("");
                $('#success_message').addClass('alert success');
                $('#success_message').text(response.message);
                $('#editModal').find('input').val('');
                $('.update_teacher').text('Update');
                $('.editModal').hide();
                fetchteacher();
            }
        }
    });

});

$(document).on('click', '.deletebtn', function() {

    var stud_id = $(this).val();
    $('#deleteing_id').val(stud_id);
});

$(document).on('click', '.delete_teacher', function(e) {
    e.preventDefault();

    $(this).text('Deleting..');
    var id = $('#deleteing_id').val();

    $.ajax({
        type: "DELETE",
        url: "/delete-teacher/" + id,
        dataType: "json",
        success: function(response) {
            if (response.status == 404) {
                $('#success_message').addClass('alert success');
                $('#success_message').text(response.message);
                $('.delete_student').text('Yes Delete');
            } else {
                $('#success_message').html("");
                $('#success_message').addClass('alert success');
                $('#success_message').text(response.message);
                $('.delete_student').text('Yes Delete');
                $('#DeleteModal').hide('');
                fetchteacher();
            }
        }
    });
});
$('#search').on('keyup', function() {
    var value = $(this).val();
    $.ajax({
        type: 'get',
        url:"/search",
        dataType: 'json',
        data: {
            search: value,
        },
        success: function(response) {
            // console.log(response.students.data);
            // $('tbody').empty();
            // $('tbody').html(response.students.data);
            // console.log(response.pagi);
            $("#test").empty();
            $("#test").html(response.paginate);
        }
    });
});

// // $(document).on('click', '.pagination a', function(event) {
// //     event.preventDefault();
// //     var page = $(this).attr('href').split('page=')[1];
// //     fetch_data(page)
// // });

// // function fetch_data(page) {
// //     $.ajax({
// //         url: "{{ url('search') }}",
// //         method: "GET",
// //         data: {
// //             page: page
// //         },
// //         success: function(data) {
// //             $("#test").html(data.pagi);
// //         }
// //     });
// }

