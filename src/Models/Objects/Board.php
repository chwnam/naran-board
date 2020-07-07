<?php


namespace Naran\Board\Models\Objects;


class Board extends BaseObject
{
    private $enabled;

    private $postType;

    private $boardStyle;

    private $pageId;

    private $pageName;

    private $created;

    private $modified;

    public function __construct()
    {
        $this->enabled    = true;
        $this->postType   = '';
        $this->boardStyle = null;
        $this->pageId     = null;
        $this->pageName   = null;
        $this->created    = null;
        $this->modified   = null;
    }

    public static function getDefault()
    {
        return new static();
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return Board
     */
    public function setEnabled(bool $enabled): Board
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostType(): string
    {
        return $this->postType;
    }

    /**
     * @param string $postType
     *
     * @return Board
     */
    public function setPostType(string $postType): Board
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @return null
     */
    public function getBoardStyle()
    {
        return $this->boardStyle;
    }

    /**
     * @param null $boardStyle
     *
     * @return Board
     */
    public function setBoardStyle($boardStyle)
    {
        $this->boardStyle = $boardStyle;
        return $this;
    }

    /**
     * @return null
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param null $pageId
     *
     * @return Board
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }

    /**
     * @return null
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * @param null $pageName
     *
     * @return Board
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
        return $this;
    }

    /**
     * @return null
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param null $created
     *
     * @return Board
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return null
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param null $modified
     *
     * @return Board
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
        return $this;
    }
}
