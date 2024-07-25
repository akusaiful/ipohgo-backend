<?php
namespace Themes\Mytravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\View;
use Modules\Theme\Abstracts\AbstractThemeProvider;
use Themes\Base\Core\Middleware\RunUpdater;
use Themes\Mytravel\Car\Blocks\ListCar;
use Themes\Mytravel\Database\Seeders\DatabaseSeeder;
use Themes\Mytravel\Event\Blocks\ListEvent;
use Themes\Mytravel\Hotel\Blocks\ListHotel;
use Themes\Mytravel\Car\Blocks\TermCar;
use Themes\Mytravel\Location\Blocks\ListLocations;
use Themes\Mytravel\Location\Blocks\UnmissableDestinations;
use Themes\Mytravel\Space\Blocks\ListSpace;
use Themes\Mytravel\Template\Blocks\BrandsList;
use Themes\Mytravel\Template\Blocks\BreadcrumbSection;
use Themes\Mytravel\Template\Blocks\CallToAction;
use Themes\Mytravel\Template\Blocks\FormSearchAllService;
use Themes\Mytravel\Template\Blocks\ListAllService;
use Themes\Mytravel\Template\Blocks\ListFeaturedItem;
use Themes\Mytravel\Template\Blocks\VideoPlayer;
use Themes\Mytravel\Tour\Blocks\ListTours;
use Themes\Mytravel\Tour\Blocks\Testimonial;

class ThemeProvider extends \Themes\Base\ThemeProvider
{

    public static $version = '2.3.0';
    public static $asset_version = '2.3.0';
    public static $name = 'IpohGO';
    public static $parent = 'base';

    public static $seeder = DatabaseSeeder::class;

    public static function info()
    {
        // TODO: Implement info() method.
    }

    public function boot(Kernel $kernel)
    {


        parent::boot($kernel);

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        //Hook Settings
        add_filter(\Modules\Core\Hook::CORE_SETTING_CONFIG,[$this,'alterSettings']);
        add_action(\Modules\Core\Hook::CORE_SETTING_AFTER_LOGO,[$this,'showCustomFieldsAfterLogo']);
        add_action(\Modules\Core\Hook::CORE_SETTING_AFTER_FOOTER,[$this,'showCustomFieldsAfterFooter']);
        add_action(\Modules\Core\Hook::CORE_SETTING_AFTER_CONTACT,[$this,'showCustomFieldsAfterContact']);

        add_filter(\Modules\Booking\Hook::BOOKING_SETTING_CONFIG,[$this,'alterBookingSettings']);
        add_action(\Modules\Booking\Hook::BOOKING_SETTING_AFTER_INVOICE,[$this,'showCustomFieldsAfterBookingInvoice']);

        if(static::$asset_version)
        config()->set('app.asset_version',static::$asset_version);
    }

    public static function getTemplateBlocks(){
        return [
            'form_search_all_service'=>FormSearchAllService::class,
            'list_locations'=>ListLocations::class,
            'unmissable_destinations'=>UnmissableDestinations::class,
            "list_all_service"=>ListAllService::class,
            "call_to_action"=>CallToAction::class,
            'list_featured_item'=>ListFeaturedItem::class,
            "list_tours"=>ListTours::class,
            'list_hotel'=>ListHotel::class,
            'list_space'=>ListSpace::class,
            'list_car'=>ListCar::class,
            'list_event'=>ListEvent::class,
            'testimonial'=>Testimonial::class,
            'brands_list'=>BrandsList::class,
            'breadcrumb_section'=>BreadcrumbSection::class,
            'video_player'=>VideoPlayer::class,
            'term_car'=>TermCar::class,
        ];
    }

    public function register()
    {
        parent::register();
        $this->app->register(\Themes\Mytravel\Tour\ModuleProvider::class);
        // $this->app->register(\Themes\Mytravel\Hotel\ModuleProvider::class);
        // $this->app->register(\Themes\Mytravel\Car\ModuleProvider::class);
        $this->app->register(UpdaterProvider::class);
    }


    public function alterSettings($settings){
        if(!empty($settings['general'])){
            $settings['general']['keys'][] = 'logo_id_2';
            $settings['general']['keys'][] = 'logo_text';

            $settings['general']['keys'][] = 'phone_contact';
            $settings['general']['keys'][] = 'footer_info_text';

            $settings['general']['keys'][] = 'page_contact_lists';
            $settings['general']['keys'][] = 'page_contact_iframe_google_map';
        }
        return $settings;
    }

    public function alterBookingSettings($settings){
        if(!empty($settings['booking'])){
            $settings['booking']['keys'][] = 'booking_why_book_with_us';
        }
        return $settings;
    }

    public function showCustomFieldsAfterLogo(){
        echo view('Core::admin.settings.setting-after-logo');
    }
    public function showCustomFieldsAfterFooter(){
        echo view('Core::admin.settings.setting-after-footer');
    }
    public function showCustomFieldsAfterContact(){
        echo view('Core::admin.settings.setting-after-contact');
    }
    public function showCustomFieldsAfterBookingInvoice(){
        echo view('Booking::admin.settings.setting-after-booking-config');
    }
}
