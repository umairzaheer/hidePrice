(function () {
    var loadScript = function (url, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        // If the browser is Internet Explorer.
        if (script.readyState) {
            script.onreadystatechange = function () {
                if (
                    script.readyState == "loaded" ||
                    script.readyState == "complete"
                ) {
                    script.onreadystatechange = null;
                    callback();
                }
            };
            // For any other browser.
        } else {
            script.onload = function () {
                callback();
            };
        }
        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
    };
    // This is my app's JavaScript /
    var eoshLoadmoreInfinitescroll = function ($) {
        $("head").append(
            "<style> .rating {display: flex; flex-direction: row-reverse; float: left;} .rating > label {font-size: 25px; color: lightgray}.rating > label:hover { content:''; position: relative; color: yellow}  .rating input {display:none;}  .rating :checked ~ label {color:yellow;}  .rating label:hover ~ label {color:yellow;} </style>"
        );
        let baseURL = "https://shopify-app.test/";
        let orderId = Shopify.checkout["order_id"];
        $(document).ready(function () {
            Shopify.Checkout.OrderStatus.addContentBox(
                "<div class='rating'>  <input type='radio' id='star1' name='rate' class='rate' value='5'><label for='star1'>&#x2605</label>" +
                  "<input type='radio' id='star2' name='rate' class='rate' value='4'><label for='star2'>&#x2605</label>" +
                  "<input type='radio' id='star3' name='rate' class='rate' value='3'><label for='star3'>&#x2605</label>" +
                  "<input type='radio' id='star4' name='rate' class='rate' value='2'><label for='star4'>&#x2605</label>" +
                  "<input type='radio' id='star5' name='rate' class='rate' value='1'><label for='star5'>&#x2605</label>" +
                "</div><br>" +
                "<div class ='review'>" +
                  "<textarea name = 'review' class = 'orderReview' style = 'width: 384px; height: 90px; padding: 5px 10px; border: solid 1px;border-radius: 0.62em;margin-top: 15px;' placeholder = 'Enter your review here'></textarea>" +
                "</div><br>" +
                "<div class = 'product_review'>" +
                  "<button style = 'color:blue; padding: 0.85em 1.5em; border-radius: 0.62em; display: inline-block; border: 1px solid;' class = 'review'>Submit Review</button>" +
                "</div>"
            );
            $(".orders").click(function () {
                var domain = Shopify.shop;
                $.ajax({
                    url: baseURL + "/api/order-status",
                    type: "post",
                    async: false,
                    crossDomain: true,
                    data: {
                        domain_name: domain,
                        order_Id: orderId,
                    },
                    success: function (data) {
                        $(".cancel_order").text(data["body"]["notice"]);
                    },
                });
            });

            $(".review").click(function () {
                var domain = Shopify.shop;
                var productId = $(".product").data("product-id");
                var variantId = $(".product").data("variant-id");
                var review_value = $(".orderReview").val();
                var rating = $("input[name='rate']:checked").val();
                $.ajax({
                    url: baseURL + "api/order-review",
                    type: "post",
                    async: false,
                    crossDomain: true,
                    data: {
                        domain_name: domain,
                        reviewval: review_value,
                        order_Id: orderId,
                        productId: productId,
                        variantId: variantId,
                        rating: rating,
                    },
                    success: function (data) {
                    },
                });
            });
            $(".stars a").on("click", function () {
                $(".stars span, .stars a").removeClass("active");
                $(this).addClass("active");
                $(".stars span").addClass("active");
                $(".myrating").html($(this).text());
            });
        });
    };
    if (typeof jQuery === "undefined" || parseFloat(jQuery.fn.jquery) < 1.7) {
        loadScript(
            "//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
            function () {
                jQuery191 = jQuery.noConflict(true);
                eoshLoadmoreInfinitescroll(jQuery191);
            }
        );
    } else {
        eoshLoadmoreInfinitescroll(jQuery);
    }
})();
