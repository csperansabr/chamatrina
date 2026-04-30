<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$busca  = trim($_GET['busca'] ?? '');
$status = $_GET['status'] ?? '';

$where  = ['1=1'];
$params = [];

if ($busca) {
    $where[]  = '(p.nome LIKE ? OR p.email LIKE ? OR p.cpf LIKE ?)';
    $like     = "%$busca%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (in_array($status, ['completo', 'incompleto'])) {
    $where[]  = 'a.status = ?';
    $params[] = $status;
}

$sql = "
    SELECT
        p.id,
        p.nome,
        p.email,
        p.whatsapp,
        p.data_nascimento,
        p.criado_em,
        a.id        AS anamnese_id,
        a.status    AS ficha_status,
        a.atualizado_em
    FROM participantes p
    LEFT JOIN anamneses a ON a.participante_id = p.id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY p.nome ASC
";

$lista        = [];
$erroTabela   = false;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $lista = $stmt->fetchAll();
} catch (PDOException $e) {
    $erroTabela = true;
}

adminHeader('Fichas de Anamnese', 'anamneses');
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <p style="color:#94a3b8;margin:0;">
        <?= count($lista) ?> participante(s) encontrado(s)
    </p>
</div>

<!-- Filtros -->
<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
    <input type="text" name="busca" placeholder="Buscar por nome, e-mail ou CPF…"
           value="<?= htmlspecialchars($busca) ?>"
           style="flex:1;min-width:220px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
    <select name="status"
            style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
        <option value="">Todos os status</option>
        <option value="completo"   <?= $status === 'completo'   ? 'selected' : '' ?>>Completo</option>
        <option value="incompleto" <?= $status === 'incompleto' ? 'selected' : '' ?>>Incompleto</option>
    </select>
    <button type="submit" class="btn-action btn-edit">Filtrar</button>
    <?php if ($busca || $status): ?>
        <a href="anamneses.php" class="btn-action" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;">Limpar</a>
    <?php endif; ?>
</form>

<?php if ($erroTabela): ?>
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:24px 28px;color:#fca5a5;">
        <strong>Tabelas não encontradas.</strong><br>
        As tabelas de anamnese ainda não foram criadas no banco de dados.<br><br>
        <a href="<?= BASE_URL ?>/setup_anamnese.php"
           style="color:#f87171;text-decoration:underline;">→ Executar setup_anamnese.php para criar as tabelas</a>
    </div>
<?php elseif (empty($lista)): ?>
    <div style="text-align:center;padding:60px 0;color:#64748b;">
        <p style="font-size:15px;">Nenhum participante cadastrado ainda.</p>
        <p style="font-size:13px;margin-top:8px;">Os participantes aparecerão aqui assim que se registrarem em
            <a href="<?= BASE_URL ?>/anamnese/registro.php" target="_blank" style="color:#8b5cf6;">/anamnese/registro.php</a>.
        </p>
    </div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>WhatsApp</th>
                <th>Nascimento</th>
                <th>Ficha</th>
                <th>Atualização</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($lista as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td>
                    <?php if ($p['whatsapp']): ?>
                        <a href="https://wa.me/55<?= preg_replace('/\D/', '', $p['whatsapp']) ?>"
                           target="_blank" style="color:#25D366;text-decoration:none;">
                            <?= htmlspecialchars($p['whatsapp']) ?>
                        </a>
                    <?php else: ?>
                        <span style="color:#475569;">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= $p['data_nascimento']
                        ? date('d/m/Y', strtotime($p['data_nascimento']))
                        : '<span style="color:#475569;">—</span>' ?>
                </td>
                <td>
                    <?php if (!$p['anamnese_id']): ?>
                        <span style="background:rgba(100,116,139,0.15);color:#64748b;padding:3px 10px;border-radius:12px;font-size:12px;">Não preenchida</span>
                    <?php elseif ($p['ficha_status'] === 'completo'): ?>
                        <span style="background:rgba(34,197,94,0.12);color:#4ade80;padding:3px 10px;border-radius:12px;font-size:12px;">Completa</span>
                    <?php else: ?>
                        <span style="background:rgba(234,179,8,0.12);color:#fbbf24;padding:3px 10px;border-radius:12px;font-size:12px;">Incompleta</span>
                    <?php endif; ?>
                </td>
                <td style="color:#94a3b8;font-size:13px;">
                    <?= $p['atualizado_em']
                        ? date('d/m/Y H:i', strtotime($p['atualizado_em']))
                        : '<span style="color:#475569;">—</span>' ?>
                </td>
                <td>
                    <?php if ($p['anamnese_id']): ?>
                        <a href="anamnese-ver.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Ver ficha</a>
                    <?php else: ?>
                        <span style="color:#475569;font-size:13px;">sem ficha</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php adminFooter(); ?>
