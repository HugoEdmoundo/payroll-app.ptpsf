<?php

namespace App\Traits;

trait GlobalSearchable
{
    /**
     * Apply global search to query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @param array $searchableFields
     * @param array $relationFields (optional) ['relation' => ['field1', 'field2']]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyGlobalSearch($query, $search, array $searchableFields, array $relationFields = [])
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search, $searchableFields, $relationFields) {
            // Search in main table fields
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }

            // Search in related table fields
            foreach ($relationFields as $relation => $fields) {
                $q->orWhereHas($relation, function($subQuery) use ($search, $fields) {
                    $subQuery->where(function($sq) use ($search, $fields) {
                        foreach ($fields as $field) {
                            $sq->orWhere($field, 'like', "%{$search}%");
                        }
                    });
                });
            }
        });
    }
}
