<?php
namespace Themes\Mytravel\Space\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Space\Models\Space;
use Modules\Location\Models\Location;

class ListSpace extends BaseBlock
{
    function getOptions()
    {
        return ([
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'desc',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Desc')
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Item')
                ],
                [
                    'id'            => 'style',
                    'type'          => 'radios',
                    'label'         => __('Style'),
                    'values'        => [
                        [
                            'value'   => '',
                            'name' => __("Style 1")
                        ],
                        [
                            'value'   => 'style_2',
                            'name' => __("Style 2")
                        ],
                    ]
                ],
                [
                    'id'      => 'location_id',
                    'type'    => 'select2',
                    'label'   => __('Filter by Location'),
                    'select2' => [
                        'ajax'  => [
                            'url'      => url('/admin/module/location/getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'allowClear' => 'true',
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected'=>url('/admin/module/location/getForSelect2?pre_selected=1')
                ],
                [
                    'id'            => 'order',
                    'type'          => 'radios',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'value'   => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'value'   => 'title',
                            'name' => __("Title")
                        ],
                    ]
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'radios',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'value'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'value'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ]
                ],
                [
                    'type'=> "checkbox",
                    'label'=>__("Only featured items?"),
                    'id'=> "is_featured",
                    'default'=>true
                ],
                [
                    'id'           => 'custom_ids',
                    'type'         => 'select2',
                    'label'        => __('List Space by IDs'),
                    'select2'      => [
                        'ajax'     => [
                            'url'      => route('space.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width'    => '100%',
                        'multiple' => "true",
                    ],
                    'pre_selected' => route('space.admin.getForSelect2', [
                        'pre_selected' => 1
                    ])
                ]
            ],
            'category'=>__("Space Blocks")
        ]);
    }

    public function getName()
    {
        return __('Space: List Items');
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $model['style'] = !empty($model['style']) ? $model['style'] :  "style_1";
        $data = [
            'rows'       => $list,
            'title'      => $model['title'] ?? "",
            'desc'       => $model['desc'] ?? "",
        ];
        return $this->view('Space::frontend.blocks.list-space.'.$model['style'], $data);
    }

    public function contentAPI($model = []){
        $rows = $this->query($model);
        $model['data']= $rows->map(function($row){
            return $row->dataForApi();
        });
        return $model;
    }

    public function query($model){

        $space_table = Space::getTableName();
        $location_table = Location::getTableName();

        $model_space = Space::select($space_table.".*")->with(['location','translation','hasWishList']);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (!empty($model['location_id'])) {
            $location = Location::where('id', $model['location_id'])->where("status","publish")->first();
            if(!empty($location)){
                $model_space->join($location_table, function ($join) use ($location,$location_table,$space_table) {
                    $join->on($location_table.'.id', '=', $space_table.'.location_id')
                        ->where($location_table.'._lft', '>=', $location->_lft)
                        ->where($location_table.'._rgt', '<=', $location->_rgt);
                });
            }
        }

        if(!empty($model['is_featured']))
        {
            $model_space->where($space_table.'.is_featured',1);
        }

        if(!empty( $model['custom_ids'] )){
            $model_space->whereIn($space_table.".id",$model['custom_ids']);
        }

        $model_space->orderBy($space_table.".".$model['order'], $model['order_by']);
        $model_space->where($space_table.".status", "publish");
        $model_space->with('location');
        $model_space->groupBy($space_table.".id");
        return $model_space->limit($model['number'])->get();
    }
}
