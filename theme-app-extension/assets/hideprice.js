$(document).ready(function () {

    function getParameter() {
        var domain = Shopify.shop;
        var baseUrl = "https://shopify-app.test/";
        let data = [];
         $.ajax({
            url: baseUrl + "api/match-product",
            type: "GET",
            async: false,
            crossDomain: true, 
            data: domain,
            contentType: "json",
            dataType: "json",
            success: function (response) {
             data = response ;
            },
        });
           if(Boolean(__st.cid)){
            console.log("logged in...");
            $(".card-information > .price").css("display","block");
           } else{ 
                console.log(" not logged in...");  
                $.each(data,function(key, value) {
                  if(value.rule_status == 1 && value.rule_category == 'Products'){
                    $.each(this.products, function(key, id){
                      let productIds = id.product_id;
                        $('a[href*="/products/"]').each(function (i, obj) {
                            var handles = $(this).attr("href").split("/").pop();
                             var eoshCurrentSelector = $(this);
                              jQuery.getJSON(
                                window.Shopify.routes.root + "products/" + handles + ".js",
                                 function (product) {   
                                    if (productIds == product["id"]) {
                                        $(eoshCurrentSelector)
                                                .closest(".card__content")
                                                .find(".price")
                                                .replaceWith(
                                                "<div class='eosh-task'><span style='color:#0c0cb5'><b>Please Login to see prices</b></span></div>"
                                        );
                                     }
                                 }
                              );
                        });
                        
                           var path = window.location.pathname;
                            var handle = path.split("/")[2];
                            jQuery.getJSON(
                              window.Shopify.routes.root + "products/" + handle + ".js",
                              function () {
                                    if(meta.product.id == productIds) {
                                       $(".product-form__buttons").replaceWith("<div class='eosh-task'><a href = 'https://app-test-12.myshopify.com/account/login'><button type = 'button' class = 'hideprice'>Please Login to see prices</button></a></div>")
                                       $(".price__container").css("display","none");
                                    }  
                                    
                              }) 
                                 
                            
                    });
                    // $(".price__container").css("display","block"); 
                }
                else if(value.rule_status == 1 && value.rule_category == 'All') {
                    console.log("all selected");
                        $('a[href*="/products/"]').each(function (i, obj) {
                            var handles = $(this).attr("href").split("/").pop();
                            var eoshCurrentSelector = $(this);
                            jQuery.getJSON(
                                window.Shopify.routes.root + "products/" + handles + ".js",
                                function (product) {   
                                        $(eoshCurrentSelector)
                                                .closest(".card__content")
                                                .find(".price")
                                                .replaceWith(
                                                "<div class='eosh-task'><span style='color:#0c0cb5'><b>Please Login to see prices</b></span></div>"
                                        );
                                }
                            );
                        });
                            $(".product-form__buttons").replaceWith("<div class='eosh-task'><a href = 'https://app-test-12.myshopify.com/account/login'><button type = 'button' class = 'hideprice'>Please Login to see prices</button></a></div>")
                            $(".price__container").hide();
                }
            });        
           
          }
        
    }


          // $("a").each(function (i, obj) { 
                                    //     var handles = $(this).attr("href").split("/").pop();
                                    //     var eoshCurrentSelector = $(this);
                                    //     // console.log(eoshCurrentSelector);
                                    //     jQuery.getJSON(
                                    //         window.Shopify.routes.root + "products/" + handles + ".js",
                                    //         function (product) {
                                    //             console.log(productIds.length);
                                    //             for (let i = 0; i < productIds.length; i++) {
                                    //                 if (productIds[i] == product["id"]) {
                                    //                     eoshCurrentSelector
                                    //                         .closest(".card__content")
                                    //                         .find(".price")
                                    //                         .replaceWith(
                                    //                             "<div class='eosh-task'><span style='color:red'><b>Price hidden</b></span></div>"
                                    //                         );
                                    //                 }
                                    //             }
                                    //         }
                                    //     );
                                    // });

    var productName = getParameter();
});
