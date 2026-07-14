<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\Order;
use App\Models\DailySalesSummary;
use Carbon\Carbon;

class GenerateDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary {date? : The target date for generating the summary (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate platform-wide daily sales summaries per shop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateInput = $this->argument('date');
        $targetDate = $dateInput ? Carbon::parse($dateInput)->format('Y-m-d') : Carbon::yesterday()->format('Y-m-d');

        $this->info("Generating daily sales summaries for: {$targetDate}");

        // Get completed orders for that date grouped by shop_id
        $summaries = Order::where('status', 'completed')
            ->whereDate('updated_at', $targetDate)
            ->selectRaw('shop_id, SUM(final_amount) as total_sales, COUNT(id) as total_orders')
            ->groupBy('shop_id')
            ->get();

        if ($summaries->isEmpty()) {
            $this->warn("No completed orders found for {$targetDate}. Creating zero-sales records for active shops.");
            $shops = Shop::all();
            foreach ($shops as $shop) {
                DailySalesSummary::updateOrCreate(
                    ['shop_id' => $shop->id, 'date' => $targetDate],
                    ['total_sales' => 0.00, 'total_orders' => 0]
                );
            }
        } else {
            foreach ($summaries as $summary) {
                DailySalesSummary::updateOrCreate(
                    ['shop_id' => $summary->shop_id, 'date' => $targetDate],
                    [
                        'total_sales' => $summary->total_sales,
                        'total_orders' => $summary->total_orders
                    ]
                );
                $this->line("Shop ID {$summary->shop_id}: Total Sales = Rp{$summary->total_sales}, Orders = {$summary->total_orders}");
            }

            // Fill in zeros for other shops that had no sales on that day
            $excludeShopIds = $summaries->pluck('shop_id')->toArray();
            $otherShops = Shop::whereNotIn('id', $excludeShopIds)->get();
            foreach ($otherShops as $shop) {
                DailySalesSummary::updateOrCreate(
                    ['shop_id' => $shop->id, 'date' => $targetDate],
                    ['total_sales' => 0.00, 'total_orders' => 0]
                );
            }
        }

        $this->info("Daily sales summaries generation completed successfully!");
        return Command::SUCCESS;
    }
}
