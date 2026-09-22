<?php

header("Content-Type: application/json; charset=UTF-8");
$file = __DIR__ . "/form-responses.json";

// Récupérer les données envoyées
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Vérifier que les données sont valides
if (!$data) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Données invalides."
    ]);
    exit;
}

// Lire les réponses existantes
$responses = [];

if (file_exists($file)) {
    $content = file_get_contents($file);

    if ($content !== "") {
        $responses = json_decode($content, true);

        if (!is_array($responses)) {
            $responses = [];
        }
    }
}

// Ajouter la nouvelle réponse
$responses[] = $data;

// Sauvegarder le fichier JSON
$result = file_put_contents(
    $file,
    json_encode(
        $responses,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )
);

if ($result === false) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Impossible d'enregistrer la réponse."
    ]);
    exit;
}

// Réponse envoyée au JavaScript
echo json_encode([
    "success" => true,
    "message" => "Réponse enregistrée."
]);
