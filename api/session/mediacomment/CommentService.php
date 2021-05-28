<?php


use session\Network;

class CommentService implements Crud
{

    const ENDPOINT = "comments";
    private Network $network;

    public function __construct()
    {
        $this->network = new Network();
    }

    function findAll()
    {
        // TODO: Implement findAll() method.
    }

    function findById($id)
    {
        if ($id === null)
            return;
        return $this->network->invoke(
            self::ENDPOINT . "/" . $id,
            \session\NetworkMethod::GET
        );
    }

    function deleteById($id, $object)
    {
        // TODO: Implement deleteById() method.
    }

    function edit($id, $object)
    {
        assert($object != null);

        return $this->network->invoke(
            self::ENDPOINT . '/' . $id,
            \session\NetworkMethod::PUT,
            $object
        );
    }

    function save($object)
    {
        assert($object != null);

        return $this->network->invoke(
            self::ENDPOINT,
            \session\NetworkMethod::POST,
            $object
        );
    }
}