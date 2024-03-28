<?php

namespace Hichamagm\IzagentShared\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Analyticable {

    function scopeChartData($query, Request $request)
    {
        $inputs = $request->query('chart');
        $column = isset($inputs['groupedColumn']) ? $inputs['groupedColumn'] : 'DATE(created_at)';

        if(isset($this->safeParams[$column])){
            $query = $query->select(DB::raw($column.' as selected_column'), $this->chartDataTypeQuery($inputs));
            $query = $query->groupBy('selected_column');
        }

        return $query->get()->pluck("value", "selected_column");
    }

    private function chartDataTypeQuery($inputs){
        $query = "";
        if($inputs['type'] == 'count'){
            $query = DB::raw('COUNT(*) as value');
        }elseif($inputs['type'] == 'sum' && isset($inputs['sumColumn'])){
            $sumColumn = $inputs['sumColumn'];
            $query = DB::raw("SUM($sumColumn) as value");
        }

        return $query;
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