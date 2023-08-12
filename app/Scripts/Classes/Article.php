<?php

namespace Scripts\Classes;
require "../Database/DatabaseConnector.php";

use http\Exception\InvalidArgumentException;
use Scripts\Database\DatabaseConnector;
use DateTime;

class Article {
    private int $id;
    private string $title;
    private string $content;
    private DateTime $addedAt;


    public function __construct(int $id, string $title, string $content, DateTime $addedAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->addedAt = $addedAt;
    }

    /**
     * @return int Id of the article in the DB
     */
    public function getId(): int{
        return $this->id;
    }
    /**
     * @return string Title of the article
     */
    public function getTitle(): string{
        return $this->title;
    }
    /**
     * @return string Content of the article
     */
    public function getContent(): string {
        return $this->content;
    }
    /**
     * @return DateTime Date when the article was added
     */
    public function getAddedAt(): DateTime {
        return $this->addedAt;
    }


}
