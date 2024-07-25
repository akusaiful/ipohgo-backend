<?php
namespace Themes\Mytravel\Database\Seeders;
use Database\Seeders\BoatSeeder;
use Database\Seeders\EventSeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\HotelSeeder;
use Database\Seeders\Language;
use Database\Seeders\LocationSeeder;
use Database\Seeders\News;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SocialSeeder;
use Database\Seeders\SpaceSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(){

        Artisan::call('cache:clear');
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(Language::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MediaFileSeeder::class);
        $this->call(General::class);
        $this->call(LocationSeeder::class);
        $this->call(News::class);
        $this->call(Tour::class);
        $this->call(SpaceSeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(SocialSeeder::class);
        $this->call(FlightSeeder::class);
        $this->call(BoatSeeder::class);
    }
}
