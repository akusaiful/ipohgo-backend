<?php
namespace Modules\Notification\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Models\SEO;

class Notification extends BaseModel
{
    use SoftDeletes;

    const STATUS_PUBLISH = 'publish';

    protected $table = 'bravo_notifications';
    protected $fillable = [
        'title',
        'preview',
        'body',
        'status',
        'user_id',  
        'send'              
    ];

    public function dataForMobile($forSingle = false){
        $translation = $this->translate();
        $data = [
            'id'=>$this->id,
            // 'slug'=>$this->slug,
            'title'=>$translation->title,
            'preview' => $translation->preview,
            'body'=>$translation->body,                        
            'created_at'=>display_date($this->created_at),
            'author'=>[
                'display_name'=>$this->author->getDisplayName(),
                'avatar_url'=>$this->author->getAvatarUrl()
            ],            
            'timestamp' => time() . rand()
        ];
        return $data;
    }
    
}
