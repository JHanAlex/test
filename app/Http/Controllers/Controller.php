<?php

namespace Clear\Http\Controllers;

use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function queryFilters(array $filters, &$query, &$selectArray)
    {
        foreach ($filters as $filter) {
            switch ($filter['op']) {
                case 'exists':
                    $query->whereExists(function($subQuery) use($filter) {
                        $subQuery->select(DB::raw($filter['select']));
                    });
                    break;
                case 'in':
                    if (is_array($filter['data'])) {
                        $query->whereIn($filter['field'], $filter['data']);
                    } else {
                        $query->whereRaw($filter['field'] . ' in (' . $filter['data'] . ')', isset($filter['params']) ? $filter['params'] : []);
                    }                    
                    break;
                case 'leftJoin':
                    if (is_array($filter['field'])) {
                        $query->leftJoin($filter['table'], function($join) use($filter) {
                            foreach($filter['field'] as $key => $value) {
                                $join->on($value, '=', $filter['on'][$key]);
                            }
                        });
                    } else {
                        $query->leftJoin($filter['table'], $filter['field'], '=',
                            $filter['on']);
                    }
                    $selectArray = array_merge($selectArray, $filter['select']);
                    break;
                case 'join':
                    if (is_array($filter['field'])) {
                        $query->join($filter['table'], function($join) use($filter) {
                            foreach($filter['field'] as $key => $value) {
                                $join->on($value, '=', $filter['on'][$key]);
                            }
                        });
                    } else {                    
                        $query->join($filter['table'], $filter['field'], '=',
                            $filter['on']);
                    }
                    $selectArray = array_merge($selectArray, $filter['select']);
                    break;
                case 'whereRaw':
                    $query->whereRaw($filter['data'], isset($filter['params']) ? $filter['params'] : []);
                    break;
                case 'having':
                    $query->having($filter['field'], $filter['have'], $filter['data']);
                    break;
                default:
                    $query->where($filter['field'], $filter['op'], $filter['data']);
                    break;
            }
        }        
    }
}
