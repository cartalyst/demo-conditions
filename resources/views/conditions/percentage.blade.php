@extends('layouts.default')

@section('scripts')
<script>
var self = {};

self.myCodeMirror = CodeMirror.fromTextArea(document.getElementById('textarea'), {
	lineNumbers: true,
	matchBrackets: true
});

self.myCodeMirror1 = CodeMirror.fromTextArea(document.getElementById('textarea1'), {
	lineNumbers: true,
	matchBrackets: true
});

$('button').on('click', function(e)
{
	e.preventDefault();

	mirror = $(this).data('mirror');

	var data = $.parseJSON(self[mirror].getValue());

	console.log($.isArray(data));

	if ($.isArray(data))
	{
		var oldData = data;
		data = {};

		$.each(oldData, function(i)
		{
			data[i] = oldData[i];
		});
	}

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
@stop

@section('content')

	<div class="jumbotron text-center">
		<h1><small>Tax</small> $<span class="tax">0</span></h1>
		<h1><small>Total</small> $<span class="price">0</span></h1>
	</div>

	<p class="lead">Play with the highlighted values below</p>

	<div class="row">
		<div class="col-md-6">

<textarea id="textarea" cols="20" rows="10" class="form-control default">
[
	{
		"name": "Hamburger",
		"quantity": 5,
		"price": 20.00,
		"tax": "12.5%",
		"rules": [
			"price &lt; 30"
		]
	},
	{
		"name": "Pizza",
		"quantity": 3,
		"price": 25.00,
		"rules": [
			"price &gt; 20",
			"quantity &gt; 2"
		]
	}
]
</textarea>

		<button data-mirror="myCodeMirror" class="btn btn-success">Calculate total</button>
	</div>
	<div class="col-md-6">

<textarea id="textarea1" cols="20" rows="10" class="form-control default">
[
	{
		"name": "Pizza",
		"quantity": 5,
		"price": 20.00,
		"tax": "25%",
		"rules": [
			"price &lt; 25"
		]
	}
]
</textarea>

		<button data-mirror="myCodeMirror1" class="btn btn-success">Calculate total</button>
	</div>
</div>

@stop
