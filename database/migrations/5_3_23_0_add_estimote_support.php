<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstimoteSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_beacons', function(Blueprint $table)
        {
            $table->string('type')->after('id')->default('Kontakt');
        });
        $date = new Carbon;
        DB::table('settings')->insert([
            [
                'label' => 'Estimote APP ID',
                'name' => 'key.estimote_id',
                'value' => '',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'label' => 'Estimote API Key',
                'name' => 'key.estimote_key',
                'value' => '',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => $date,
                'updated_at' => $date
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_beacons', function(Blueprint $table)
        {
            $table->dropColumn('type');
        });
        DB::table('settings')->whereIn('name', ['key.estimote_id', 'key.estimote_key'])->delete();
    }

}
