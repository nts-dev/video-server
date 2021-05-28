<?php

namespace session\project\dao;
use Project;

interface ProjectDao
{
    function getAll();

    function findById(int $id);

    function delete(int $id);

    function save(Project $project);

}