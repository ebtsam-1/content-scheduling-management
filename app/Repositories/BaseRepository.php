<?php

namespace App\Repositories;


class BaseRepository
{
    public function __construct(private $model, private $searchColumn = '')
    {
    }

    public function get($search = false, array $filters = [],
      $with = false, $paginate = true, $pageNum = 15, $dates = [])
    {
        $filters = $this->prepareFilters($filters);
        $query = $this->model->where($filters);
        $query = $search != false && $this->searchColumn != '' ? $query->where($this->searchColumn, $search) : $query;
        $with == false ? $query : $query = $query->with($with);
        $query = $this->prepareDates($dates, $query);
        $query = $query->orderBy('id', 'desc');
        return $paginate == false ? $query->get() : $query->paginate($pageNum);
    }

    public function show($by, $column = 'id')
    {
       return $this->model->where($column, $by)->first();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($by , $data, $column = 'id')
    {
        $this->model->where($column, $by)->update($data);
    }

    public function destroy($by, $column = 'id')
    {
        $this->model->where($column, $by)->delete();
    }

    public function prepareFilters($filters)
    {
        return array_diff($filters, ['*']);
    }

    public function prepareDates($dates, $query)
    {
        if(empty($dates)) {
            return $query;
        }

        if(isset($dates['start_date']) && isset($dates['end_date'])) {
            $query->whereBetween('created_at', [$dates['start_date'], $dates['end_date']]);
            return $query;
        }

        if(isset($dates['start_date'])) {
            $query->where('created_at' , '>=' , $dates['start_date']);
            return $query;
        }

        if(isset($dates['end_date'])) {
            $query->where('created_at' , '<=' , $dates['end_date']);
            return $query;
        }
    }
}


?>
