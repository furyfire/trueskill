<?php

declare(strict_types=1);

namespace DNW\Skills;

/**
 * Helper class to sort ranks in non-decreasing order.
 */
final class RankSorter
{
    /**
     * Performs an in-place sort of the items in according to the ranks in non-decreasing order.
     *
     * @param array<mixed> $teams     The items to sort according to the order specified by ranks.
     * @param array<int>   $teamRanks The ranks for each item where 1 is first place.
     *
     * @return array<int>
     */
    public static function sort(array &$teams, array &$teamRanks): array
    {
        array_multisort($teamRanks, $teams);

        return $teams;
    }
}
