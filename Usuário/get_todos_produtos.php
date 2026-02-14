<?php

header('Content-Type: application/json');


include("config.php"); 

try {
    
    $stmt = $pdo->query("SELECT * FROM produto ORDER BY nome ASC");
    
    
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo json_encode($produtos);

} catch (PDOException $e) {
    
    http_response_code(500);
    
    echo json_encode(['erro' => 'Erro interno do servidor ao carregar produtos.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro interno.']);
}

exit; 
?>