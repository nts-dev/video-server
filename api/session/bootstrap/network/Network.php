<?php

namespace session;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use session\config\Constants;
use session\auth\UserFlare;

class Network
{

    private Client $client;
    private UserFlare $user;

    public function __construct($BASE_URL = Constants::API_URL)
    {

        if (!isset($_SESSION) || !isset($_SESSION['USER'])) {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode(array("response" => false, "message" => 'Session Ended'));
            exit();
        }

        $current_session = unserialize($_SESSION["USER"]);
        $this->user = $current_session->getFlareUser();



        $this->client = new Client(
            [
                'base_uri' => $BASE_URL,
                'timeout' => 0
            ]
        );
    }



    public function invoke(string $URL, string $METHOD, $JSON = null)
    {
        try {
            $response = $this->client->request(
                $METHOD,
                $URL,
                [
                    'headers' =>
                        ['Authorization' => 'Bearer ' . $this->user->getToken(),],
                    'debug' => false,
                    'json' => $JSON
                ],
            );
//            echo $response->getStatusCode(); // 200
            $data = \GuzzleHttp\json_decode($response->getBody());
            return $data->data;

        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
        }
    }



    public function upload(string $URL, $postData)
    {
        try {
            $response = $this->client->request('POST', $URL, [
                'multipart' => [
                    [
                        'name' => 'subject_id',
                        'contents' => $postData['subject_id'],
                    ],
                    [
                        'name' => 'module_id',
                        'contents' => $postData['module_id'],
                    ],
                    [
                        'name' => 'title',
                        'contents' => $postData['title'],
                    ],
                    [
                        'name' => 'description',
                        'contents' => $postData['description'],
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen($postData['file']['tmp_name'], 'r'),
                        'filename' => $postData['file']['name']
                    ]

                ],
                'headers'  => [
                    'Authorization' =>  'Bearer ' . $this->user->getToken(),],
                'debug' => false,
            ]);
//            echo $response->getStatusCode(); // 200
//            $data = \GuzzleHttp\json_decode($response->getBody());
            $responseData = $response->getBody()->getContents();
            $result = json_decode($responseData);
            return $result;

        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
        }
    }

}