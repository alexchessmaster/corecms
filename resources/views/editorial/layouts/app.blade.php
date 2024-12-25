<!DOCTYPE HTML>
<html>

<head>
    @php
        $response = Http::post('https://cms-api.nordicstandard.net/api/content', [
            'path' => '/en',
        ]);
        $body = $response->body();
        if(!$body) {
            dd('api not working');
        }
        $data = json_decode($body)->data;
        // dd($data);
    @endphp

    <title>{{ $data->settings[0]->value }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/editorial/assets/css/main.css" />
</head>

<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">

        @yield('content')


    </div>

    <!-- Scripts -->
    <script src="/editorial/assets/js/jquery.min.js"></script>
    <script src="/editorial/assets/js/browser.min.js"></script>
    <script src="/editorial/assets/js/breakpoints.min.js"></script>
    <script src="/editorial/assets/js/util.js"></script>
    <script src="/editorial/assets/js/main.js"></script>

</body>

</html>
