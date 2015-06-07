@extends('layouts.default')
@section('content')
	<div class="jumbotron text-center">
		<h1><small>Inc. Tax</small> $<span class="tax">0</span></h1>
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
		"inclusive": true,
		"rules": [
			"price &lt; 25"
		]
	},
	{
		"name": "Pizza",
		"quantity": 6,
		"price": 25.00,
		"tax": "5%",
		"inclusive": true,
		"rules": [
			"price &gt; 20",
			"quantity &gt; 5"
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
		"tax": "10%",
		"inclusive": true,
		"rules": [
			"price &lt; 50"
		]
	}
]
</textarea>

			<button data-mirror="myCodeMirror1" class="btn btn-success">Calculate total</button>
		</div>
	</div>
@stop
