<?php
namespace Themes\Mytravel\Hotel\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\Location;

class ListHotel extends BaseBlock
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
                    'label'     => __('Number Item'),
                    "default" => "5",
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
                    ],
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
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
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
                    'label'        => __('List Hotel by IDs'),
                    'select2'      => [
                        'ajax'     => [
                            'url'      => route('hotel.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width'    => '100%',
                        'multiple' => "true",
                    ],
                    'pre_selected' => route('hotel.admin.getForSelect2', [
                        'pre_selected' => 1
                    ])
                ]
            ],
            'category'=>__("Hotel Blocks")
        ]);
    }

    public function getName()
    {
        return __('Hotel: List Items');
    }

    public function content($model = [])
    {
        $hotel_table = Hotel::getTableName();
        $location_table = Location::getTableName();
        $model_hotel = Hotel::select($hotel_table.".*")->with(['location','translation','hasWishList']);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (!empty($model['location_id'])) {
            $location = Location::where('id', $model['location_id'])->where("status","publish")->first();
            if(!empty($location)){
                $model_hotel->join($location_table, function ($join) use ($location,$location_table,$hotel_table) {
                    $join->on($location_table.'.id', '=', $hotel_table.'.location_id')
                        ->where($location_table.'._lft', '>=', $location->_lft)
                        ->where($location_table.'._rgt', '<=', $location->_rgt);
                });
            }
        }

        if(!empty($model['is_featured']))
        {
            $model_hotel->where($hotel_table.'.is_featured',1);
        }

        if(!empty( $model['custom_ids'] )){
            $model_hotel->whereIn($hotel_table.".id",$model['custom_ids']);
        }

        $model_hotel->orderBy($hotel_table.".".$model['order'], $model['order_by']);
        $model_hotel->where($hotel_table.".status", "publish");
        $model_hotel->with('location');
        $model_hotel->groupBy($hotel_table.".id");
        $list = $model_hotel->limit($model['number'])->get();
        $model['style'] = !empty($model['style']) ? $model['style'] :  "style_1";
        $data = [
            'rows'       => $list,
            'title'      => $model['title'],
            'desc'       => $model['desc'],
        ];
        return $this->view('Hotel::frontend.blocks.list-hotel.'.$model['style'], $data);
    }
}
