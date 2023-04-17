<?php namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;
use App\Models\User;
use Exception;
class ShopRedactJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;
    /**
     * The webhook data
     *
     * @var object
     */
    public $data;
    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        // Convert domain
        $this->shopDomain = ShopDomain::fromNative($this->shopDomain);
        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
        try {
                $shop = User::withTrashed()->where('name', $this->shopDomain->toNative())->first();
                
                \Log::info($shop);
                if(isset($shop) && !empty($shop) && $shop->plan_id == null
                && $shop->shopify_token == null ){
                    $shop->forceDelete();
                    \Log::info("DELETED");
                }
                return;
        }
         catch(\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }
}
