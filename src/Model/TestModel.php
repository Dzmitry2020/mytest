<?php


namespace Model;

use TexLab\MyDB\DbEntity;

class TestModel extends DbEntity

{
    public function getNames()
    {
        $res = [];
        foreach ($this->runSQL('SELECT id, name FROM place') as $row) {
            $res[$row['id']] = $row['name'];
        }
        return $res;
    }

    public function getStatus()
    {
        $res = [];
        foreach ($this->runSQL('SELECT id, name FROM taskstate') as $row) {
            $res[$row['id']] = $row['name'];
        }
        return $res;
    }

    public function getTask($pageSize, $page)
    {
        return $this
            ->setSelect('tasks.id, tasks.status, tasks.date, place.name,  tasks.content, tasks.comment')
            ->setFrom('tasks, place')
            ->setWhere('place.id = tasks.place_id')
            ->setOrderBy('tasks.date')
            ->setPageSize($pageSize)
            ->getPage($page);
    }

}