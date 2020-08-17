<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

<meta name="csrf-token" content="{{ csrf_token() }}">
	

	<title>Tala Flota Admin</title>

	<!-- Bootstrap CSS -->
	<link href="/css/app.css" rel="stylesheet">
	<link href="/css/vendor/font-awesome.css" rel="stylesheet">
<script src="/js/lararail.js"></script>
	

	<!-- Web Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>


<body>
@include('partials.header.pelado')
@yield('content')
</body>
@include('partials.footer')
</html>
