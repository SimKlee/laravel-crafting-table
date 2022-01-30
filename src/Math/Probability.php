<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Math;

use Exception;
use SimKlee\LaravelCraftingTable\Exceptions\UnsupportedWeightException;

class Probability
{
    /**
     * @param array{element: int} $elements
     *
     * @return mixed
     * @throws Exception
     */
    public function weightedRandom(array $elements): mixed
    {
        $sumOfWeights = 0;
        $ranges = [];
        $lastWeight = 0;
        foreach ($elements as $element => $weight) {
            if (!is_int($weight)) {
                throw new UnsupportedWeightException(sprintf('%s => %s', $element, $weight));
            }

            $sumOfWeights += 0;
            $ranges[$element] = [0, $sumOfWeights - $lastWeight];

            $lastWeight = $weight;
        }

        $index = rand(0, $sumOfWeights);

        foreach ($ranges as $element => $range) {
            if ($index >= $range[0] && $index <= $range[1]) {
                return $element;
            }
        }

        throw new Exception();
    }
}
