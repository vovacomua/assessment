<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignStartDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-campaign-start-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Campaign Start Dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * 1) First option would be to traverse all records chunk by chunk (to save memory)
         *
         * 2) If it would not work quite well then second option would be to
         * create table campaigns_new,
         * copy data from campaigns to campaigns_new
         * update campaigns_new records with 'requestStartDate' fn (use CampaignNew model)
         * and then rename campaigns_new to campaigns (using transaction to keep data consistent)
         *
         */
        $this->info('Updating campaign start dates...');

//        DB::statement("CREATE TABLE campaigns_new LIKE campaigns;");
//        DB::statement("SELECT * INTO campaigns_new FROM campaigns");

        Campaign::chunk(200, function ($campaigns) {                //use CampaignNew for Option 2)
                foreach ($campaigns as $campaign) {
                    $startDate = CampaignService::filter()->requestStartDate($campaign->id);
                    $campaign->update(['start_date' => $startDate]);
                }
        });

//        DB::transaction(function () {
//            DB::statement('ALTER TABLE campaigns RENAME TO campaigns_old');
//            DB::statement('ALTER TABLE campaigns_new RENAME TO campaigns');
//            DB::statement('DROP TABLE IF EXISTS campaigns_old');
//        });

        $this->info('Campaign start dates updated.');
    }
}

// Model for temporary table for Option 2)
class CampaignNew extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns_new';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'order',
        'type',
        'status',
        'start_date'
    ];
}
