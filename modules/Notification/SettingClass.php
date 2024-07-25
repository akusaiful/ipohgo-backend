<?php
namespace  Modules\Notification;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        // return [
        //     [
        //         'id'   => 'notification',
        //         'title' => __("Notification Settings"),
        //         'position'=>20,
        //         'view'=>"Notification::admin.settings.notification",
        //         "keys"=>[
        //             'notification_page_list_title',
        //             'notification_page_list_banner',
        //             'notification_sidebar',
        //             'notification_page_list_seo_title',
        //             'notification_page_list_seo_desc',
        //             'notification_page_list_seo_image',
        //             'notification_page_list_seo_share',

        //             'notification_vendor_need_approve',
        //             'notification_layout_search'
        //         ],
        //         'html_keys'=>[

        //         ]
        //     ]
        // ];
    }
}
