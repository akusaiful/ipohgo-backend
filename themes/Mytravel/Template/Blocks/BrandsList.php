<?php
namespace Themes\Mytravel\Template\Blocks;

use Modules\Template\Blocks\BaseBlock;

class BrandsList extends BaseBlock
{
    function getOptions()
    {
        return ([
            'settings' => [
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Brand Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'        => 'title',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Title')
                        ],
                        [
                            'id'    => 'image_id',
                            'type'  => 'uploader',
                            'label' => __('Featured Image')
                        ],
                    ]
                ],
            ],
            'category'=>__("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Brands List');
    }

    public function content($model = [])
    {
        if(!empty($model['image_id'])){
            $model['image_url'] = get_file_url($model['image_id'] , 'full');
        }
        return $this->view('Template::frontend.blocks.brands-list.index', $model);
    }

    public function contentAPI($model = []){
        if(!empty($model['image_id'])){
            $model['image_url'] = get_file_url($model['image_id'] , 'full');
        }
        return $model;
    }
}
