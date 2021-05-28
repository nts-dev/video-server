<?php


interface Crud
{

    function findAll();

    function findById($id);

    function deleteById($id, $object);

    function edit($id, $object);

    function save($object);

}