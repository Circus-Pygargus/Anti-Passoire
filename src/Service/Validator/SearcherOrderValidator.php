<?php

namespace App\Service\Validator;

use App\Entity\AntiPassoire;
use Symfony\Component\Form\Form;

class SearcherOrderValidator
{
    public static function isOrderValid (Form $search): bool
    {
        return (in_array($search->get('orderBy')->getData(), AntiPassoire::ORDER_BY_POSSIBILITIES)
            && in_array($search->get('orderDirection')->getData(), AntiPassoire::ORDER_DIRECTION_POSSIBILITIES))
                ? true
                : false;
    }
}
