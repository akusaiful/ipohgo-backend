<?php
namespace Modules\Notification\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Notification\Models\Notification;
// use Modules\News\Models\NewsCategory;

class ListNotification extends BaseBlock
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
                    'id'        => 'body',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Desc')
                ],
                // [
                //     'id'        => 'number',
                //     'type'      => 'input',
                //     'inputType' => 'number',
                //     'label'     => __('Number Item')
                // ],
                // [
                //     'id'      => 'category_id',
                //     'type'    => 'select2',
                //     'label'   => __('Filter by Category'),
                //     'select2' => [
                //         'ajax'  => [
                //             'url'      => route('news.admin.category.getForSelect2') ,
                //             'dataType' => 'json'
                //         ],
                //         'width' => '100%',
                //         'allowClear' => 'true',
                //         'placeholder' => __('-- Select --')
                //     ],
                //     'pre_selected'=>route('news.admin.category.getForSelect2',['pre_selected'=>1])
                // ],
                // [
                //     'id'            => 'order',
                //     'type'          => 'radios',
                //     'label'         => __('Order'),
                //     'values'        => [
                //         [
                //             'value'   => 'id',
                //             'name' => __("Date Create")
                //         ],
                //         [
                //             'value'   => 'title',
                //             'name' => __("Title")
                //         ],
                //     ]
                // ],
                // [
                //     'id'            => 'order_by',
                //     'type'          => 'radios',
                //     'label'         => __('Order By'),
                //     'values'        => [
                //         [
                //             'value'   => 'asc',
                //             'name' => __("ASC")
                //         ],
                //         [
                //             'value'   => 'desc',
                //             'name' => __("DESC")
                //         ],
                //     ]
                // ]
            ],
            // 'category'=>__("News")
        ]);
    }

    public function getName()
    {
        return __('Notification: List Items');
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $data = [
            'rows'       => $list,
            'title'      => $model['title'] ?? "",
            'desc'      => $model['body'] ?? "",
        ];
        return view('Notification::frontend.blocks.list-notification.index', $data);
    }

    public function contentAPI($model = []){
        $rows = $this->query($model);
        $model['data']= $rows->map(function($row){
            return $row->dataForApi();
        });
        return $model;
    }

    public function query($model){
        $model_notification = Notification::select("bravo_notifications.*")->with(['translation']);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        // if (!empty($model['category_id'])) {
        //     $category_ids = [$model['category_id']];
        //     $list_cat = NewsCategory::whereIn('id', $category_ids)->where("status","publish")->get();
        //     if(!empty($list_cat)){
        //         $where_left_right = [];
        //         $params = [];
        //         foreach ($list_cat as $cat){
        //             $where_left_right[] = " ( core_notification_category._lft >= ? AND core_notification_category._rgt <= ? ) ";
        //             $params[] = $cat->_lft;
        //             $params[] = $cat->_rgt;
        //         }
        //         $sql_where_join = " ( ".implode("OR" , $where_left_right)." )  ";
        //         $model_notification
        //             ->join('core_notification_category', function ($join) use($sql_where_join,$params) {
        //                 $join->on('core_notification_category.id', '=', 'core_notification.cat_id')
        //                     ->WhereRaw($sql_where_join,$params);
        //             });
        //     }
        // }

        $model_notification->orderBy("core_notification.".$model['order'], $model['order_by']);
        $model_notification->where("core_notification.status", "publish");
        $model_notification->groupBy("core_notification.id");
        return $model_notification->limit($model['number'])->get();
    }
}
