<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2019
 * Time: 2:05 PM
 */
namespace Modules\Notification\Models;

use App\BaseModel;

class NotificationTranslation extends BaseModel
{
    protected $table = 'bravo_notification_translations';
    protected $fillable = ['title', 'body', 'preview'];
    protected $seo_type = 'notification_translation';
    protected $cleanFields = [
        'content', 'preview'
    ];
}