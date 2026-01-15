<?php

namespace App\Statistic;

use App\Models\User;

class UserStatistic
{
    public function getUserStatisticByRole(): \Illuminate\Support\Collection
    {
        return User::query()
            ->groupBy('role')
            ->selectRaw('role, count(*) as total')
            ->pluck('total', 'role'); 
    }
}
