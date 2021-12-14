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
        $this->mongoManager = $mongoManager;
        $this->entityManager = $entityManager;
        $this->guzzleClient = $guzzleClient;
        $this->config = $config;
    }

    public function parse()
    {
        $dataBoard             = $this->getBoard(self::ID_BOARD);
        $dataBoardList         = $this->getBoardList(self::ID_BOARD);
        $dataBoardCards        = $this->getBoardCards(self::ID_BOARD);
        $dataBoardCustomFields = $this->getBoardCustomFields(self::ID_BOARD);
        $dataOrganization      = $this->getOrganization($dataBoard['idOrganization']);

        $dataCards             = $this->getCards($dataBoardCards[1]['id']);
        $dataCardsComments     = $this->getCardsComments($dataCards['id']);
        $dataCardsMembers      = $this->getCardsMembers($dataCards['id']);
        $dataCardsAttachments  = $this->getCardsAttachments($dataCards['id']);
        $dataCardsTags         = $this->getCardsTags($dataCards['id']);
        $dataCardsCustomFields = $this->getCardsCustomFields($dataCards['id']);
        $dataCheckList         = $this->getCheckList($dataCards['idChecklists'][0]);
    }

    public function import()
    {
        // TODO: Implement import() method.
    }

    private function getOrganization($idOrganization)
    {
        $response = $this->getResponseData('GET', self::URL . self::ORGANIZATIONS . '/' . $idOrganization, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Організації' . $idOrganization);
        }
    }

    private function getBoard($idBoard)
    {
        $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Дошки' . $idBoard);
        }
    }

    private function getBoardCustomFields($idBoard)
    {
        $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::CUSTOM_FIELDS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Кастомних полів Дошки' . $idBoard);
        }
    }

    private function getCheckList($idCheckList)
    {
        $response = $this->getResponseData('GET', self::URL . self::CHECKLISTS . '/' . $idCheckList, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Переліку дошки' . $idCheckList);
        }
    }

    private function getBoardList($idBoard)
    {
        $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::LISTS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці списків дошки' . $idBoard);
        }
    }

    private function getBoardCards($idBoard)
    {
        $response = $this->getResponseData('GET', self::URL . self::BOARDS . '/' . $idBoard . '/' . self::CARDS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці карток дошки' . $idBoard);
        }
    }

    private function getCards($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці карток дошки' . $idCards);
        }
    }

    private function getCardsMembers($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::MEMBERS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Учасників картки' . $idCards);
        }
    }
    private function getCardsComments($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::ACTIONS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці Коментарів картки' . $idCards);
        }
    }

    private function getCardsAttachments($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::ATTACHMENTS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці файлів картки' . $idCards);
        }
    }

    private function getCardsTags($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::LABELS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці тегів картки' . $idCards);
        }
    }

    private function getCardsCustomFields($idCards)
    {
        $response = $this->getResponseData('GET', self::URL . self::CARDS . '/' . $idCards . '/' . self::CUSTOM_FIELDS_ITEMS, []);

        if (200 == $response->getStatusCode()) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception('Сталася помилка при вичитці кастомних полів картки' . $idCards);
        }
    }

    protected function getResponseData($method, $url, $args)
    {
        return $this->guzzleClient->request($method, $url, [
            'query' => $this->config['trello'],
            $args
        ]);
    }

}