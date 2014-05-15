<?php

use Cartalyst\Conditions\Condition;
use Illuminate\Support\Collection;

class ConditionsController extends BaseController {

	/**
	 * Home view
	 *
	 * @return \Illuminate\View\View
	 */
	public function home()
	{
		return View::make('home');
	}

	/**
	 * Percentage view
	 *
	 * @return \Illuminate\View\View
	 */
	public function regular()
	{
		return View::make('conditions.regular');
	}

	/**
	 * Percentage view
	 *
	 * @return \Illuminate\View\View
	 */
	public function percentage()
	{
		return View::make('conditions.percentage');
	}

	/**
	 * Invlusive view
	 *
	 * @return \Illuminate\View\View
	 */
	public function inclusive()
	{
		return View::make('conditions.inclusive');
	}

	/**
	 * Condition processor.
	 *
	 * @return array
	 */
	public function process()
	{
		$result   = [];
		$sumArray = [];
		$inputs   = Input::all();

		return $this->processData($inputs);
	}

	/**
	 * Process a condition
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	protected function processData($data)
	{
		if ($this->isMulti($data))
		{
			foreach ($data as $key => $input)
			{
				$result[] = $this->processData($input);
			}

			foreach ($result as $k => $subArray)
			{
				foreach ($subArray as $id => $value)
				{
					$sumArray[$id] = isset($sumArray[$id]) ? $sumArray[$id] : 0;
					$sumArray[$id]+=$value;
					$sumArray[$id] = number_format($sumArray[$id], 2);
				}
			}

			return $sumArray;
		}

		// Create our collection
		$collection = new Collection(array_except($data, 'tax'));

		// Create our condition
		$condition = new Condition([
			'target' => 'price',
		]);

		// Set actions
		$condition->setActions([

			[
				'value'      => array_get($data, 'tax'),
				'multiplier' => 'quantity',
				'inclusive'  => array_get($data, 'inclusive', false),
			],

		]);

		// Do we have rules to check against?
		if ($rules = array_get($data, 'rules'))
		{
			$condition->setRules(array_flatten($rules));
		}

		// Apply the condition
		$newTotal  = $condition->apply($collection);
		$inclusive = filter_var(array_get($data, 'inclusive', false), FILTER_VALIDATE_BOOLEAN);
		$oldtotal  = array_get($data, 'quantity') * array_get($data, 'price');

		// Regular
		if ($newTotal !== false && ! $inclusive)
		{
			$price = $newTotal;
			$tax   = $newTotal - $oldtotal;
		}

		// Inclusive conditions
		else if ($inclusive)
		{
			$price = $oldtotal;
			$tax   = $newTotal - $oldtotal;
		}

		// Invalid conditions
		else
		{
			$price = $oldtotal;
			$tax   = 0;
		}

		// Result
		return [
			'price' => $price,
			'tax'   => $tax,
		];
	}

	protected function isMulti($array)
	{
		return is_array(array_shift($array));
	}

}
