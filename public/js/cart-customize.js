$(document).ready(function () {
    $('table th').hover(()=>{
        $(this).css('cursor', 'pointer');
    }, function() {
        $(this).css('cursor','auto');
    });
    
    if ($("#customize_change_btn").prop("checked") == 1) {
        $(".hide-show-section").show();
    } else {
        $(".hide-show-section").hide();
    }

    if ($("#enable_merchant_msg").prop("checked") == 1) {
        $(".merchant-msg-hide-show-section").show();
    } else {
        $(".merchant-msg-hide-show-section").hide();
    }


    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on('click','ul.tabs li',function (e) {
        e.preventDefault();
        var tab_id = $(this).children().attr('href');
        var disabled= $(this).children().attr("disabled");
        jQuery('ul.tabs li').removeClass('active');
        jQuery('.tab-content').removeClass('current');
        jQuery('.tab-content').hide();
        jQuery(this).addClass('active');
        jQuery(tab_id).addClass('current');
        jQuery(tab_id).show();
      
    });


    $('#customize_change_btn').change(function () {
        if ($("#customize_change_btn").prop("checked") == 1) {
            $(".hide-show-section").show();
        } else {
            $(".hide-show-section").hide();
        }
    });

    $("#enable_merchant_msg").change(function () {
        if ($("#enable_merchant_msg").prop("checked") == 1) {
            $(".merchant-msg-hide-show-section").show();
        } else {
            $(".merchant-msg-hide-show-section").hide();
        }});

    $("#add-setting").submit(function (e) {
        e.preventDefault(); 
        var data = new FormData(this);
        //storing checkbox values in formData
        if ($("#enable_id").prop("checked") == 1) {
            data.append("enable_app", "1");
        } else {
            data.append("enable_app", "0");
        }

        if ($("#customize_change_btn").prop("checked") == 1) {
            data.append("customize_change_btn", "1");
        } else {
            data.append("customize_change_btn", "0");
        }
        

        if ($("#enable_merchant_msg").prop("checked") == 1) {
            data.append("enable_merchant_msg", "1");
        } else {
            data.append("enable_merchant_msg", "0");
        }

        if ($("#enable_customer_msg").prop("checked") == 1) {
            data.append("enable_customer_msg", "1");
        } else {
            data.append("enable_customer_msg", "0");
        }

        if ($("#enable_survey_question").prop("checked") == 1) {
            data.append("enable_survey_question", "1");
        } else {
            data.append("enable_survey_question", "0");
        }
           
        $.ajax({
            type: "POST",
            url: "/store-setting",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                // return(response);
                if(response.status == 200){
                    windowScroll();
                    successMessage('Settings Updated Successfully'); 
                } else{
                windowScroll();
                validationErrorSetting(response);
            }
            },
        });
    });
 
    var ruleApplyTo = $('select[name="rule_applied_to"] option:selected').val();
    if(ruleApplyTo=='product'|| ruleApplyTo=='collection')
    {
        $(".product-hide-show-section").show();
    }
    else{
        $(".product-hide-show-section").hide();
    }

    $('select[name="rule_applied_to"]').change(function(){
        let Label = $(this).val();
        if($(this).val() == "all")
        {
            $(".product-hide-show-section").hide();
        }else{
            $("#product-collection-label").text(Label.charAt(0).toUpperCase() + Label.slice(1));
            $("#product-collection-value").empty();
            $(".product-hide-show-section").show();
        }
    })
        //product and collection APIs to get their data
    $("#product-collection-value").select2({
        ajax: {
            url: "/get-all-products",
            data: function (params) {
                var type = $('select[name="rule_applied_to"] option:selected').val();
                var query = {
                    term: params.term,
                    type: type,
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

    $("#add-rule").submit(function (e) {
        e.preventDefault(); 
        var data = new FormData(this);
        if ($("#enable-rule").prop("checked") == 1) {
            data.append("enable_rule", "1");
        } else {
            data.append("enable_rule", "0");
        }
        $.ajax({
            type: "post",
            url: "/add-rule",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 200) {
                    $('#rule_id').val(response.ruleId);
                    windowScroll();
                    successMessage('Rule Added Successfully'); 
                }
                else{
                    windowScroll();
                    validationErrorSetting(response);
                }
            },
       });
    });   

            $(document).on("click", "#delete-rule", function () {
                var rule_id = $(this).val();
                $("#delete-id").val(rule_id);
                windowScroll();
                $('#DeleteModal').show();
                $("body").css('overflow', 'hidden');
                $("#DeleteModal").css('display','flex');
            });
            
            $(document).on("click", "#confirm-delete-rule", function (e) {
                e.preventDefault();
                var id = $("#delete-id").val();
                $.ajax({
                    type: "DELETE",
                    url: "/delete-rule/" + id,
                    dataType: "json",
                    success: function (data) {
                        $("tr#"+data.id).remove();
                        successMessage('Rule Deleted Successfully'); 
                        $("#DeleteModal").hide("");
                        $("body").css('overflow', 'auto');
                        $(".rule-data").empty();
                        if(data.rulesCount == 0){
                            location.reload();
                        }else{
                            $(".rule-data").load('load-rule-data');
                        }
                    },
                });
            });

            $(".delete-model-cancel-btn").on("click", function (e) {
                $('#DeleteModal').hide();
             });
             
             $(".delete-model-close-btn").on("click", function (e) {
                $('#DeleteModal').hide();
             });
             
             $("#update-rule").submit(function (e) {
                e.preventDefault(); 
                var data = new FormData(this);
                if ($("#enable-rule").prop("checked") == 1) {
                    data.append("enable_rule", "1");
                } else {
                    data.append("enable_rule", "0");
                }
                var appendProductionCollectionValue = data.getAll('product_collection_value[]');
                var id = $("#check-rule-id").val();
                $.ajax({
                    type: "post",
                    url: "/update-rule/" + id,
                    data: data,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status == 200) {
                            windowScroll();
                            $('#check_login_value').val(response.ruleId);
                            successMessage('Rule Updated Successfully');
                        } 
                        else {
                            windowScroll();
                            validationErrorSetting(response);
                        }
                    },
                    complete: function(){}
                });
            });
    
    $(document).on("keyup", '#search-rule' , function (e) {
        var value = $(this).val();
        $.ajax({
            type: "get",
            dataType: "json",
            url: "/pagination",
            data: {
                search: value,
            },
            success: function (response) {
                var data= response.data.data;
                if(response.data.data.length === 0){
                    $("#search-data").html("<tr><td colspan='3'> No Data Available</td></tr>");
                    $(".pagination").hide();
                }else{
                    $("#search-data").html("");
                    data.forEach(function (item) {
                        var products=[];
                        var finalArrayProducts=[];
                        var ruleAppliedTo='';
                        var span='';
                        if (item.enable_rule == 1) {
                            span = "<span class='tag green'>Active</span>";
                        } else {
                            span = "<span class='tag yellow'>Inactive</span>";
                        }
                        
                        if(item.rule_applied_to == 'all'){
                            ruleAppliedTo = 'All';
                        } else if(item.rule_applied_to == 'product'){
                            ruleAppliedTo = 'Product';
                            $.each(item.products, function(key, value) {
                                $.each(response.productTitles, function(key1, value1) {
                                    if(value.product_id == key1){
                                        finalArrayProducts = "<span>"+value1+"</span></br>";
                                        products.push(finalArrayProducts);
                                    }
                                });
                            });
                        }
                         else if(item.rule_applied_to == 'collection'){
                            ruleAppliedTo = 'Collection';
                            $.each(item.products, function(key, value) {
                                $.each(response.collectionTitles, function(key1, value1) {
                                    if(value.collection_id == key1){
                                        finalArrayProducts = "<span>"+value1+"</span></br>";
                                        products.push(finalArrayProducts);
                                    }
                                });
                            });
                        }
                          products=products.toString();
                          products=products.replace(/,/g, ""); 
                          $("#search-data").append(
                            "<tr id="+ item.id +">\
                            <td><span class= 'highlight-warning'>" + item.id + "</span></td>\
                            <td>" + item.rule_title + "</td>\
                            <td>" + span + "</td>\
                            <td>" + ruleAppliedTo + "</td>\
                            <td>" + products + "</td>\
                            <td><a href='edit-rule/"+item.id+"'><button value='" + item.id + "' class='secondary icon-edit edit-rule' id= 'edit-rule' data-id = '" +  item.id +"'></button></a>\
                            <button value='" + item.id + "' class='secondary icon-trash' id = 'delete-rule'></button></td>\
                            </tr>"
                            );
                            $("#search-data").next().css('display','none');
                            $("#pagination").html("");
                            $("#pagination").append(response['pagination']);
                        });
                    }
                },
                complete: function(){}
            });
        });
        
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault();
            var page = $(this).attr("href").split("page=")[1];
            fetch_data(page);
        });
                    function fetch_data(page) {
                        var searchValue = $("#search-rule").val();
                        $.ajax({
                            url: "/pagination",
                            method: "GET",
                            dataType: "json",
                            data: {
                                page: page,
                                 paginationSearch:searchValue,
                            },
                            success: function (response) {
                                var data= response.data.data;
                                var searchPaginationData;
                                var paginationButton;
                               
                                // if(response.searchPaginationData == "search-data-registration"){
                                    $("#search-data").html("");
                                    data.forEach(function (item) {
                                        var ruleAppliedTo='';
                                        var products=[];
                                        var finalArrayProducts=[];
                                        var span;
                                        if (item.enable_rule == 1) {
                                            span = "<span class='tag green'>Active</span>";
                                        } else {
                                            span = "<span class='tag yellow'>Inactive</span>";
                                        }
                                        if(item.rule_applied_to == 'all'){
                                            ruleAppliedTo = 'All';
                                            products='';
                                        } else if(item.rule_applied_to == 'product'){
                                            ruleAppliedTo = 'Product';
                                            $.each(item.products, function(key, value) {
                                                $.each(response.productTitles, function(key1, value1) {
                                                    if(value.product_id == key1){
                                                        finalArrayProducts = "<span>"+value1+"</span></br>";
                                                        products.push(finalArrayProducts);
                                                    }
                                                });

                                                        });
                                                    } else if(item.rule_applied_to == 'collection'){
                                                        ruleAppliedTo = 'Collection';
                                                        $.each(item.products, function(key, value) {
                                                            $.each(response.collectionTitles, function(key1, value1) {
                                                                if(value.collection_id == key1){
                                                                    finalArrayProducts = "<span>"+value1+"</span></br>";
                                                                    products.push(finalArrayProducts);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    products=products.toString();
                                                    products=products.replace(/,/g, ""); 
                                                    $("#search-data").append(
                                                        "<tr id="+ item.id +">\
                                                        <td><span class= 'highlight-warning'>" + item.id + "</span></td>\
                                                        <td>" + item.rule_title + "</td>\
                                                        <td>" + span + "</td>\
                                                        <td>" + ruleAppliedTo + "</td>\
                                                        <td>" + products + "</td>\
                                                        <td><a href='edit-rule/"+item.id+"' ><button value='" + item.id + "' class='secondary icon-edit edit-rule' id= 'edit-rule' data-id = '" +  item.id +"'></button></a>\
                                                        <button value='" + item.id + "' class='secondary icon-trash' id = 'delete-rule'></button></td>\
                                                        </tr>"
                                                        );
                                                        $("#search-data").next().css('display','none');
                                                        $("#pagination").html("");
                                                        $("#pagination").append(response['pagination']);
                                                    });
                                                },
                                                complete: function(){
                                                }
                                            });
                                        }
                                        
                                        function windowScroll() {
                                            window.scroll({
                                                top: 0,
                                                left: 0,
                                                behavior: "smooth",
                                            });
                                        }
                                        
                                        function validationErrorSetting(response){
                                            $.each(response.errors ,function(prefix, val){
                                                $('span.'+prefix+'_error').text(val[0]).css("color", "red").show();
                                                $('span.'+prefix+'_error').text(val[0]).fadeIn(100).delay(3000).hide(100);
                                            }); 
                                        }
                                        function successMessage(message) {
                                            jQuery(".sm-content").text(message);
                                            jQuery(".success-message").removeClass("default-hidden");
                                            window.setTimeout(function () {
                                                jQuery(".success-message").addClass("default-hidden");
                                            }, 2000);
                                        }
});