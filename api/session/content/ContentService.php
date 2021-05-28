<?php

use session\Network;


class ContentService implements Crud, IModuleService
{

    const ENDPOINT = "modules";
    private Network $network;

    public function __construct()
    {
        $this->network = new Network();
    }

    function findAll()
    {
        return $this->network->invoke(
            self::ENDPOINT,
            \session\NetworkMethod::GET
        );
    }

    function findById($id)
    {
        assert($id != null);

        return $this->network->invoke(
            self::ENDPOINT . "/" . $id,
            \session\NetworkMethod::GET
        );
    }

    function deleteById($id, $object)
    {
        assert($object !== null);

        return $this->network->invoke(
            self::ENDPOINT . '/' . $id,
            \session\NetworkMethod::DELETE,
            $object
        );
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

    function findBySubject($id)
    {
        assert($id != null);


        return $this->network->invoke(
            self::ENDPOINT . "/subject/" . $id,
            \session\NetworkMethod::GET
        );
    }

}