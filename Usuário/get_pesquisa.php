<?php
include("config.php");
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["q"]) || empty($_GET["q"])) {
    echo json_encode(["erro" => "Nenhum termo de pesquisa informado."]);
    exit;
}

$q = "%" . $_GET["q"] . "%";

$stmt = $pdo->prepare("SELECT * FROM produto WHERE nome LIKE ? OR descricao LIKE ?");
$stmt->execute([$q, $q]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultados);
?>