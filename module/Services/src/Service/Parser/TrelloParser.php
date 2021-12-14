<?php

namespace Services\Service\Parser;

use Exception;
use Laminas\Filter\Word\UnderscoreToCamelCase;

/**
 * Парсинг Дошок з trello.com
 * https://developer.atlassian.com/cloud/trello/rest/api-group-actions/ Документація
 */
class TrelloParser implements ParseInterface
{
    private $mongoManager;
    private $entityManager;
    private $guzzleClient;
    private $config;

    const URL                 = 'https://api.trello.com/1/';
    const BOARDS              = 'boards';
    const CARDS               = 'cards';
    const LISTS               = 'lists';
    const ACTIONS             = 'actions'; // тут коментарі
    const LABELS              = 'labels'; // tags
    const MEMBERS             = 'members'; //список користувачів
    const CHECKLISTS          = 'checklists';
    const CUSTOM_FIELDS       = 'customFields';
    const CUSTOM_FIELDS_ITEMS = 'customFieldItems';
    const ATTACHMENTS         = 'attachments';
    const ORGANIZATIONS       = 'organizations';

    const ID_BOARD = 'vY2Q33uz';

    public function __construct($mongoManager, $entityManager, $guzzleClient, $config)
    {
        $this->mongoManager  = $mongoManager;
        $this->entityManager = $entityManager;
        $this->guzzleClient  = $guzzleClient;
        $this->config        = $config;
    }

    public function parse()
    {
        $dataBoard             = $this->getBoard(self::ID_BOARD);
        $dataBoardList         = $this->getBoardList(self::ID_BOARD);
        $dataBoardCards        = $this->getBoardCards(self::ID_BOARD);
        $dataBoardCustomFields = $this->getBoardCustomFields(self::ID_BOARD);
        $dataOrganization      = $this->getOrganization($dataBoard['idOrganization']);

        $dataCards             = $this->getCards($dataBoardCards[4]['id']);
        $dataCardsComments     = $this->getCardsComments($dataCards['id']);
        $dataCardsMembers      = $this->getCardsMembers($dataCards['id']);
        $dataCardsAttachments  = $this->getCardsAttachments($dataCards['id']);
        $dataCardsTags         = $this->getCardsTags($dataCards['id']);
        $dataCardsCustomFields = $this->getCardsCustomFields($dataCards['id']);
        $dataCheckList         = $this->getCheckList($dataCards['idChecklists'][0]);

        var_dump($dataCheckList);
    }

    public function import()
    {
        // TODO: Implement import() method.
    }

    private function getOrganization($idOrganization)
    {
        if ($idOrganization) {
            $response = $this->getResponseData('GET', self::URL . self::ORGANIZATIONS . '/' . $idOrganization, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці Організації' . $idOrganization);
        }
        return null;
    }

    private function getBoard($idBoard)
    {
        if ($idBoard) {
            $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці Дошки' . $idBoard);
        }
        return null;
    }

    private function getBoardCustomFields($idBoard)
    {
        if ($idBoard) {
            $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::CUSTOM_FIELDS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці Кастомних полів Дошки' . $idBoard);
        }
        return null;
    }

    private function getCheckList($idCheckList)
    {
        if ($idCheckList) {
            $response = $this->getResponseData('GET', self::URL . self::CHECKLISTS . '/' . $idCheckList, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці Переліку дошки' . $idCheckList);
        }
        return null;
    }

    private function getBoardList($idBoard)
    {
        if ($idBoard) {
            $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::LISTS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці списків дошки' . $idBoard);
        }

        return null;
    }

    private function getBoardCards($idBoard)
    {
        if ($idBoard) {
            $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::CARDS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці карток дошки' . $idBoard);
        }

        return null;
    }

    private function getCards($idCards)
    {
        if ($idCards) {
            $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці карток дошки' . $idCards);
        }

        return null;
    }

    private function getCardsMembers($idCards)
    {
       if ($idCards) {
           $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::MEMBERS, []);

           if (200 == $response->getStatusCode()) {
               return json_decode($response->getBody(), true);
           }

           throw new Exception('Сталася помилка при вичитці Учасників картки' . $idCards);
       }

       return null;
    }
    private function getCardsComments($idCards)
    {
        if ($idCards) {
            $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::ACTIONS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці Коментарів картки' . $idCards);
        }

        return null;
    }

    private function getCardsAttachments($idCards)
    {
        if ($idCards) {
            $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::ATTACHMENTS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці файлів картки' . $idCards);
        }
        return null;
    }

    private function getCardsTags($idCards)
    {
        if ($idCards) {
            $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::LABELS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці тегів картки' . $idCards);
        }
        return null;
    }

    private function getCardsCustomFields($idCards)
    {
        if ($idCards) {
            $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::CUSTOM_FIELDS_ITEMS, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці кастомних полів картки' . $idCards);
        }

        return null;
    }

    protected function getResponseData($method, $url, $args)
    {
        return $this->guzzleClient->request($method, $url, [
            'query' => $this->config['trello'],
            $args
        ]);
    }

}