<?php

namespace SharedIzAgent\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Analyticable {

    function scopeChartData($query, Request $request)
    {
        $column = $request->exists('column') ? $request->input('column') : 'DATE(created_at)';

        if(isset($this->safeParams[$column])){
            $query = $query->select(DB::raw($column.' as selected_column'), DB::raw('COUNT(*) as count'))
            ->groupBy('selected_column');
        }

        return $query->get()->pluck('count', 'selected_column');
    }

    public function scopeColumnsData($query, Request $request){

        $columns = [];

        foreach($request->input('columns') as $column){
            if(isset($this->safeParams[$column]) && !in_array($column, $columns)){
                array_push($columns, isset($this->columnMap[$column]) ? $this->columnMap[$column] : $column);
            }
        }

        $distinct = $request->input('distinct');
        $groupBy = $request->input('groupBy');
        $withCount = $groupBy ? $request->input('withCount') : false;
    
        $data = $withCount ? $query->select(DB::raw(implode(',', $columns)), DB::raw('COUNT(*) as count')) : $query->select($columns);
        if($distinct) $data->distinct();
        if($groupBy) $data = $data->groupBy($columns);
        $data = $data->get();

        return $data;
    }
}