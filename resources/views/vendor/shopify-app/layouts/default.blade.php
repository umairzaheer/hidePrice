<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('shopify-app.app_name') }}</title>
        <link href="{{ asset('css/uptown.css') }}" rel="stylesheet">
        <link href="{{ asset('css/common.css') }}" rel="stylesheet">
        <link href="{{ asset('css/cart-customize.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
        {{-- <style>
            /* .select2-container .select2-selection--single {
                padding-top: 6px;
                height: 42px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow { 
                height: 42px;
                width: 665.172px;
            }
            .select2-results> ul.select2-results__options> li.select2-results__option{
                text-align:left;
            }
            .select2-results> ul.select2-results__options> li.select2-results__option> strong.select2-results__group{
                background: #f3f3f3;
            }
            .select2-results> ul.select2-results__options> li.select2-results__option> ul.select2-results__options--nested> li{
                padding-left: 40px;
            } */

            /* section + section {
                padding: 2rem 0rem 0rem 2rem; 
            } */

            /* .select2 .select2-container .select2-container--default{
                width: 100% !important;
            } */
            /* .select2-container--default .select2-selection--multiple{
                height: 40px !important;
            } */

            .select2-container--default .select2-selection--multiple{
                /* height: 38px !important; */
                height: auto !important;
            }
            .alignment
            {
                text-align: left
            }
            .select2-search__field{
                display: none;
            }
            .full-width {
                padding: 2rem 0rem 0rem 2rem; 
            }
        </style> --}}

        @yield('styles')

        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('js/cart-customize.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>



           
        
        @if(\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled'))
            <script src="https://unpkg.com/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
            <script src="https://unpkg.com/@shopify/app-bridge-utils{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
            <script
                @if(\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled'))
                    data-turbolinks-eval="false"
                @endif
            >
                var AppBridge = window['app-bridge'];
                var actions = AppBridge.actions;
                var utils = window['app-bridge-utils'];
                var createApp = AppBridge.default;
                var app = createApp({
                    apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', base64_decode(\Request::get('host'))) }}",
                    shopOrigin: "{{ base64_decode(\Request::get('host')) }}",
                    host: "{{ \Request::get('host') }}",
                    forceRedirect: true,
                });
            </script>

            @include('shopify-app::partials.token_handler')
            @include('shopify-app::partials.flash_messages')
        @endif

    </head>

    <body>
        <div class="app-wrapper">
            <div class="app-content">
                <main role="main">
                    @yield('content')
                    <footer>
                        <article class="help">
                            <span></span>
                            <p>
                                For support please log a ticket
                                <a target="_blank" href="https://support.extendons.com/">Here
                                    <svg version="1.1" id="Layer_1" height="15" width="15" fill="#084e8a" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
                                        <path d="M488.727,0H302.545c-12.853,0-23.273,10.42-23.273,23.273c0,12.853,10.42,23.273,23.273,23.273h129.997L192.999,286.09 c-9.087,9.087-9.087,23.823,0,32.912c4.543,4.543,10.499,6.816,16.455,6.816c5.956,0,11.913-2.273,16.455-6.817L465.455,79.458 v129.997c0,12.853,10.42,23.273,23.273,23.273c12.853,0,23.273-10.42,23.273-23.273V23.273C512,10.42,501.58,0,488.727,0z"/>
                                        <path d="M395.636,232.727c-12.853,0-23.273,10.42-23.273,23.273v209.455H46.545V139.636H256c12.853,0,23.273-10.42,23.273-23.273 S268.853,93.091,256,93.091H23.273C10.42,93.091,0,103.511,0,116.364v372.364C0,501.58,10.42,512,23.273,512h372.364 c12.853,0,23.273-10.42,23.273-23.273V256C418.909,243.147,408.489,232.727,395.636,232.727z"/>
                                    </svg>
                                </a>
                                <br>
                                or Email at
                                <a target="_blank" href="mailto:ess@extendons.com">ess@extendons.com</a>
                            </p>
                        </article>
                    </footer>
                </main>
            </div>
        </div>
        @yield('scripts')
    </body>
</html>

