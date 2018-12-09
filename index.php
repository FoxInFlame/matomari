<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="matomari. The perfect way to access MyAnimeList through code." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="theme-color" content="#2e51a2"/> <!-- Because fancy colours are nice on Android -->
    <!--GOOGLE-->
    <meta name="google-site-verification" content="Tu1uvU-GrbGcqSljN0HtOIHb5SVu_LpwQsR5eUGeJRg" />
    <!--END GOOGLE-->
    <title>Introduction - matomari API</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="canonical" href="https://www.matomari.tk">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="apple-touch-icon.png"/>

    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body class="mainpage">
    <div id="main">
      <div id="logo">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 73.56 49.51"
        id="logo_matomari">
        <g
          id="ma_line1">
          <path
            d="M14.54,12.35a23,23,0,0,1,7.3-2.7"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana1"/>
          <path
            d="M14.42,16.73a31.76,31.76,0,0,1,8.48-2.9"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana2"/>
          <path
            d="M18.64,7.7A106.36,106.36,0,0,1,21,21.45c.23,2.15-3,3.69-4.89,1.88-3.14-3,5.88-3.36,9.14-1.08"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana3"/>
        </g>
        <g
          id="to_line1">
          <path
            d="M31.36,9.05c1.19,2.11,2.81,5.13,3.26,5.93"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana4"/>
          <path
            d="M39.13,10.8c-9.83,8.82-7.54,12.32,4.35,11.45"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana5"/>
        </g>
        <g
          id="ma_line1-2"
          data-name="ma_line1">
          <path
            d="M51.17,11.43c3.29-2,4.62-2.07,6.58-1.94"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana6"/>
          <path
            d="M51.65,15a14,14,0,0,1,7.64-1.91"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana7"/>
          <path
            d="M54.8,7.52s3.41,9.75,3.86,13.4c.42,3.35-3.73,2.31-4.83,1.33-2.1-1.87,3.06-3.36,7.83,0"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana8"/>
        </g>
        <g
          id="ri_line1">
          <path
            d="M67.11,7.08s.06,2.53.22,5.19,1.49,2.09,2.83-.62"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana9"/>
          <path
            d="M76,6.5c2.77,10.07-.33,15.93-2.31,19.33"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_hiragana10"/>
        </g>
        <g
          id="Layer_12"
          data-name="Layer 12">
          <path
            d="M9.54,49.67l-1.1,4.5c6.23-11.05,6.46-7.72,4.56,0,4.4-7.66,5.11-9.4,3.71-2.67-1.53,7.35,8-2.58,7.46-4.42-.21-.68-4.91,5.33-3.71,6.5,3.13,3.06,6.88-6.78,3.71-6.5,2.71-.67-.2,11.55,5,5.38C31.45,49.82,36,37,35.83,36.71,34.61,35,31.9,48.87,31.58,52.5c-.12,1.31,3,2.72,4.91.46,1.18-1.39,4.78-5.65,4.3-5.73-1.19-.19-3.09,4.86-2.13,6.14,2.64,3.51,6.57-5.85,1.92-6.17,2.42.27,4.88.57,5.75-.29a23.92,23.92,0,0,1-1,6.67c3.36-9.67,5.12-5.71,4.28-1,3.93-7.14,5.46-6.33,4.25-2.75-2.67,7.86,5.38,3.65,8.54-2.53.71-1.38-5,3.18-3.74,5.87,1.87,4,5.23-6.76,3.72-6.07,2,0,.11,14.66,7.1.31,2.22-4.55-4.06-1.44-1.71.67,1.52,1.37,3.8.06,5.67-1.54-6.54,14.88,4.55,3.53,4,.47-.24-1.36-2.09,5.34.5,5.93,1.37.31,3.41,1,3.11-2"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_english_main"/>
          <path
            d="M29.46,44.08a33.37,33.37,0,0,1,8.17-1.21"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_english_t"/>
          <path
            d="M77.71,42.25A1.91,1.91,0,0,1,79,43.54"
            transform="translate(-7.76 -6.37)"
            id="logo_matomari_english_i"/>
        </g>
      </svg>
      </div>
      <a href="/docs/" id="cta_main">Go to the docs</a>
    </div>
    <!--<script>
    window.setTimeout(function() {
      var a = document.getElementById('logo');
      a.classList ? a.classList.add('animateSVG') : a.className += ' animateSVG';
      window.setTimeout(function() {
        a.style.marginTop = 'calc(-3rem - 50px)';
        window.setTimeout(function() {
          document.getElementById("cta_main").style.opacity = 1;
        }, 1000);
      }, 11000);
    }, 1000);
    </script>-->
  </body>
</html>