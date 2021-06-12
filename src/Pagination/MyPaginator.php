<?php


namespace App\Pagination;


class MyPaginator
{
    public function paginate($data, $pageNumber): array
    {
        $pagination = [];
        $pages = $data->pagination->pages;
        $pagination['page'] = $pageNumber;
        $pagination['pages'] = $pages;

        return $pagination;
    }
}