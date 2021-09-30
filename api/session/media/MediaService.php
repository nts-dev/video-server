<?php


use session\Network;

class MediaService implements Crud, IMediaService
{
    const ENDPOINT = "videos";

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

        return $this->network->upload(
            self::ENDPOINT,
//            \session\NetworkMethod::POST,
            $object
        );

    }

    function findByCategory($category)
    {
        assert($category != null);

        return $this->network->invoke(
            self::ENDPOINT . "/category/" . $category,
            \session\NetworkMethod::GET
        );

    }

    function findByHashing($hash)
    {
        assert($hash != null);

        return $this->network->invoke(
            self::ENDPOINT . "/hash/" . $hash,
            \session\NetworkMethod::GET
        );
    }

    function encodeMedia($id)
    {
        assert($id != null);

        return $this->network->invoke(
            self::ENDPOINT . "/encode/" . $id,
            \session\NetworkMethod::GET
        );
    }
}