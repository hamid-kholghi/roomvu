<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deposit;
use Carbon\Carbon;

class CalculateDepositsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculate deposit every day';

    public function handle()
    {
        $deposit = Deposit::whereDate('created_at', Carbon::today());
        $debits = $deposit->sum('debit');
        $credits = $deposit->sum('credit');
        $balance = $credits - $debits;
        $this->info("Today Debits: {$debits},\nToday Credits: {$credits},\nToday Balance: {$balance}");
    }
}
