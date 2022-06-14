<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class NumericExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'Numéric extension';
    }

    public function getTests()
    {
        return [
            new TwigTest('numeric', function ($value) {
                return is_numeric($value);
            })
        ];
    }
}
