<?php

namespace Encore\Incore;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Class Doutils.
 */
class Doutils
{
    /**
     * Format data to tree like array.
     *
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function toTree(EloquentModel $model, array $elements = [], $parentId = 0)
    {
        $branch = [];

        if (empty($elements)) {
            $elements = $model->orderByRaw('`order` = 0,`order`')->get()->toArray();
        }

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = static::toTree($model, $elements, $element['id']);

                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

}
