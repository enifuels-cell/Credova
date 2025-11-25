<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AgingReportCommand extends Command
{
    protected $signature = 'report:aging';
    protected $description = 'Generate aging report';

    public function handle()
    {
        $today = now()->toDateString();
        $select = implode(', ', [
            "SUM(CASE WHEN DATEDIFF('$today', first_due_date) <= 0 THEN balance ELSE 0 END) as current",
            "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 1 AND 30 THEN balance ELSE 0 END) as `1_30`",
            "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 31 AND 60 THEN balance ELSE 0 END) as `31_60`",
            "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 61 AND 90 THEN balance ELSE 0 END) as `61_90`",
            "SUM(CASE WHEN DATEDIFF('$today', first_due_date) > 90 THEN balance ELSE 0 END) as `gt_90`",
            "SUM(balance) as total"
        ]);

        $row = DB::table('loans')->selectRaw($select)->where('status','!=','paid')->first();

        $this->table(['Bucket','Amount'], [
            ['Current', $row->current],
            ['1-30', $row->{"1_30"}],
            ['31-60', $row->{"31_60"}],
            ['61-90', $row->{"61_90"}],
            ['>90', $row->gt_90],
            ['Total', $row->total],
        ]);
    }
}
