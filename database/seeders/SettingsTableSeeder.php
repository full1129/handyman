<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'ADMOB_APP_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'ADMOB_BANNER_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'ADMOB_INTERSTITIAL_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'COLOR_PRIMARY_COLOR',
                'type' => 'COLOR',
                'value' => '#000000',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'COLOR_SECONDARY_COLOR',
                'type' => 'COLOR',
                'value' => '#000000',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'CURRENCY_COUNTRY_ID',
                'type' => 'CURRENCY',
                'value' => '231',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'CURRENCY_POSITION',
                'type' => 'CURRENCY',
                'value' => 'left',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'ONESIGNAL_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'ONESIGNAL_REST_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'DISTANCE_TYPE',
                'type' => 'DISTANCE',
                'value' => 'km',
            ),
            10 => 
            array (
                'id' => 11,
                'key' => 'DISTANCE_RADIOUS',
                'type' => 'DISTANCE',
                'value' => '50',
            ),
        ));
        
        
    }
}