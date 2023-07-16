<!DOCTYPE HTML>
<!--
    Broadcast by TEMPLATED
    templated.co @templatedco
    Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
    <head>
        <title>{{ $g->g_title }} {{ Config::get('constants.general.site_name_upper_1') }} - {{ trans('message.site_title.2') }}</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="../css/main.css" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
		
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-KK6BKBHN2Z"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-KK6BKBHN2Z');
		</script>
		<style type="text/css">
			html, body {
				height: 100%;
				margin: 0;
				position: relative;
			}
			#iframe-wrapper {
				/* position: absolute;
				top: 15px; */
				padding-top: 15px !important;
				min-height: 100%;
				min-width: 100%;
				height: 100% !important;
				z-index: 1;
			}
			#iframe-wrapper-landscape {
				/* position: absolute;
				top: 15px; */
				padding-top: 15px !important;
				min-height: 100%;
				min-width: 100%;
				height: 100% !important;
				z-index: 1;
			}
			#ifr_play_game_m {
				width: 100% !important;
				height: 100% !important;
			}
			.hide-overlay {
				width: 55px;
				height: 50px;
				position: absolute;
				top: 48px;
				right: 0px;
				background-color: #000000;
				z-index: 1000;
			}
			.hidden {
				display: none;
			}
    	</style>

		<!-- PWA -->
		<meta name="theme-color" content="#6777ef"/>
        <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
		
    </head>
    <body onload="openFullscreen">
	@php
		$hide_class = '';
		if (in_array($g->g_title_slug, array('nations-league-soccer')))
			$hide_class = 'hide-overlay';
		else
			$hide_class = 'hidden';	
	@endphp
		<div class="{{ $hide_class }}">
		</div>

	@if ($g->g_dimension == 0)
		<div id="iframe-wrapper-landscape"><iframe id="ifr_play_game_m" seamless="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true" webkit-playsinline="true" frameborder="0" scrolling="no" src="{{ $g->g_link }}"></iframe></div>	
	@else
		<div id="iframe-wrapper"><iframe id="ifr_play_game_m" seamless="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true" webkit-playsinline="true" frameborder="0" scrolling="no" src="{{ $g->g_link }}"></iframe></div>
	@endif	
		<!-- <img src="../images/icons/ic-player.png" id="btn_play_game" onclick="openFullscreen('{{ getOrientationMode($g->g_dimension) }}');" /> -->

		<script src="{{ asset('/sw.js') }}"></script>
		<script>
			if (!navigator.serviceWorker.controller) {
				navigator.serviceWorker.register("/sw.js").then(function (reg) {
					console.log("Service worker has been registered for scope: " + reg.scope);
				});
			}
		</script>

	</body>
	
</html>