<?php

namespace session\project\dao;

use mysql_xdevapi\Exception;
use PDO;
use Project;

class ProjectDaoImpl implements ProjectDao
{

    private $executor;

    public function __construct()
    {
        $this->executor = new ProjectQueryExecutor();
    }

    function getAll(): array
    {
        $result = $this->executor->findAll();
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    function findById(int $id)
    {
        // TODO: Implement findById() method.
    }

    function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    function save(Project $project)
    {
        // TODO: Implement save() method.
    }



}