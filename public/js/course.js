$(document).ready(function () {
    //$(".myselect").select2();

    function fetchcourse() {
        $.ajax({
            type: "GET",
            url: "/fetch-courses",
            dataType: "html",
            success: function (response) {
                $("#course").empty();
                $("#course").html(response);
            },
        });
    }

    $(document).on("click", ".insert_course", function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "/insert-course",
            data: "",
            dataType: "html",
            success: function (response) {
                $("#new_record").empty();
                $("#new_record").html(response);
                $("#search").hide();
                $(".pagination").hide();
                $(".insert_course").hide();
            },
        });
    });

    $(".add_course").click(function (e) {
        e.preventDefault();
        var data = {
            name: $(".name").val(),
            code: $(".code").val(),
            description: $(".desc").val(),
            product: $(".myselect").val(),
        };
        $.ajax({
            type: "POST",
            url: "/courses",
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.status == 400) {
                    $("#success_message").show();
                    //$("#success_message").html("");
                    $("#success_message").addClass("alert error");
                    $.each(response.errors, function (key, err_value) {
                        $("#success_message").append(
                            "<li>" + err_value + "</li>"
                        );
                        setTimeout(function () {
                            $("#success_message").fadeOut("slow");
                        }, 2000);
                    });
                } else if (response.status == 404) {
                    $("#success_message").show();
                    $("#success_message").addClass("alert error");
                    $("#success_message").text(response.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                } else {
                    $("#success_message").show();
                    $("#success_message").addClass("alert success");
                    $("#success_message").text(response.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                }
            },
        });
    });

    $("tbody").on("click", ".edit", function () {
        var id = $(this).val();
        var product = $(".myselect").val();
        $.ajax({
            type: "GET",
            url: "/edit-course/" + id,
            data: {
                id: id,
                product: product,
            },
            success: function (res) {
                console.log(res);
                if (res.status == 404) {
                    $("#success_message").show();
                    $("#success_message").addClass("alert error");
                    $("#success_message").text(res.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                }
                $("#search").hide();
                $(".pagination").hide();
                $(".insert_course").hide();
                $("#new_record").html("");
                $("#new_record").html(res);
            },
        });
    });
    // $("#update_course").click(function (e) {
    $(document).on("click", "#update_course", function (e) {
        e.preventDefault();
        var id = $("#course_id").val();
        var product = $(".myselect").val();
        var data = {
            name: $(".name").val(),
            code: $(".code").val(),
            description: $(".desc").val(),
            product: product,
        };
        $.ajax({
            type: "PUT",
            url: "/update-course/" + id,
            data: data,
            success: function (response) {
                if (response.status == 400) {
                    $("#success_message").html('');
                    $("#success_message").show();
                    $("#success_message").addClass("alert error");
                    $.each(response.errors, function (key, err_value) {
                        $("#success_message").append(
                            "<ul><li>" + err_value + "</li></ul>"
                        );
                        setTimeout(function () {
                            $("#success_message").fadeOut("slow");
                        }, 2000);
                    });
                } else if (response.status == 404) {
                    $("#success_message").show();
                    $("#success_message").addClass("alert error");
                    $("#success_message").text(response.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                } else {
                    $("#success_message").show();
                    $("#success_message").addClass("alert success");
                    $("#success_message").text(response.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                }
            },
        });
    });

    $(document).on("click", ".deletebtn", function () {
        var stud_id = $(this).val();
        $("#deleteing_id").val(stud_id);
    });

    $(document).on("click", ".delete_course", function (e) {
        e.preventDefault();
        $(this).text("Deleting..");
        var id = $("#deleteing_id").val();
        $.ajax({
            type: "DELETE",
            url: "/delete-course/" + id,
            dataType: "json",
            success: function (response) {
                $("#success_message").show();
                $("#success_message").addClass("alert success");
                $("#success_message").text(response.message);
                setTimeout(function () {
                    $("#success_message").fadeOut("slow");
                }, 2000);
                $("#DeleteModal").hide("");
                // fetchcourse();
            },
        });
    });

    $("#search").on("keyup", function () {
        var value = $(this).val();
        $.ajax({
            type: "get",
            url: "/search",
            dataType: "json",
            data: {
                search: value,
            },
            success: function (response) {
                console.log(response);
                $("tbody").html("");
                $.each(response.courses.data, function (key, item) {

                    $("tbody").append(
                        "<tr>\
                <td><span class= 'highlight-warning'>" +
                            item.id +
                            "</span></td>\
                <td>" +
                            item.name +
                            "</td>\
                <td>" +
                            item.code +
                            "</td>\
                <td>" +
                            item.description +
                            '</td>\
                <td><button value="' +
                            item.id +
                            '" class="secondary icon-edit edit"></button></a>\
                <a href="#DeleteModal"><button value="' +
                            item.id +
                            '" class="secondary icon-trash deletebtn"></button></a></td>\
            </tr>'
                    );
                });
            },
        });
    });

    $(document).on("click", ".pagination a", function (event) {
        event.preventDefault();
        var page = $(this).attr("href").split("page=")[1];
        fetch_data(page);
        $("#pagination").hide();
    });

    function fetch_data(page) {
        $.ajax({
            url: "/search",
            method: "GET",
            data: {
                page: page,
            },
            success: function (data) {
                $("#new_record").html("");
                $("#new_record").html(data.paginate);
            },
        });
    }

    $("#product").select2({
        ajax: {
            url: "/get-all-products",
            data: function (params) {
                var query = {
                    term: params.term,
                    type: "public",
                };
                return query;
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: data,
                };
            },
        },
    });

    $(".add_product").click(function (e) {
        e.preventDefault();
        // var id = $("#course_id").val();
        var data = {
            product: $(".myselect").val(),
        };
        return console.log(data);
        $.ajax({
            type: "POST",
            url: "store-product",
            data: data,
            success: function (response) {
                //console.log(response);
                if (response.status == 400) {
                    $("#success_message").show();
                    $("#success_message").addClass("alert error");
                    $.each(response.errors, function (key, err_value) {
                        $("#success_message").append(
                            "<ul><li>" + err_value + "</li></ul>"
                        );
                    });
                } else {
                    $("#success_message").show();
                    $("#success_message").addClass("alert success");
                    $("#success_message").text(response.message);
                    setTimeout(function () {
                        $("#success_message").fadeOut("slow");
                    }, 2000);
                }
            },
        });
    });

  
});
