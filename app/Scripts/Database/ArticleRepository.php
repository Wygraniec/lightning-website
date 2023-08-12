<?php

namespace Scripts\Database;

require "../DatabaseConnector.php";

use http\Exception\InvalidArgumentException;
use Scripts\Classes\Article;

class ArticleRepository {
    private static self $singleton;

    public function getSingleton(): self {
        if(!isset(self::$singleton))
            self::$singleton = new self();

        return self::$singleton;
    }

    /**
     * Retrieves all articles from the database
     * @return array Array of all articles
     */
    public function getAll(): array {
        return iterator_to_array(
            DatabaseConnector::getSingleton()
            ->getQuery("SELECT * FROM `Articles`")
        );
    }

    /**
     * Retrieves data about a specific article from the database
     * @param int $id Id of the article in the database
     * @return Article Article of a given ID
     *
     * @throws InvalidArgumentException When article of specified ID does not exist
     * @throws \Exception When A DateTime object could not be created
     */
    public function fromId(int $id): Article {
        if(!in_array($id, $this->getIds()))
            throw new InvalidArgumentException("The provided ID is invalid");

        $data = DatabaseConnector::getSingleton()
            ->getQuery("SELECT * FROM Articles WHERE id = ?", $id)
            ->current();

        return new Article(
            $data['id'],
            $data['Title'],
            $data['Content'],
            new \DateTime($data['AddedAt'])
        );
    }

    /**
     * Deletes an article from the database
     * @param int $id Id of the article in the database
     * @throws InvalidArgumentException When article of specified ID does not exist
     */
    public function delete(int $id): void {
        if(!in_array($id, $this->getIds()))
            throw new InvalidArgumentException("The provided ID is invalid");

        DatabaseConnector::getSingleton()
            ->updateQuery("DELETE FROM `Articles` WHERE `id` = ?", $id);
    }

    private function getIds(): array {
        return iterator_to_array(
            DatabaseConnector::getSingleton()
                ->getQuery("SELECT DISTINCT `id` FROM `Articles`")
        );
    }
}