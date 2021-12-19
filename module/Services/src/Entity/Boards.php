<?php

namespace Services\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="boards")
 */

class Boards
{
    /** @ODM\Id */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $serviceName;

    /**
     * @ODM\Field(type="string")
     */
    protected $OrganizationDisplayName;

    /**
     * @ODM\Field(type="string")
     */
    protected $boardOrganization;

    /**
     * @ODM\Field(type="string")
     */
    protected $boardName;

    /**
     * @ODM\Field(type="date")
     */
    protected $dateLastActivity;

    /**
     * @ODM\Field(type="string")
     */
    protected $cardDesc;

    /**
     * @ODM\Field(type="string")
     */
    protected $cardName;

    /**
     * @ODM\Field(type="boolean")
     */
    protected $isComplete;

    /**
     * @ODM\Field(type="date")
     */
    protected $isCompleteData;

    /**
     * @ODM\Field(type="string")
     */
    protected $cardBoard;

    /**
     * @ODM\Field(type="string")
     */
    protected $cardList;

    /**
     * @ODM\Field(type="collection")
     */
    protected $cardAttachments;

    /**
     * @ODM\Field(type="collection")
     */
    protected $tags;

    /**
     * @ODM\Field(type="collection")
     */
    protected $members;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName($serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return mixed
     */
    public function getOrganizationDisplayName()
    {
        return $this->OrganizationDisplayName;
    }

    /**
     * @param mixed $OrganizationDisplayName
     */
    public function setOrganizationDisplayName($OrganizationDisplayName): void
    {
        $this->OrganizationDisplayName = $OrganizationDisplayName;
    }

    /**
     * @return mixed
     */
    public function getBoardOrganization()
    {
        return $this->boardOrganization;
    }

    /**
     * @param mixed $boardOrganization
     */
    public function setBoardOrganization($boardOrganization): void
    {
        $this->boardOrganization = $boardOrganization;
    }

    /**
     * @return mixed
     */
    public function getBoardName()
    {
        return $this->boardName;
    }

    /**
     * @param mixed $boardName
     */
    public function setBoardName($boardName): void
    {
        $this->boardName = $boardName;
    }



    /**
     * @return mixed
     */
    public function getDateLastActivity()
    {
        return $this->dateLastActivity;
    }

    /**
     * @param mixed $dateLastActivity
     */
    public function setDateLastActivity($dateLastActivity): void
    {
        $this->dateLastActivity = $dateLastActivity;
    }

    /**
     * @return mixed
     */
    public function getCardDesc()
    {
        return $this->cardDesc;
    }

    /**
     * @param mixed $cardDesc
     */
    public function setCardDesc($cardDesc): void
    {
        $this->cardDesc = $cardDesc;
    }

    /**
     * @return mixed
     */
    public function getCardName()
    {
        return $this->cardName;
    }

    /**
     * @param mixed $cardName
     */
    public function setCardName($cardName): void
    {
        $this->cardName = $cardName;
    }

    /**
     * @return mixed
     */
    public function getIsComplete()
    {
        return $this->isComplete;
    }

    /**
     * @param mixed $isComplete
     */
    public function setIsComplete($isComplete): void
    {
        $this->isComplete = $isComplete;
    }

    /**
     * @return mixed
     */
    public function getIsCompleteData()
    {
        return $this->isCompleteData;
    }

    /**
     * @param mixed $isCompleteData
     */
    public function setIsCompleteData($isCompleteData): void
    {
        $this->isCompleteData = $isCompleteData;
    }

    /**
     * @return mixed
     */
    public function getCardBoard()
    {
        return $this->cardBoard;
    }

    /**
     * @param mixed $cardBoard
     */
    public function setCardBoard($cardBoard): void
    {
        $this->cardBoard = $cardBoard;
    }

    /**
     * @return mixed
     */
    public function getCardList()
    {
        return $this->cardList;
    }

    /**
     * @param mixed $cardList
     */
    public function setCardList($cardList): void
    {
        $this->cardList = $cardList;
    }

    /**
     * @return mixed
     */
    public function getCardAttachments()
    {
        return $this->cardAttachments;
    }

    /**
     * @param mixed $cardAttachments
     */
    public function setCardAttachments($cardAttachments): void
    {
        $this->cardAttachments = $cardAttachments;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param mixed $members
     */
    public function setMembers($members): void
    {
        $this->members = $members;
    }

}