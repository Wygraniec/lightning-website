<?php

if (!isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone'], $_POST['course'], $_POST['experience'])) {
    header("../../");
    exit("All fields are required!");
}

require "../Database/RegistrationsRepository.php";
use Scripts\Database\RegistrationsRepository;

$data = [
    'name' => $_POST['name'],
    'surname' => $_POST['surname'],
    'guardian' => $_POST['guardian'] ?? null,
    'email' => $_POST['email'],
    'phone' => $_POST['phone'],
    'course' => $_POST['course'],
    'englishKnowledge' => $_POST['experience'],
];

RegistrationsRepository::getSingleton()
    ->register(
        $data['name'],
        $data['surname'],
        $data['guardian'],
        $data['email'],
        $data['phone'],
        $data['course'],
        $data['englishKnowledge']
    );

header("location: ../../");
