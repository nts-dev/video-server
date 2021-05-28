<?php

namespace session\project;

use Crud;
use session\config\Constants;
use session\Network;
use session\project\dao\ProjectDao;
use session\project\dao\ProjectDaoImpl;

class ProjectService implements Crud
{

    private $projectDao;

    public function __construct()
    {

        $this->projectDao = new ProjectDaoImpl();

    }

    function findAll()
    {
        return $this->projectDao->getAll();
    }

    function findById($id)
    {
        // TODO: Implement findById() method.
    }

    function deleteById($id, $object)
    {
        // TODO: Implement deleteById() method.
    }

    function edit($id, $object)
    {
        // TODO: Implement edit() method.
    }

    function save($object)
    {
        // TODO: Implement save() method.
    }


}