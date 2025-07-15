    <div
        style="background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%); width: 100%; height:100%">
        <style type="text/css">
            @font-face {
                font-family: 'Helvetica Neue';
                src: url('/fonts/HelveticaNeueRoman.otf') format('opentype');
                font-weight: 400;
                font-style: normal;
            }

            @font-face {
                font-family: 'Helvetica Neue';
                src: url('/fonts/HelveticaNeueMedium.otf') format('opentype');
                font-weight: 500;
                font-style: normal;
            }
        </style>
        <table width="100%" cellpadding="0" cellspacing="0"
            style="max-width:76rem; margin-left:auto; margin-right:auto; background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);">
            <tr>
                <td
                    style="padding: 1rem; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: white; word-break: break-word; background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);">
                    <div style="margin: 0 auto;">
                        <img style="display: block; margin: 0 auto 0.5rem auto;"
                            src="{{ $message->embed(public_path('images/logo.png')) }}" alt="logo">
                        <h1 style="font-size: 0.75rem; color: #DDCCAA; font-weight: 500; text-align: center">MOVIE
                            QUOTES
                        </h1>
                    </div>

                    <p style="margin: 2rem 0 1.5rem 0">
                        Hola {{ $name }}!
                    </p>

                    <p style="margin: 0 0 1.5rem 0;">
                        {{ $content }}
                    </p>

                    <a style="padding: 0.6rem 0.875rem; color: white; background-color: #E31221; border-radius: 0.25rem; text-decoration:none; display:inline-block; margin: 0 auto;"
                        href={{ $url }}>{{ $linkName }}</a>

                    <p style="margin: 2.5rem 0 1rem 0">If clicking doesn't work, you can try copying and pasting it to
                        your
                        browser:</p>

                    <a href={{ $url }} style="color: #DDCCAA; padding: 0">{{ $url }}</a>

                    <p style="margin: 2.5rem 0 1.5rem 0">If you have any problems, please contact us:
                        support@moviequotes.ge
                    </p>

                    <p>MovieQuotes Crew</p>
                </td>
            </tr>
        </table>
    </div>
