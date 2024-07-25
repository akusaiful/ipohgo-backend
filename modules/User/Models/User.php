<?php
namespace Modules\User\Models;

use Modules\Agency\Models\Agency;
use Modules\Agency\Models\AgencyAgent;

class User extends \App\User
{
    public function fillByAttr($attributes , $input)
    {
        if(!empty($attributes)){
            foreach ( $attributes as $item ){
                $this->$item = isset($input[$item]) ? ($input[$item]) : null;
            }
        }
    }

    public function agencies(){
        return $this->belongsToMany(Agency::class, AgencyAgent::class, 'agent_id', 'agencies_id');
    }

    public function getAvatar(){
        if($this->avatar_url){
            return $this->avatar_url;
        }else{
            return get_file_url(311, 'full');
        }        
    }

    public function getSignInProvider()
    {
        return $this->sign_in_provider?: 'Email';    
    }
}
