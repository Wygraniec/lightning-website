<?php

namespace Scripts\Database;

require "DatabaseConnector.php";

class CoursesRepository {
    private static self $singleton;

    public static function getSingleton(): self{
        if (!isset(self::$singleton))
            self::$singleton = new self();

        return self::$singleton;
    }

    public function getCourses(): array {
        $data = DatabaseConnector::getSingleton()
            ->getQuery("SELECT * FROM `Courses`");

        $courses = [];

        foreach ($data as $course)
            $courses[$course['id']] = $course['Name'];


        return $courses;
    }

}