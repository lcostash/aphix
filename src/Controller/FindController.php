<?php

namespace App\Controller;

use App\Core\Enum\ConstraintEnum;
use App\Core\FamaCore;
use App\Core\FamaResponse;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_SearchListResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class FindController extends MainController
{
    /**
     * FindController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @Route("/find", methods={"POST"}, requirements={"_format":"json"})
     * @param Request $request
     * @return FamaResponse
     * @throws Exception
     */
    public function findRows(Request $request): FamaResponse
    {
        try {
            $form = $this->jsonValidate($request->getContent());
            if (is_array($result = $this->formValidate((array)$form, $this->getConstraint(ConstraintEnum::FIND)))) {
                return new FamaResponse($result);
            }

            $rows = [];

            $container = FamaCore::getContainer();
            if ($container instanceof ContainerInterface) {
                $google = $container->getParameter('google');

                // search on youtube
                $client = new Google_Client();
                $client->setApplicationName('Aphix');
                $client->setDeveloperKey(isset($google['api_key']) ? $google['api_key'] : '');

                $service = new Google_Service_YouTube($client);
                $params = [
                    'q' => $form->find,
                    'maxResults' => 10
                ];
                $results = $service->search->listSearch('id,snippet', $params);
                if ($results instanceof Google_Service_YouTube_SearchListResponse) {
                    foreach ($results['items'] as $result) {
                        switch ($result['id']['kind']) {
                            case 'youtube#video':
                                $rows['videos'][] = [
                                    'id' => $result['id']['videoId'],
                                    'title' => $result['snippet']['title']
                                ];
                                break;
                            case 'youtube#channel':
                                $rows['channels'][] = [
                                    'id' => $result['id']['channelId'],
                                    'title' => $result['snippet']['title']
                                ];
                                break;
                            case 'youtube#playlist':
                                $rows['playlists'][] = [
                                    'id' => $result['id']['playlistId'],
                                    'title' => $result['snippet']['title']
                                ];
                                break;
                        }
                    }
                }
            }

            $data = [
                'status' => Response::HTTP_OK,
                'rows' => $rows
            ];

            return new FamaResponse($data);

        } catch (Exception $exception) {
            return new FamaResponse($exception);
        }
    }
}
