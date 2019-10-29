<?php

namespace App\Http\Controllers\Demo;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Cartalyst\Conditions\Condition;
use App\Http\Controllers\Controller;
use Cartalyst\Collections\Collection;

class ConditionsController extends Controller
{
    /**
     * Home view.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('demo/index');
    }

    /**
     * Regular view.
     *
     * @return \Illuminate\View\View
     */
    public function regular()
    {
        return view('demo/regular');
    }

    /**
     * Percentage view.
     *
     * @return \Illuminate\View\View
     */
    public function percentage()
    {
        return view('demo/percentage');
    }

    /**
     * Invlusive view.
     *
     * @return \Illuminate\View\View
     */
    public function inclusive()
    {
        return view('demo/inclusive');
    }

    /**
     * Condition processor.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function process(Request $request)
    {
        return $this->processData($request->input());
    }

    /**
     * Process a condition
     *
     * @param array $data
     *
     * @return array
     */
    protected function processData(array $data)
    {
        if ($this->isMulti($data)) {
            $result = [];
            $sumArray = [];

            foreach ($data as $key => $input) {
                $result[] = $this->processData($input);
            }

            foreach ($result as $k => $subArray) {
                foreach ($subArray as $id => $value) {
                    $sumArray[$id] = isset($sumArray[$id]) ? $sumArray[$id] : 0;
                    $sumArray[$id] += $value;
                    $sumArray[$id] = number_format($sumArray[$id], 2);
                }
            }

            return $sumArray;
        }

        // Create our collection
        $collection = new Collection(Arr::except($data, 'tax'));

        // Create our condition
        $condition = new Condition([
            'target' => 'price',
        ]);

        // Set actions
        $condition->setActions([
            [
                'value'      => Arr::get($data, 'tax', 0),
                'multiplier' => 'quantity',
                'inclusive'  => Arr::get($data, 'inclusive', false),
            ],
        ]);

        // Do we have rules to check against?
        if ($rules = Arr::get($data, 'rules')) {
            $condition->setRules(Arr::flatten($rules));
        }

        // Apply the condition
        $oldtotal  = Arr::get($data, 'quantity', 1) * Arr::get($data, 'price', 0);

        $newTotal  = $condition->apply($collection);

        $inclusive = filter_var(Arr::get($data, 'inclusive', false), FILTER_VALIDATE_BOOLEAN);

        // Regular
        if ($newTotal !== false && !$inclusive) {
            $price = $newTotal;

            $tax = $newTotal - $oldtotal;
        }

        // Inclusive conditions
        else if ($inclusive) {
            $price = $oldtotal;

            $tax = $newTotal - $oldtotal;
        }

        // Invalid conditions
        else {
            $price = $oldtotal;

            $tax = 0;
        }

        // Result
        return [
            'price' => $price,
            'tax'   => $tax,
        ];
    }

    /**
     * Determines if the given array is a multidimensional array.
     *
     * @param array $array
     *
     * @return bool
     */
    protected function isMulti(array $array): bool
    {
        return is_array(array_shift($array));
    }
}
