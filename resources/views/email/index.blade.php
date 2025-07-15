<div style="background-color: rgb(219, 219, 219); padding: 3rem 0 5rem 0">
    <style type="text/css">
        @font-face {
            font-family: 'Raleway';
            font-style: normal;
            font-weight: 700;
            src: url('https://fonts.gstatic.com/s/raleway/v28/1Ptug8zYS_SKggPNyC0ISg.woff2') format('woff2');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url('https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTcviYw.woff2') format('woff2');
        }
    </style>
    <div style="width: 100%; height: 100%;">
        <div
            style="margin: 0 auto; width: 28rem; text-align:center; font-family: 'Inter', Arial, Helvetica, sans-serif !important;">
            <img style="height: 2.75rem; width: 4.5rem; display: block; margin: 0 auto;"
                src="{{ $message->embed(public_path('images/logo.png')) }}" alt="logo">
            <h1
                style="font-weight: bold; font-size: 2.5rem; font-family: 'Raleway', Arial, Helvetica, sans-serif !important; text-align: center; margin: 1.5rem 0; color:#000">
                {{ $title }}
            </h1>
            <p style="color: #000; margin: 0 0 1.5rem 0; text-align:left">
                {!! nl2br(e($content)) !!}
            </p>
            <a style="padding: 0.625rem 3.25rem; font-weight: 600; color: #fff; background-color: #4B69FD; border-radius: 0.625rem; text-decoration:none; display:inline-block; margin: 0 auto;"
                href="{{ $url }}">{{ $linkName }}</a>
        </div>
    </div>
</div>
