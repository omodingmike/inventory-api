<?php

    namespace Database\Seeders;

    use App\Models\inventory\Unit;
    use Illuminate\Database\Seeder;

    class AddNewMeasurementToUnits extends Seeder
    {
        public function run () : void
        {
            Unit ::create( [ 'name' => 'Cups' , 'symbol' => 'cps' ] );
        }
    }
