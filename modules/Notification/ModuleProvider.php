<?php
namespace Modules\Notification;

use Modules\Core\Helpers\SitemapHelper;
use Modules\ModuleServiceProvider;
use Modules\Notification\Models\Notification;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper){
        $sitemapHelper->add("notication",[app()->make(Notification::class),'getForSitemap']);        

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {        
        $this->mergeConfigFrom(
            __DIR__.'/Config/notification.php', 'notification'
        );

        $this->app->register(RouteServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        
        // $count = Notification::whereStatus('pending')->count('id');
        return [
            'notification'=>[
                "position"=>40,
                'url'        => route('notification.admin.index'),
                'title'      => __("Notification"),
                'icon'       => 'ion-md-notifications',
                'permission' => 'notification_view',
                'children'   => [
                    'news_view'=>[
                        'url'        => route('notification.admin.index'),
                        'title'      => __("All Notification"),
                        'permission' => 'notification_view',
                    ],
                    'news_create'=>[
                        'url'        => route('notification.admin.create'),
                        'title'      => __("Add Notification"),
                        'permission' => 'notification_create',
                    ],
                    // 'news_categoty'=>[
                    //     'url'        => route('notification.admin.category.index'),
                    //     'title'      => __("Categories"),
                    //     'permission' => 'news_create',
                    // ],
                    // 'news_tag'=>[
                    //     'url'        => route('notification.admin.tag.index'),
                    //     'title'      => __("Tags"),
                    //     'permission' => 'news_create',
                    // ],
                ]
            ],
        ];
    }

    public static function getTemplateBlocks(){
        return [
            'list_notification'=>"\\Modules\\Notification\\Blocks\\ListNotification",
        ];
    }

    public static function getUserMenu()
    {
        $res = [];

        $res['notification'] = [
            "position"=>80.1,
            'url'        => route('notification.admin.index'),
            'title'      => __("Manage Notification"),
            'icon'       => 'ion-md-bookmarks',
            'permission' => 'notification_view',
        ];

        return $res;
    }
}
