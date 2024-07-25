<?php
namespace Themes\Mytravel\Tour\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Tour\Models\Tour;
use Modules\Tour\Models\TourCategory;
use Modules\Location\Models\Location;

class ListTours extends BaseBlock
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
                    'id'      => 'category_id',
                    'type'    => 'select2',
                    'label'   => __('Filter by Category'),
                    'select2' => [
                        'ajax'  => [
                            'url'      => url('/admin/module/tour/category/getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'allowClear' => 'true',
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected'=>url('/admin/module/tour/category/getForSelect2?pre_selected=1')
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
                    'label'        => __('List Tour by IDs'),
                    'select2'      => [
                        'ajax'     => [
                            'url'      => route('tour.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width'    => '100%',
                        'multiple' => "true",
                    ],
                    'pre_selected' => route('tour.admin.getForSelect2', [
                        'pre_selected' => 1
                    ])
                ]
            ],
            'category'=>__("Tour Blocks")
        ]);
    }

    public function getName()
    {
        return __('Tour: List Items');
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $model['style'] = !empty($model['style']) ? $model['style'] :  "style_1";
        $data = [
            'rows'       => $list,
            'title'      => $model['title'] ?? "",
            'desc'      => $model['desc'] ?? "",
        ];
        return $this->view('Tour::frontend.blocks.list-tour.'.$model['style'], $data);
    }

    public function contentAPI($model = []){
        $rows = $this->query($model);
        $model['data']= $rows->map(function($row){
            return $row->dataForApi();
        });
        return $model;
    }

    public function query($model){
        $model_Tour = Tour::select(Tour::getTableName().".*")->with(['location','translation','hasWishList']);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (!empty($model['location_id'])) {
            $location = Location::where('id', $model['location_id'])->where("status","publish")->first();
            if(!empty($location)){
                $model_Tour->join(Location::getTableName(), function ($join) use ($location) {
                    $join->on(Location::getTableName().'.id', '=', Tour::getTableName().'.location_id')
                        ->where(Location::getTableName().'._lft', '>=', $location->_lft)
                        ->where(Location::getTableName().'._rgt', '<=', $location->_rgt);
                });
            }
        }
        if (!empty($model['category_id'])) {
            $category_ids = [$model['category_id']];
            $list_cat = TourCategory::whereIn('id', $category_ids)->where("status","publish")->get();
            if(!empty($list_cat) and $list_cat->count() > 0)
            {
                $where_left_right = [];
                $params = [];
                foreach ($list_cat as $cat){
                    $where_left_right[] = " ( ".TourCategory::getTableName()."._lft >= ? AND ".TourCategory::getTableName()."._rgt <= ? ) ";
                    $params[] = $cat->_lft;
                    $params[] = $cat->_rgt;
                }
                $sql_where_join = " ( ".implode("OR" , $where_left_right)." )  ";
                $model_Tour
                    ->join(TourCategory::getTableName(), function ($join) use($sql_where_join,$params) {
                        $join->on(TourCategory::getTableName().'.id', '=', Tour::getTableName().'.category_id')
                            ->WhereRaw($sql_where_join,$params);
                    });
            }
        }
        if(!empty($model['is_featured']))
        {
            $model_Tour->where(Tour::getTableName().'.is_featured',1);
        }

        if(!empty( $model['custom_ids'] )){
            $model_Tour->whereIn(Tour::getTableName().".id",$model['custom_ids']);
        }
        $model_Tour->orderBy(Tour::getTableName().".".$model['order'], $model['order_by']);
        $model_Tour->where(Tour::getTableName().".status", "publish");
        $model_Tour->with(['location','category_tour','category_tour']);
        $model_Tour->groupBy(Tour::getTableName().".id");
        return $model_Tour->limit($model['number'])->get();
    }
}
