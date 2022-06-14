<?php

namespace App\Service\Pagination;

class PaginationService
{
    public function getBuilderHelpers (int $totalPageNbr, int $currentPageNbr): array
    {
        $paginationHelper = [];

        if ($totalPageNbr > 1) {
            array_push($paginationHelper, 1);

            if ($currentPageNbr - 2 > 0 && $currentPageNbr - 2 !== 1) {

                array_push($paginationHelper, $currentPageNbr - 2);
            }

            if ($currentPageNbr - 1 > 0 && $currentPageNbr - 1 !== 1) {

                array_push($paginationHelper, $currentPageNbr - 1);
            }

            if ($currentPageNbr != $totalPageNbr && $currentPageNbr !== 1) {
                array_push($paginationHelper, $currentPageNbr);
            }

            if ($currentPageNbr + 1 < $totalPageNbr) {
                array_push($paginationHelper, $currentPageNbr + 1);
            }

            if ($currentPageNbr + 2 < $totalPageNbr) {
                array_push($paginationHelper, $currentPageNbr + 2);
            }

            array_push($paginationHelper, $totalPageNbr);

            if ($paginationHelper[1] - $paginationHelper[0] > 1) {
                array_splice($paginationHelper, 1, 0, '...');
            }

            $helpersNbr = count($paginationHelper);
            if ($helpersNbr > 3
                && $paginationHelper[$helpersNbr - 1] - $paginationHelper[$helpersNbr - 2] > 1
            ) {
                array_splice($paginationHelper, $helpersNbr - 1, 0, '...');
            }
        }

        return $paginationHelper;
    }
}
