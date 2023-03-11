// $(document).ready(function () {
//     // $.ajax({
//     //   url: baseUrl + 'api/get-product-handle',
//     //   type: 'GET',
//     //   async: false,
//     //   crossDomain: true,
//     //   data:{
//     //     domain_name:domain,
//     //   },
//     //   contentType: "json",
//     //   dataType: "json",
//     //   success: function(response) {
//     // var i = 0;
//     //  $.each(response[i], function (key, item){
//     //    console.log(item.handle);
//     //  })
//     // for(let j = 0; j < response.length; j++) {
//     //   var eoshCurrentSelector = $(this);
//     //  for(let i = 0; i < productIds.length; i++) {
//     // if(productIds[i]==id){
//     //   console.log("matched");
//     //   eoshCurrentSelector.closest('.card__content').find('.price').after("<div class='eosh-task'><span style='color:red'><b>Done</b></span></div>");
//     // }

//     // }
//     // }
//     // }
//     // });

//     function getParameter() {
//         var domain = Shopify.shop;
//         var baseUrl = "https://shopify-app.test/";
//         let productIds = [];
//         $.ajax({
//             url: baseUrl + "api/match-product",
//             type: "GET",
//             async: false,
//             crossDomain: true,
//             data: domain,
//             contentType: "json",
//             dataType: "json",
//             success: function (response) {
//                 productIds = response.id;
//             },
//         });

//         $("a").each(function (i, obj) {
//              var handles = $(this).attr("href").split("/").pop();
//             var eoshCurrentSelector = $(this);
//             jQuery.getJSON(
//                 window.Shopify.routes.root + "products/" + handles + ".js",
//                 function (product) {
//                     for (let i = 0; i < productIds.length; i++) {
//                         console.log(product["id"]);
//                         if (productIds[i] == product["id"]) {
//                             console.log("matched");
//                             eoshCurrentSelector
//                                 .closest(".card__content")
//                                 .find(".price")
//                                 .replaceWith(
//                                     "<div class='eosh-task'><span style='color:red'><b>Price hidden</b></span></div>"
//                                 );
//                         }
//                     }
//                 }
//             );
//         });
//     }

//     function starRating() {
//         var domain = Shopify.shop;
//         var baseUrl = "https://shopify-app.test/";
//         var path = window.location.pathname;
//         var handle = path.split("/")[2];
//         let productIds = [];
//         $(".five_star").click(function () {
//         $.ajax({
//             url: baseUrl + "api/star-rating",
//             type: "GET",
//             async: false,
//             crossDomain: true,
//             data: domain,
//             contentType: "json",
//             dataType: "json",
//             success: function (response) {
//                 console.log(response.rating);
              
//               productIds = response.id;
//                 console.log(handle);
//                 jQuery.getJSON(
//                     window.Shopify.routes.root + "products/" + handle + ".js",
//                     function (product) {
//                         for (let i = 0; i < productIds.length; i++) {
//                             if (productIds[i] == product["id"]) {
//                                 $(".five_star_rating").text("ids matched");
//                             }
//                             else{
//                                 $(".five_star_rating").text("ids donot match");
//                             }
//                         }
//                     }
//                 );
//             },
//         });
//       })
//     }
//     var starRating = starRating();
//     var productName = getParameter();
// });
