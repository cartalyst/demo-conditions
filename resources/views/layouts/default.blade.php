<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Conditions Demo</title>

	<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
	<link href="{{ URL::to('assets/css/font-awesome.min.css') }}" rel="stylesheet" media="screen">
	<link href="{{ URL::to('assets/css/demo.css') }}" rel="stylesheet" media="screen">
	<link href="{{ URL::to('assets/css/codemirror.css') }}" rel="stylesheet" media="screen">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>

	<div class="flux clearfix">
		<div class="flux--1"></div>
		<div class="flux--2"></div>
		<div class="flux--3"></div>
		<div class="flux--4"></div>
		<div class="flux--5"></div>
	</div>

	<div class="container">
		<nav class="navbar xnavbar-fixed-top navbar-inverse" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="{{ URL::to('/') }}">Conditions</a>
			</div>

			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li {!! URL::current() === URL::to('') ? 'class="active"' : null !!}><a href="{{URL::to('/')}}">Home</a></li>
					<li {!! URL::current() === URL::to('regular') ? 'class="active"' : null !!}><a href="{{URL::to('regular')}}">Regular</a></li>
					<li {!! URL::current() === URL::to('percentage') ? 'class="active"' : null !!}><a href="{{URL::to('percentage')}}">Percentage</a></li>
					<li {!! URL::current() === URL::to('inclusive') ? 'class="active"' : null !!}><a href="{{URL::to('inclusive')}}">Inclusive</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="https://cartalyst.com/manual/conditions">Manual</a></li>
				</ul>
			</div>
		</nav>

		@yield('content')
	</div>

	<script src="{{ URL::to('assets/js/jquery.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/codemirror.js') }}"></script>
	<script src="{{ URL::to('assets/js/codemirror.javascript.js') }}"></script>
	<script src="{{ URL::to('assets/js/waypoints.js') }}"></script>
	<script src="{{ URL::to('assets/js/counterUp.js') }}"></script>

	<script type="text/javascript">
		var self = {};

		self.myCodeMirror = CodeMirror.fromTextArea(document.getElementById('textarea'), {
			lineNumbers: true,
			matchBrackets: true
		});

		self.myCodeMirror1 = CodeMirror.fromTextArea(document.getElementById('textarea1'), {
			lineNumbers: true,
			matchBrackets: true
		});

		$('button').on('click', function(e) {
			e.preventDefault();

			mirror = $(this).data('mirror');

			var data = $.parseJSON(self[mirror].getValue());

			console.log($.isArray(data));

			if ($.isArray(data)) {
				var oldData = data;
				data = {};

				$.each(oldData, function(i) {
					data[i] = oldData[i];
				});
			}

			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$.ajax({
				url: 'process',
				data: data,
				type: 'post',
			}).done(function(res) {
				$('h1 .tax').text(res.tax);
				$('h1 .price').text(res.price);

				$('.price').counterUp({
					delay: 5,
					time: 500
				});

				$('.tax').counterUp({
					delay: 5,
					time: 500
				});
			});
		});
	</script>

	@yield('scripts')

	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-26550564-1']);
		_gaq.push(['_setDomainName', 'cartalyst.com']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>

</body>
</html>
