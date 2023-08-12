<?php

namespace Scripts\Database;

require_once "DatabaseConnector.php";

class RegistrationsRepository {
    private static self $singleton;

    public static function getSingleton(): self {
        if (!isset(self::$singleton))
            self::$singleton = new self();

        return self::$singleton;
    }

    public function register(string $name, string|null $surname, string $guardian, string $email,
                             string $phone, int $courseId, string $englishKnowledge): void {
        $query = "INSERT INTO `Registrations`(`Name`, `Surname`";
        $phone = str_replace(" ", "", $phone);

        if($guardian != null)
            $query .= ", `LegalGuardian`";

        $query .= ", `EMail`, `Phone`, `EnglishExperience`, `CourseID`)";

        $query .= "VALUES(?, ?, ?, ?, ?, ?";

        if($guardian != null)
            $query .= ", ?";

        $query .= ");";


        if($guardian == null)
            DatabaseConnector::getSingleton()
                ->updateQuery($query, $name, $surname, $email, $phone, $englishKnowledge, $courseId);
        else
            DatabaseConnector::getSingleton()
                ->updateQuery($query, $name, $surname, $guardian, $email, $phone, $englishKnowledge, $courseId);

    }
}