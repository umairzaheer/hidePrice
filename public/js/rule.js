$(document).ready(function () {
    if ($('select[name="categories"] option:selected').val() == "Products") {
        $(".hide-show-section").show();
    } else {
        $(".hide-show-section").hide();
    }
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

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
                return {
                    results: data,
                };
            },
        },
    });

    $('select[name="categories"]').change(function () {
        if ($('select[name="categories"] option:selected').val() == "Products") {
            $(".hide-show-section").show();
        } else {
            $(".hide-show-section").hide();
        }
    });

    $("form").submit(function (e) {
        e.preventDefault(); 
        $(".loader").fadeIn(100).delay(4000).hide(500);
        var data = new FormData(this);
        if ($("#enable_id").prop("checked") == 1) {
            data.append("enable_rule", "1");
        } else {
            data.append("enable_rule", "0");
        }
        
        var status;
        // var id = $("#save_ruule_id").val();
        // if ($("#enable_id").prop("checked") == 1) {
        //     status= 1;
        // } else { 
        //     status= 0;
        // }
        var product = $(".myselect").val();
        // var data = data
            // id: id,
            // status: status,
            // rule_title: $(".title").val(),
            // categories: $('select[name="categories"] option:selected').val(),
            // product: product,
        
           
        $.ajax({
            type: "POST",
            url: "/rules",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response.ruleId);
                $('#save_ruule_id').val(response.ruleId);
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
                    // $("#success_message").show();
                    $("#success_message").addClass("alert success");
                    $("#success_message").text(response.message);
                    $("#success_message").delay(3000).fadeIn(100).fadeOut(5000);
                }
            },
        });
    });

   
    $(".update_rule").click(function (e) {
        e.preventDefault();
        // $('.loader').show();
        // $(".loader").show(2000, function(){
        //     $('.loader').hide();
        // });
        $(".loader").fadeIn(100).delay(4000).hide(500);
      
        // var data = new FormData(this);
        var status;
        var id = $("#ruule_id").val();
        if ($("#enable_id").prop("checked") == 1) {
            status= 1;
        } else { 
            status= 0;
        }
        var product = $(".myselect").val();
        var data = {
            id: id,
            status: status,
            rule_title: $(".title").val(),
            categories: $('select[name="categories"] option:selected').val(),
            product: product,
        };
        
        $.ajax({
            type: "PUT",
            url: "/update-rule/" + id,
            data: data,
            success: function (response) {
                console.log(response);
                if (response.status == 400) {
                    $("#success_message").html("");
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
                    // $("#success_message").fadeOut(5000, function(){
                        
                    // });
                } else {
                    // $("#success_message").show();
                    $("#success_message").addClass("alert success");
                    $("#success_message").text(response.message);
                    $("#success_message").delay(3000).fadeIn(100).fadeOut(5000);
                    // setTimeout(function () {
                    //     $("#success_message").fadeOut("slow");
                    // }, 2000);
                    // $("#success_message").fadeOut("slow");
                    // $("#success_message").hide(2000);
                }
            },
        });
    });

    $(document).on("click", ".deletebtn", function () {
        // var rule_id = $(this).attr("data-id");
        var rule_id = $(this).val();
        $("#deleteing_id").val(rule_id);
    });

    $(document).on("click", ".delete_rule", function (e) {
        e.preventDefault();
        $(this).text("Deleting..");
        var id = $("#deleteing_id").val();
        // console.log(id);
        $.ajax({
            type: "DELETE",
            url: "/delete-rule/" + id,
            dataType: "json",
            success: function (response) {
                $("tr#"+response.id).remove();
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
                // console.log(response);
                $("#new_record").html("");
                $("#new_record").html(response);
                // var data = response.data;
                //   $("tbody").html("");
                // // console.log(response.data);
                //      data.forEach(function (item) {
                //          var productTitles = "";
                //             item.products.forEach(function (titles) {
                //                 if (titles.ruules_id == item.id) {
                //                   productTitles +=
                //                       '<span style="color: blue">' +
                //                          titles.product_title +
                //                              ", </span>";
                //                 }
                //             });
                //                var span;
                //                 if (item.rule_status == 1) {
                //                  span = "<span class='tag green'>Active</span>";
                //                 } else {
                //                    span = "<span class='tag yellow'>Inactive</span>";
                //                 }    
                //                  var removelastcomma = productTitles.lastIndexOf(',') === productTitles.length - 1 ? productTitles.slice(0, -1) : productTitles;
                //             //  return console.log(removelastcomma);
                //             $("tbody").append(
                //                 "<tr>\
                //                  <td><span class= 'highlight-warning'>" + item.id + "</span></td>\
                //                  <td>" + span + "</td>\
                //                  <td>" + item.rule_title + "</td>\
                //                  <td>" + item.rule_category + "</td>\
                //                  <td>" + removelastcomma + '</td>\
                //                  <td><a href="edit-rule/'+item.id+'" ><button value="' + item.id + '" class="secondary icon-edit edit"></button></a>\
                //                  <a href="#DeleteModal"><button value="' + item.id + '" class="secondary icon-trash deletebtn"></button></a></td>\
                //                 </tr>'
                //             );
                //      });
             },
        });
    });

    $(".pagination a").click( function (event) {
        event.preventDefault();
        var page = $(this).attr("href").split("page=")[1];
        fetch_data(page);
        $("#pagination").hide();
    });

    function fetch_data(page) {
        $.ajax({
            url: "/pagination",
            method: "GET",
            data: {
                page: page,
            },
            success: function (response) {
                // console.log(response);
                $("#pagination-data").html("");
                $("#pagination-data").html(response);

                // data.forEach(function (item) {
                //     var productTitles = "";
                //     item.products.forEach(function (titles) {
                //         if (titles.ruules_id == item.id) {
                //             productTitles +=
                //                 '<span style="color: blue">' +
                //                 titles.product_title +
                //                 ", </span>";
                //         }
                //     });
                //     var span;
                //     if (item.rule_status == 1) {
                //         span = "<span class='tag green'>Active</span>";
                //     } else {
                //         span = "<span class='tag yellow'>Inactive</span>";
                //     }
                //     $("tbody").append(
                //         "<tr>\
                //         <td><span class= 'highlight-warning'>" +
                //             item.id +
                //             "</span></td>\
                //         <td>" +
                //             span +
                //             "</td>\
                //         <td>" +
                //             item.rule_title +
                //             "</td>\
                //         <td>" +
                //             item.rule_category +
                //             "</td>\
                //         <td>" +
                //             productTitles +
                //             '</td>\
                //         <td><button value="' +
                //             item.id +
                //             '" class="secondary icon-edit edit"></button></a>\
                //             <a href="#DeleteModal"><button value="' +
                //             item.id +
                //             '" class="secondary icon-trash deletebtn"></button></a></td>\
                //     </tr>'
                //     );
                // });
            },
        });
    }
});
