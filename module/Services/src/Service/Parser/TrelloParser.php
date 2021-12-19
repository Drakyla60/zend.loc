<?php

namespace Services\Service\Parser;

use Exception;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Services\Entity\Boards;

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
    const BOARD               = 'board';
    const CARDS               = 'cards';
    const LISTS               = 'lists';
    const ACTIONS             = 'actions'; // тут коментарі
    const LABELS              = 'labels';
    const MEMBERS             = 'members';
    const CHECKLISTS          = 'checklists';
    const CUSTOM_FIELDS       = 'customFields';
    const CUSTOM_FIELDS_ITEMS = 'customFieldItems';
    const ATTACHMENTS         = 'attachments';
    const ORGANIZATIONS       = 'organizations';

//    const ID_BOARD = 'i6KEgCk9';
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
        $card = [];
        foreach ($this->getBoardCards(self::ID_BOARD) as $dataCard) {
            $data = [
                'dateLastActivity' => $dataCard['dateLastActivity'] ?: null,
                'cardDesc'         => $dataCard['desc'] ?: null,
                'cardName'         => $dataCard['name'] ?: null,
                'isComplete'       => $dataCard['dueComplete'] ?: null,
                'isCompleteData'   => $dataCard['due'] ?: null,
                'cardBoard'        => $this->getBoard($dataCard['idBoard'])['desc'] ?: null,
                'cardList'         => $this->getBoardList($dataCard['idList'])['name'] ?: null,
                'cardAttachments'  => $this->getArrayAttachments($dataCard['id']) ?: null,
                'tags'             => $this->getTagsAsArray($dataCard['idLabels']) ?: null,
                'members'          => $this->getMembersAsArray($dataCard['idMembers']) ?: null
            ];

            $card[] = array_merge($this->getBoardInfo(), $data);
        }

        foreach ($card as $item) {
            $board = new Boards();

            $board->setServiceName($item['serviceName']);
            $board->setOrganizationDisplayName($item['boardOrganizationDisplayName']);
            $board->setBoardOrganization($item['boardOrganization']);
            $board->setBoardName($item['boardName']);
            $board->setDateLastActivity($item['dateLastActivity']);
            $board->setCardDesc($item['cardDesc']);
            $board->setCardName($item['cardName']);
            $board->setIsComplete($item['isComplete']);
            $board->setIsCompleteData($item['isCompleteData']);
            $board->setCardBoard($item['cardBoard']);
            $board->setCardList($item['cardList']);
            $board->setCardAttachments($item['cardAttachments']);
            $board->setTags($item['tags']);
            $board->setMembers($item['members']);

            $this->mongoManager->persist($board);

            $this->mongoManager->flush();
        }

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

    private function getBoardList($idList)
    {
        if ($idList) {
            $response = $this->getResponseData('GET', self::URL . self::LISTS . '/' . $idList , []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці списку дошки' . $idList);
        }

        return null;
    }

    private function getBoardLists($idBoard)
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

    private function getMember($idMember)
    {
       if ($idMember) {
           $response = $this->getResponseData('GET', self::URL . self::MEMBERS . '/' . $idMember , []);

           if (200 == $response->getStatusCode()) {
               return json_decode($response->getBody(), true);
           }

           throw new Exception('Сталася помилка при вичитці автора картки' . $idMember);
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

    private function getTag($idTag)
    {
        if ($idTag) {
            $response = $this->getResponseData('GET', self::URL . self::LABELS . '/' . $idTag, []);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception('Сталася помилка при вичитці тега картки' . $idTag);
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

    /**
     * @param $dataBoardList
     * @return array
     */
    private function getBoardListsName($dataBoardList): array
    {
        $boardList = [];
        foreach ($dataBoardList as $board) {
            $boardList[] = $board['name'];
        }
        return $boardList;
    }

    /**
     * @param $id
     * @return array|null
     * @throws Exception
     */
    private function getArrayAttachments($id): ?array
    {
        if ($id) {
            foreach ($this->getCardsAttachments($id) as $cardsAttachment) {

                $member = $this->getMember($cardsAttachment['idMember']);

                $attachments[] = [
                    'bytes' => $cardsAttachment['bytes'],
                    'member' => [
                        'fullName' => $member['fullName'],
                        'username' => $member['username'],
                        'aaEmail' => $member['aaEmail'],
                    ],
                    'mimeType' => $cardsAttachment['mimeType'],
                    'name' => $cardsAttachment['name'],
                    'url' => $cardsAttachment['url'],
                ];
            }
            return $attachments;
        }
        return null;
    }

    /**
     * @param $idLabels
     * @return array|null
     * @throws Exception
     */
    private function getTagsAsArray($idLabels): ?array
    {
        if (null != $idLabels) {
            foreach ($idLabels as $tag) {
                $tagsName[] = $this->getTag($tag)['name'];
            }
            return $tagsName;
        }
       return null;
    }

    /**
     * @param $idMembers
     * @return array|null
     * @throws Exception
     */
    private function getMembersAsArray($idMembers): ?array
    {
        if ($idMembers) {
            foreach ($idMembers as $member) {
                $members[] = $this->getMember($member)['fullName'];
            }

            return $members;
        }

        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getBoardInfo(): array
    {
        $dataBoard = $this->getBoard(self::ID_BOARD);
        $dataBoardList = $this->getBoardLists(self::ID_BOARD);
//        $dataBoardCustomFields = $this->getBoardCustomFields(self::ID_BOARD);
        $dataOrganization = $this->getOrganization($dataBoard['idOrganization']);


        return [
            'serviceName'                  => 'Trello',
            'boardOrganizationDisplayName' => $dataOrganization['displayName'],
            'boardOrganization'            => $dataOrganization['name'],
            'boardName'                    => $dataBoard['name'],
//            'boardList'                    => $this->getBoardListsName($dataBoardList),
        ];
    }

}