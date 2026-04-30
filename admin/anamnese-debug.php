<?php
// ARQUIVO TEMPORÁRIO DE DIAGNÓSTICO — DELETE APÓS USO
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo '<pre style="font-family:monospace;background:#0f172a;color:#e2e8f0;padding:20px;font-size:13px;">';
echo "=== DIAGNÓSTICO — anamneses.php ===\n\n";

// Passo 1: config
echo "1. Carregando config.php... ";
require_once __DIR__ . '/../includes/config.php';
echo "OK\n";

// Passo 2: db
echo "2. Carregando db.php / conectando banco... ";
require_once __DIR__ . '/../includes/db.php';
echo "OK\n";

// Passo 3: auth (sem redirecionar)
echo "3. Verificando sessão admin... ";
if (session_status() === PHP_SESSION_NONE) session_start();
echo (empty($_SESSION['admin_logado']) ? "NÃO logado (seria redirecionado)" : "Logado") . "\n";

// Passo 4: layout
echo "4. Carregando layout.php... ";
require_once __DIR__ . '/includes/layout.php';
echo "OK — funções adminHeader e adminFooter: " . (function_exists('adminHeader') ? 'existem' : 'NÃO EXISTEM') . "\n";

// Passo 5: tabela participantes
echo "5. Verificando tabela participantes... ";
try {
    $r = $pdo->query("SELECT COUNT(*) FROM participantes");
    echo "OK — " . $r->fetchColumn() . " registro(s)\n";
} catch (PDOException $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}

// Passo 6: tabela anamneses
echo "6. Verificando tabela anamneses... ";
try {
    $r = $pdo->query("SELECT COUNT(*) FROM anamneses");
    echo "OK — " . $r->fetchColumn() . " registro(s)\n";
} catch (PDOException $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}

// Passo 7: query completa
echo "7. Executando query principal... ";
try {
    $stmt = $pdo->query("
        SELECT p.id, p.nome, p.email, p.whatsapp, p.data_nascimento, p.criado_em,
               a.id AS anamnese_id, a.status AS ficha_status, a.atualizado_em
        FROM participantes p
        LEFT JOIN anamneses a ON a.participante_id = p.id
        ORDER BY p.nome ASC
    ");
    $lista = $stmt->fetchAll();
    echo "OK — " . count($lista) . " participante(s)\n";
} catch (PDOException $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}

// Passo 8: coluna criado_em existe?
echo "8. Verificando coluna 'criado_em' em participantes... ";
try {
    $cols = $pdo->query("SHOW COLUMNS FROM participantes")->fetchAll(PDO::FETCH_ASSOC);
    $nomes = array_column($cols, 'Field');
    echo implode(', ', $nomes) . "\n";
} catch (PDOException $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO DIAGNÓSTICO ===\n";
echo '</pre>';
echo '<p style="font-family:sans-serif;color:red;padding:20px;"><strong>DELETE este arquivo do servidor após usar!</strong></p>';
