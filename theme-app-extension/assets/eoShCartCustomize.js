var domain = Shopify.shop;
var baseUrl = "https://sf-cartcustomizer.extendons.com/";
var variantIds = [];
var selectedVariantId = "";
var previousVariantId = "";
var previousQty = "";
var collectionHandles = [];
var eoShopMoney='';
var newArray = [];
var ajaxCallOnCartPage=false;
var changeQtyOfPopUp=false;
var themeId = Shopify.theme.theme_store_id;
var eoAddToCartContainer = ["button.addtocart-button-active", "button.product-form__add-button", 'button[type="submit"][name="add"]', ".singleCart", "#AddToCart-product-template", "#AddToCart", "#addToCart-product-template", ".product__add-to-cart-button", ".product-form__cart-submit", ".add-to-cart", ".cart-functions > button", ".productitem--action-atc", ".product-form--atc-button", ".product-menu-button-atc", ".product__add-to-cart", ".product-add", ".add-to-cart-button", "#addToCart", ".product-detail__form__action > button", ".product-form-submit-wrap > input", ".product-form input[type=\"submit\"]", "input.submit", ".add_to_cart", ".product-item-quick-shop", 'button#add-to-cart', ".productForm-submit", ".add-to-cart-btn", ".product-single__add-btn", ".quick-add--add-button", ".product-page--add-to-cart", ".addToCart", ".product-form .form-actions", ".button.add", ".btn-cart", "button#add", ".addtocart", ".AddtoCart", ".product-add input.add", "button#purchase", "form[action=\"\/cart\/add\"] button[type=\"submit\"]", ".product__form button[type=\"submit\"]", "#AddToCart--product-template", ".cws_atc_form", ".sc-pcjuG", 'form[action*="cart/add"] input[type="submit"]', "button.button--add-to-cart", '[data-action="add-to-cart"]', '[data-pf-type="ProductATC"]:first']; 
var eoAddToCartSelector = eoAddToCartContainer.toString();
(function () {
    var loadScript = function (url, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        if (script.readyState) {
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {
            script.onload = function () {
                callback();
            };
        }
        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
    };
    var eoShCartCustomize = function ($)
    {
        $(document).ready(function () {
            let pageUrl = $(location).attr("href");
            if (window.location.pathname.indexOf('products') > -1){
                eventRemoveFromAddToCartBtn();
                $('body').delegate('.eoshCart_button', 'click', function(event){ 
                    event.preventDefault();
                    event.stopPropagation();
                    console.log("event stopped");
                    var form=$('[action="/cart/add"]').last();
                    form.submit();
                });
            }
            if (pageUrl.includes("/cart")) {
                var cartItems = getCart();
                var cartLineItems = cartItems.items;
                if (cartLineItems.length > 0) {
                    var rules = getRuleData();
                    console.log(rules);
                    if(rules.allProductCollection == false){
                      //match product ids of cart items with db products ids and place those matched ids in variantIds variable
                        if(rules.productIds.length > 0){
                            $.each(cartLineItems, function (key, cartProductId) {
                                  //below if condition is to check whether product has a variant or not
                                  if(!cartProductId.product_has_only_default_variant){
                                $.each(rules.productIds, function (key, dbProductId) {
                                    if (cartProductId.product_id == dbProductId) {
                                        variantIds.push({
                                            id: cartProductId.id,
                                            handle: cartProductId.handle,
                                            quantity: cartProductId.quantity,
                                            image:cartProductId.image,
                                            priceforPopup:cartProductId.price,
                                        });
                                    }
                                });
                            }
                            });
                        }
                        //extract all products ids using collections id and then 'handle' and match those product ids with cart items 
                        if (rules.collectionIds.length > 0) {
                            $.each(rules.collectionIds, function (key, dbcollectionId) {
                                $.ajax({
                                    url: `/search/suggest.json?q=id:${dbcollectionId}&resources[type]=collection`,
                                    type: 'GET',
                                    dataType: 'json',
                                    async: false,
                                    crossDomain: true,
                                    success: function (res) {
                                        collectionHandles.push(
                                            res.resources.results.collections[0].handle
                                        );
                                    },
                                });
                            });
                            if(collectionHandles.length > 0){
                                $.each(collectionHandles, function (key, value) {
                                    $.ajax({
                                        url:`/collections/${value}/products.json?limit=249`,
                                        type: 'GET',
                                        dataType: 'json',
                                        async: false,
                                        crossDomain: true,
                                        success: function (response) {
                                                $.each(response.products, function (key, value1) {
                                                    $.each(cartLineItems, function (key, cartProductId) {
                                                        //below if condition is to check whether product has a variant or not
                                                        if(!cartProductId.product_has_only_default_variant){
                                                            if (cartProductId.product_id == value1.id) {
                                                            variantIds.push({
                                                                id: cartProductId.id,
                                                                handle: cartProductId.handle,
                                                                quantity: cartProductId.quantity,
                                                                image:cartProductId.image,
                                                                priceforPopup:cartProductId.price,
                                                            });
                                                        }
                                                      }
                                                    });
                                                });
                                            },
                                        });
                                    });
                                }
                        }
                        removeDuplicateValue(variantIds);
                        function removeDuplicateValue(myArray){ 
                                $.each(myArray, function(key, value) {
                                    var exists = false;
                                    $.each(newArray, function(k, val2) {
                                        if(value.id == val2.id){ 
                                            exists = true
                                        }; 
                                    });
                                    if(exists == false && value.id != "") {
                                        newArray.push(value);
                                    }
                                });
                                return newArray;
                        }
                        if (newArray.length > 0) {
                                displayButton(newArray);
                        }
                    } 
                    else{
                            if(cartLineItems.length > 0){

                                $.each(cartLineItems, function (key, cartProductId) {
                                      //below if condition is to check whether product has a variant or not
                                      if(!cartProductId.product_has_only_default_variant){
                                            variantIds.push({
                                                id: cartProductId.id,
                                                handle: cartProductId.handle,
                                                quantity: cartProductId.quantity,
                                                image:cartProductId.image,
                                                priceforPopup:cartProductId.price,
                                            });
                                        }
                                });

                                displayButton(variantIds);
                            }    
                    }
                }
            }
            if (window.location.pathname.indexOf('cart') > -1) {
                $(document).ajaxComplete(function (event, xhr, settings) {
                    if (settings.url.indexOf('/cart/change.js') > -1 || (settings.url.indexOf('/cart/update.js') > -1) || settings.url.indexOf('/cart/change') > -1) {
                        ajaxCallOnCartPage=true;
                        if(changeQtyOfPopUp == false){
                            window.location.reload();
                        }
                    }
                });
            if(ajaxCallOnCartPage == false){
                (function (ns, fetch) {
                    if (typeof fetch !== 'function')
                        return;
                    ns.fetch = function () {
                        if (arguments['0'] == "/cart/change.js"  || arguments['0'] == "/cart/change") {
                                return fetch.apply(this, arguments).then(function (response) {
                                    response.json().then(function (data) {
                                        if(changeQtyOfPopUp == false){
                                          window.location.reload();
                                        }
                                    });
                                })
                        }
                    }
                }(window, window.fetch))
            } 
            }
            $(document).on("click", "#changeBtn", function (e) {
                e.preventDefault();
                var handle = $(this).attr("data-handle");
                var imagesrc=$(this).attr("data-image");
                previousVariantId = $(this).attr("data-id");
                previousQty = $(this).attr("data-qty");
                price = $(this).attr("data-price");
                price=price/100;
                //check if survey question is enable in settings table
                var surveyQuestion='';
                if(rules.setting.enable_survey_question == 1){
                   var surveyQuestion =  "<div id = 'sureveyQuestion'>"+
                   "<lable>How did you know about us ?</lable><br>" +
                   "<input type='checkbox' name='option1'>Facebook<br>" +      
                   "<input type='checkbox' name='option2'>Google<br>" +      
                   "<input type='checkbox' name='option3'>Ads<br>" + 
                    "</div>";
                }
                else {
                    surveyQuestion='';
                }
                $("body").append(
                        '<div class="maincontainer">' +
                            '<div class="popupcontainer">'+
                            '<div class="popupblock">'+
                                '<div class="eosh-crossButton"><img class="crossimage" src="'+baseUrl+'images/close.png" alt="sorry"></div>'+
                                '<div class="table_container">'+
                                '<table id="customers" class="table">'+
                                    '<thead>'+
                                    '<tr>'+
                                        '<th class="product">PRODUCT</th>'+
                                        '<th class="">QUANTITY</th>'+
                                        '<th class="total">TOTAL</th>'+
                                    '</tr>'+
                                    '</thead>'+
        
                                    '<tbody class="body">'+
                                    '<tr>'+
                                        '<td>'+
                                        '<div class="imagecontainer">'+
                                            '<div class="img" id = "productImage">'+
                                            
                                            '</div>'+
                                            '<div class="dropdowncontent">'+
                                            '<h3 class="eosh-ProductTitle"><b></b></h3>'+
                                            '<div class="mbl_row">'+
                                                '<div>'+
                                                '<div><span id= "eosh-cartProductError" style= "color:red"></span></div>' +
                                                '<div class="form">'+
                                                    '<div id =option1Section><label id="labelDropDown1"></label>'+
                                                    '<select id="eosh-dropdown1" class="dropDowns">'+
                                                    '</select></div>'+
                                                    '<br>'+
                                                '</div>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div id =option2Section><label id="labelDropDown2"></label>'+
                                                    '<select id="eosh-dropdown2" class="dropDowns">'+
                                                    '</select></div>'+
                                                    '<br>'+
                                                '</div>'+
                                                '<div>'+
                                                '<div id =option3Section><label id="labelDropDown3"></label>'+
                                                    '<select id="eosh-dropdown3" class="dropDowns">'+
                                                    '</select></div>'+
                                                    '<select id="eosh-hiddenDropDown" hidden></select>' +
                                                    '<br>'+
                                                '</div>'+
                                                '<div style=display:flex;> <button class="update"id="eosh-updateProduct" style=color:'+rules.setting.text_color+';background-color:'+rules.setting.text_background_color+';>'+rules.setting.update_btn_text+'</button>'+
                                                '<button class="cancel" style=color:'+rules.setting.text_color+';background-color:'+rules.setting.text_background_color+'; id="cancelUpdateProduct">'+rules.setting.cancel_btn_text+'</button>'+
                                                '</div>'+
                                                    '</div>' +
                                            '</div>' +
        
                                        '</div>' +
                                        '</td>'+
                                        '<td class="one">'+
                                        '<div class="popup-price" popup-price='+price+'>'+
                                        '<button class= "eosh-minus">-</button>'+
                                            '<input type="text" id="eosh-quantity">'+
                                            '<button class= "eosh-plus">+</button>'+
                                        '</div>'+
                                        '</td>'+
                                        '<td class="ones">'+
                                        '<p id="eosh-price"></p>'+
                                        '</td>'+
                                    ' </tr>'+
                                    '</tbody>'+
                                '</table>'+
                                '</div>'+
                            '</div>'+
                            '</div>'
                );
                getVariants(handle,imagesrc);
            });
            $(document).on("click", ".eosh-plus, .eosh-minus", function (e) {
                var convertedPrice=$(this).parent().attr('popup-price');
                // var convertedPrice = price/100;
                   if($(this).hasClass('eosh-plus')){
                    value=$(this).prev().val();
                    var increment = ++value;
                    $('#eosh-quantity').val(increment);
                    var priceTotal = convertedPrice*increment;
                    var priceFormat=eoShPriceFormatter(priceTotal);
                    $('#eosh-price').text(priceFormat);
                   }
                   if($(this).hasClass('eosh-minus')){
                    value=$(this).next().val();
                    var decrement=1;
                    if(value > 1){
                        decrement = --value;
                    }            
                    $('#eosh-quantity').val(decrement);
                    var priceTotal = convertedPrice*decrement;
                    var priceFormat=eoShPriceFormatter(priceTotal);
                    $('#eosh-price').text(priceFormat);
                   }
            });
            $(document).on("keyup", "#eosh-quantity", function (e) {
                var userInputQty=$(this).val();
                var convertedPrice=$(this).parent().attr('popup-price');
                var priceTotal = convertedPrice*userInputQty;
                var priceFormat=eoShPriceFormatter(priceTotal);
                $('#eosh-price').text(priceFormat);
            });
            $(document).on("click", ".eosh-crossButton, #cancelUpdateProduct", function (e) {
                $(".maincontainer").remove();
            });
            $(document).on("change", "#eosh-dropdown1,#eosh-dropdown2,#eosh-dropdown3", function (e) {
                e.preventDefault();
                var selectedVariantTitle = "";
                var selectedImageSrc="";
                var selectedPrice="";
                if ($("#eosh-dropdown1 > option").length > 0 && $("#eosh-dropdown2 > option").length > 0 && $("#eosh-dropdown3 > option").length > 0) {
                    selectedVariantTitle = $("#eosh-dropdown1 option:selected").text() + " / " + $("#eosh-dropdown2 option:selected").text() + " / " + $("#eosh-dropdown3 option:selected").text();
                }
                if ($("#eosh-dropdown1 > option").length > 0 && $("#eosh-dropdown2 > option").length > 0 && $("#eosh-dropdown3 > option").length == 0) {
                    selectedVariantTitle =$("#eosh-dropdown1 option:selected").text() + " / " + $("#eosh-dropdown2 option:selected").text();
                }
                if ($("#eosh-dropdown1 > option").length > 0 && $("#eosh-dropdown2 > option").length == 0 && $("#eosh-dropdown3 > option").length == 0) {
                    selectedVariantTitle = $("#eosh-dropdown1 option:selected").text();
                }
                $.each($("#eosh-hiddenDropDown > option"), function (key, value) {
                    var hiddenOptionText = $(this).text().trim();
                    // if (hiddenOptionText.indexOf(selectedVariantTitle.trim()) >= 0) {
                    //     selectedVariantId = $(this).val();
                    //     selectedImageSrc=$(this).attr('data-imagesrc');
                    //     selectedPrice=$(this).attr('data-price');
                    // }
                    if(hiddenOptionText === selectedVariantTitle){
                        selectedVariantId = $(this).val();
                        selectedImageSrc=$(this).attr('data-imagesrc');
                        selectedPrice=$(this).attr('data-price');
                    }
                    // console.log(hiddenOptionText);
                    // console.log(selectedVariantTitle);
                });
                
                $('#productImage > img').attr("src",selectedImageSrc);
                var qty=$('#eosh-quantity').val();
                var qtyPrice=qty*selectedPrice;
                $('.popup-price').attr('popup-price',selectedPrice);
                var priceFormat=eoShPriceFormatter(qtyPrice);
                $('#eosh-price').text(priceFormat);
            });
            $(document).on("click", "#eosh-updateProduct", function (e) {
                e.preventDefault();
                $('#eosh-cartProductError').hide();
                var updatedCartProductQuantity = $("#eosh-quantity").val();
                if (selectedVariantId == ""){
                      changeQty(previousVariantId,updatedCartProductQuantity);
                }
                else if(selectedVariantId == previousVariantId){
                    changeQty(previousVariantId,updatedCartProductQuantity);
                }
                else if (selectedVariantId != "" ) {
                    var data = {
                        updates: {
                            [previousVariantId]: 0,
                            [selectedVariantId]: updatedCartProductQuantity,
                        },
                    };
                    updateQty(data);
                }
            });
            $('.icon-cart, .header__cart-count-bubble, .slide-menu-cart, .header__icon--cart, .cartCountSelector').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                window.location.replace("/cart");
            });
            function changeQty(previousVariantId,updatedCartProductQuantity){
                 $.ajax({
                    type: "POST",
                    url: "/cart/change.js",
                    data:{
                        'id': previousVariantId,
                        'quantity': updatedCartProductQuantity,
                        },
                    dataType: "json",
                    success: function (response) {
                        location.href = "/cart";
                    },
                    error: function (err) {
                        changeQtyOfPopUp=true;
                        $("#eosh-cartProductError").text(err.responseJSON.description);
                        $("#eosh-cartProductError").show();
                        setTimeout(function () {
                            $("#eosh-cartProductError").fadeOut("slow");
                            changeQtyOfPopUp=false;
                        }, 2000);
                    },
                });
            }
            function updateQty(data){
                $.ajax({
                    type: "POST",
                    url: "/cart/update.js",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        location.href = "/cart";
                    },
                    error: function (err) {
                        changeQtyOfPopUp=true;
                        $("#eosh-cartProductError").text(err.responseJSON.description);
                        $("#eosh-cartProductError").show();
                        setTimeout(function () {
                            $("#eosh-cartProductError").fadeOut("slow");
                            changeQtyOfPopUp=false;
                        }, 2000);
                        return false;
                    },
                });
            }
            function getCart() {
                var ajaxResponse;
                $.ajax({
                    url: "/cart.js",
                    type: "GET",
                    dataType: "json",
                    async: false,
                    crossDomain: true,
                    success: function (result) {
                        ajaxResponse = result;
                    },
                });
                return ajaxResponse;
            }
            function getRuleData() {
                let data = [];
                $.ajax({
                    url: baseUrl + "api/get-product-collection-data",
                    type: "GET",
                    async: false,
                    crossDomain: true,
                    data: { shop: domain },
                    contentType: "json",
                    dataType: "json",
                    success: function (response) {
                        data = response;
                        eoShopMoney=data.moneyFormat
                    },
                });
                return data;
            }
            function displayButton(newArray) {
            //   if(themeId == 887){
                $("cart-remove-button > a").each(function (key, value) {
                    if (this.href.indexOf("?id=") > -1) {
                        var point = $(this);
                        var hrefAttr = $(this).attr("href");
                        var qtyString = hrefAttr.toString().split("?id=").pop();
                        variantIdOnCartPage = qtyString.split(":")[0];
                        variantIdOnCartPage = parseInt(variantIdOnCartPage);
                        $.each(newArray, function (key, value) {
                            if (variantIdOnCartPage == value.id) {
                                point
                                    .parents("tr:first")
                                    .find("td:eq(1)")
                                    .append(
                                        '<div><button id="changeBtn" style=color:'+rules.setting.text_color+';background-color:'+rules.setting.text_background_color+'; data-handle="' +
                                            value.handle +
                                            '" data-id="' +
                                            value.id +
                                            '" data-qty="' +
                                            value.quantity +
                                            '" data-price="' +
                                            value.priceforPopup +
                                            '" data-image="'+value.image+'">'+rules.setting.change_btn_text+'</button></div>'
                                    );
                            }
                        });
                    }
                });
            //   }   
            }
            function getVariants(handle,imagesrc) {
                console.log(imagesrc);
                var productUrl = "/products/" + handle +"/product.json";
                $.ajax({
                    url: productUrl,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $.each(response.product.variants, function (index, value) {
                            if(value.image_id == null){
                                $("#eosh-hiddenDropDown").append(
                                    $("<option data-imagesrc="+imagesrc+" data-price="+value.price+"></option>").val(value.id).html(value.title));
                            } 
                            if(value.image_id != null){
                                var imageFilter = response.product.images.find(item => item.id === value.image_id );
                                var imageUrl=imageFilter.src;
                                $("#eosh-hiddenDropDown").append(
                                    $("<option data-imagesrc="+imageUrl+"  data-price="+value.price+"></option>").val(value.id).html(value.title));
                            }                       
                        });
                        var labelDropDown1 = null;
                        var dropDown1 = [];
                        var labelDropDown2 = null;
                        var dropDown2 = [];
                        var labelDropDown3 = null;
                        var dropDown3 = [];
                        var selectedTitleArray = "";
                        var selectedPrice='';
                        if (response.product.options.length > 0) {
                            $.each(response.product.options,function (key, variantName) {
                                    if (key == 0) {
                                        labelDropDown1 = variantName.name;
                                        $.each(variantName.values,function (id, variantValue) {
                                                dropDown1.push(variantValue);
                                            }
                                        );
                                    } else if (key == 1) {
                                        labelDropDown2 = variantName.name;
                                        $.each(variantName.values,function (id, variantValue) {
                                                dropDown2.push(variantValue);
                                            }
                                        );
                                    } else {
                                        labelDropDown3 = variantName.name;
                                        $.each(variantName.values,function (id, variantValue) {
                                                dropDown3.push(variantValue);
                                            }
                                        );
                                    }
                                }
                            );
                            if (labelDropDown1 != null) {
                                $("#labelDropDown1").append(labelDropDown1);
                                $.each(dropDown1, function (key, val) {
                                    $("#eosh-dropdown1").append(
                                        $("<option></option>").val(key).html(val)
                                    );
                                });
                            } else {
                                $("#option1Section").hide();
                            }
        
                            if (labelDropDown2 != null) {
                                $("#labelDropDown2").append(labelDropDown2);
                                $.each(dropDown2, function (key, val) {
                                    $("#eosh-dropdown2").append(
                                        $("<option></option>").val(key).html(val)
                                    );
                                });
                            } else {
                                $("#option2Section").hide();
                            }
        
                            if (labelDropDown3 != null) {
                                $("#labelDropDown3").append(labelDropDown3);
                                $.each(dropDown3, function (key, val) {
                                    $("#eosh-dropdown3").append(
                                        $("<option></option>").val(key).html(val)
                                    );
                                });
                            } else {
                                $("#option3Section").hide();
                            }
                        }
                        $.each($("#eosh-hiddenDropDown > option"), function (key, value) {
                            var hiddenOptionText = $(this).val();
                            if (hiddenOptionText.indexOf(previousVariantId) >= 0) {
                                selectedTitleArray = $(this).text().split(" / ");
                                selectedPrice=$(this).attr('data-price');
                                selectedImageSrc=$(this).attr('data-imagesrc');
                            }
                            // if(hiddenOptionText === selectedVariantTitle){
                            //     selectedVariantId = $(this).val();
                            //     selectedImageSrc=$(this).attr('data-imagesrc');
                            //     selectedPrice=$(this).attr('data-price');
                            // }
                        });
        
                        //this piece of code is to populate popup modal with customer selected product variants e.g size, color etc
                        if (selectedTitleArray.length == 1) {
                            $.each($("#eosh-dropdown1 option"), function (key, val) {
                                if (selectedTitleArray[0] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
                        }
                        if (selectedTitleArray.length == 2) {
                            $.each($("#eosh-dropdown1 option"), function (key, val) {
                                if (selectedTitleArray[0] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
        
                            $.each($("#eosh-dropdown2 option"), function (key, val) {
                                if (selectedTitleArray[1] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
                        }
                        if (selectedTitleArray.length == 3) {
                            $.each($("#eosh-dropdown1 option"), function (key, val) {
                                if (selectedTitleArray[0] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
        
                            $.each($("#eosh-dropdown2 option"), function (key, val) {
                                if (selectedTitleArray[1] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
        
                            $.each($("#eosh-dropdown3 option"), function (key, val) {
                                if (selectedTitleArray[2] == val.text) {
                                    $(this).attr("selected", "selected");
                                }
                            });
                        }
                        $(".eosh-ProductTitle").text(response.product.title);
                        $("#eosh-quantity").val(previousQty);
                        $("#productImage").html(`<img src='${imagesrc}' class ="productImage">`);
                        selectedPrice = selectedPrice*previousQty;
                        var priceFormat=eoShPriceFormatter(selectedPrice);
                        $('#eosh-price').text(priceFormat);
                         $(".maincontainer").css("display", "block");
                        
                    },
                });
            }
            function eoShPriceFormatter(eoShprice) {
                var eoShMoney = (eoShopMoney) ? eoShopMoney.replace(/<\/?[^>]+(>|$)/g, "") : theme.moneyFormat;
                var eoShMoneySymbol = '';
                var eoShAmount = eoShprice;
                eoShAmount = parseFloat(eoShAmount);
                eoShMoney = eoShMoney.replace(/\s/g, "");
                if (eoShMoney.indexOf("{{amount}}") >= 0) {
                    var eoShFormat = eoShNumberFormat(eoShAmount, 2);
                    eoShMoneySymbol = eoShMoney.replace("{{amount}}", eoShFormat);
                } else if (eoShMoney.indexOf("{{amount_no_decimals}}") >= 0) {
                    var eoShFormat = Math.round(eoShAmount);
                    eoShMoneySymbol = eoShMoney.replace("{{amount_no_decimals}}", eoShFormat);
                } else if (eoShMoney.indexOf("{{amount_with_comma_separator}}") >= 0) {
                    var eoShFormat = eoShNumberFormat(eoShAmount, 2, ',', '.');
                    eoShMoneySymbol = eoShMoney.replace("{{amount_with_comma_separator}}", eoShFormat);
                } else if (eoShMoney.indexOf("{{amount_no_decimals_with_comma_separator}}") >= 0) {
                    eoShAmount = eoShNumberFormat(eoShAmount, 2, ',', '.');
                    var eoShFormat = eoShAmount.substr(0, eoShAmount.lastIndexOf(","));
                    eoShMoneySymbol = eoShMoney.replace("{{amount_no_decimals_with_comma_separator}}", eoShFormat);
                } else if (eoShMoney.indexOf("{{amount_with_apostrophe_separator}}") >= 0) {
                    eoShAmount = Math.round(eoShAmount);
                    var eoShFormat = eoShAmount.replace(/,/g, "'");
                    eoShMoneySymbol = eoShMoney.replace("{{amount_with_apostrophe_separator}}", eoShFormat);
                } else {
                    eoShMoneySymbol = eoShMoney;
                }
                if (Shopify.theme.theme_store_id == 411) {
                    return eoShMoneySymbol.replace(/\u0026pound;/g, '£');
                }
                return eoShMoneySymbol;
            }
            function eoShNumberFormat(numberToFormat, decimals, dec_point, thousands_sep) {
                dec_point = typeof dec_point !== 'undefined' ? dec_point : '.';
                thousands_sep = typeof thousands_sep !== 'undefined' ? thousands_sep : ',';
                var parts = numberToFormat.toFixed(decimals).split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
                return parts.join(dec_point);
            }
            function eventRemoveFromAddToCartBtn(){
                $(eoAddToCartSelector).wrap( "<div class='eoshCart_button-deactivated'></div>" );
                $('.eoshCart_button-deactivated' ).wrap( "<div class='eoshCart_button'></div>" );
                $('.eoshCart_button-deactivated' ).attr('style', 'pointer-events: none;');
            }

            var rules = getRuleData();
            //hidden field is created in below code to get multiple values of survey checkbox 
            $(".cart__blocks").before('<div style="width: 55%;" ><div class="inner_container">'+
            '<div class="survey"><span class="survey-heading">How Did You Hear About Us ?</span>' +
            '<input type="hidden" class="eoshidden" form="cart" type="checkbox" name="attributes[Where did you hear about us ?]" value=""></input>'+
            '<label class="option"><input class="eoshinputt"  type="checkbox"  value="Google"> Google</label>'+
            '<label class="option"><input class="eoshinputt"  type="checkbox"  value="Facebook"> Facebook</label>'+
            '<label class="option"><input class="eoshinputt"  type="checkbox"  value="Ads"> Ads</label></div>'+
          
            '<div class="note">'+
          '<span class="note-heading">Message for seller</span>'+
          '<textarea name="note" form="cart" id="Cart-note" placeholder="Order special instructions" cols="45" rows="5" spellcheck="false"></textarea></div>'+
        //   '<textarea class="textbox" name="Cart Note" id="cart-note" cols="45" rows="5"></textarea>'+
          '<span class="message"><strong>'+rules.setting.merchant_msg+'</strong>&nbsp;</span>'+
          '</div></div>');

            if(rules.setting.enable_customer_msg != 1)
            {
                $(".note").hide();
            }
            if(rules.setting.enable_survey_question != 1){
                $(".survey").hide();
            }
            if(rules.setting.enable_merchant_msg != 1){
                $(".message").hide();
            }
          
          $(document).on("click", ".eoshinputt", function () {
            if($(this).is(":checked")){
                surveyAnswers.push($(this).val());
            }
            else if($(this).is(":not(:checked)")){
                var uncheck = $(this).val();
                console.log(uncheck);
                $.each(surveyAnswers, function(key, value) {
                    if(value == uncheck){
                        surveyAnswers.splice(key,1);
                    }
                    // console.log(surveyAnswers.toString());
                })
                console.log(surveyAnswers);
            }
            console.log(surveyAnswers.toString());
            $('.eoshidden').attr('value',surveyAnswers.toString());
        });
        });
    };
    if ((typeof jQuery === 'undefined') || (parseFloat(jQuery.fn.jquery) < 1.7)) {
            loadScript('//ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js', function () {
                jQuery191 = jQuery.noConflict(true);
                eoShCartCustomize(jQuery191);
            });
        } else {
            eoShCartCustomize(jQuery);
        }
})();












