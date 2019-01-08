@php
$polyfills = [
    'Promise',
    'Object.assign',
    'Object.values',
    'Array.prototype.find',
    'Array.prototype.findIndex',
    'Array.prototype.includes',
    'String.prototype.includes',
    'String.prototype.startsWith',
    'String.prototype.endsWith',
];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{config('app.title')}}</title>
        
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="" />        

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- Polyfill JS features via polyfill.io --}}
        <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>        

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}?ver={{config('app.js')}}" defer></script>
 
        @yield('js')

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}?ver={{config('app.css')}}" rel="stylesheet">
        @yield('css')
    </head>
    <body>
        <i class="fas fa-9x fa-spinner fa-pulse fullpage-loader text-success"></i>
        <div id="app" style="display: none;">
            <v-app light>
                <v-content>
                    @yield('content')
                </v-content>
                <scroll-up-button icon="fas fa-angle-double-up" color="blue lighten-2" text-color="white--text"></scroll-up-button>
                <v-footer
                  height="auto"
                >
                    <v-card
                      flat
                      tile
                      class="flex text-xs-center"
                    >
                        <v-card-text>
                          &copy;2018 — <a href="https://vuetifyjs.com/ru/" target="blank"><strong>Vuetify</strong></a>
                        </v-card-text>
                    </v-card>
                </v-footer>
            </v-app>                    
        </div>
    </body>
</html>
