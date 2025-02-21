<?php

namespace Hichamagm\IzagentShared\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait Filterable {

    protected $operatorWhereMap = [
        'eq' => '=',
        'lt' => '<',
        'ltq' => '<=',
        'lte' => '<=',
        'gt' => '>',
        'gtq' => '>=',
        'gte' => '>=',
    ];

    function scopeFilter($modelQuery, Request $request){

        if(count($request->all()) > 0){

            if(isset($this->safeParams)){
                foreach($this->safeParams as $param => $operators){
                    $query = $request->query($param);

                    if(!isset($query)){
                        continue;
                    }

                    $column = $this->columnMap[$param] ?? $param;
                    
                    foreach($operators as $operator){
                        if(isset($query[$operator])){
                            if(isset($this->operatorWhereMap[$operator])){
                                $where = [$column, $this->operatorWhereMap[$operator], $query[$operator]];
                                $modelQuery = $modelQuery->where([$where]);
                            }
                            elseif($operator == 'lk'){
                                $modelQuery = $modelQuery->where($column, 'like', '%'.$query[$operator].'%');
                            }
                            elseif($operator == 'in'){
                                $modelQuery = $modelQuery->whereIn($column, array_values($query[$operator]));
                            }
                            elseif($operator == 'notin'){
                                $modelQuery = $modelQuery->whereNotIn($column, array_values($query[$operator]));
                            }
                            elseif($operator == 'bt'){
                                $modelQuery = $modelQuery->whereBetween($column, array_values($query[$operator]));
                            }
                        }
                        elseif(!is_array($query) && $operator == "eq"){
                            $modelQuery = $modelQuery->where($column, $request->input($param));
                        }
                    }
                }
            }

            if(isset($this->filterableRelations)){
                foreach($this->filterableRelations as $relation => $columns){
                    $query = $request->query($relation);

                    if(!isset($query)){
                        continue;
                    }

                    foreach($columns as $column => $operators){
                        foreach($operators as $operator){
                            if(isset($query["has.$column.$operator"])){
                                $value = $query["has.$column.$operator"];
                                $dbColumn = $this->columnMap[$column] ?? $column;

                                $modelQuery = $modelQuery->whereHas($relation, function($q) use($dbColumn, $value, $operator) {
                                    $q->where([[$dbColumn, $this->operatorWhereMap[$operator],  $value]]);
                                });
                            }elseif(isset($query["hasNot.$column.$operator"])){
                                $value = $query["hasNot.$column.$operator"];
                                $dbColumn = $this->columnMap[$column] ?? $column;

                                $modelQuery = $modelQuery->whereDoesntHave($relation, function($q) use($dbColumn, $value, $operator) {
                                    $q->where([[$dbColumn, $this->operatorWhereMap[$operator],  $value]]);
                                });
                            }
                        }
                    }
                }
            }
            
            if($request->input('sort')){
                $sort = explode(':', $request->input('sort'));

                if(isset($this->columnMap[$sort[0]])){
                    $sortBy = isset($sort[1]) ? $sort[1] : 'desc'; 
                    $modelQuery = $modelQuery->orderby($this->columnMap[$sort[0]], $sortBy);
                }
                
            }

            $groupBy = $request->input('groupBy');

            if(is_array($groupBy)){
                foreach($groupBy as $column){
                    $modelQuery = $modelQuery->addSelect($groupBy);
                    $modelQuery = $modelQuery->groupBy($column);
                }
            }
            
            elseif($request->has('groupBy') && $groupBy !== ''){
                $modelQuery = $modelQuery->addSelect($groupBy);
                $modelQuery = $modelQuery->groupBy($groupBy);
            }
        }

        return $modelQuery;
    }

    public function scopeDefaultFilters($q)
    {
        if(app('access') == 'limited'){
            $q->forUser();
        }
    }
}
