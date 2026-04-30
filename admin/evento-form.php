<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$evento = null;
$erro   = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
    $evento = $stmt->fetch();
    if (!$evento) {
        header('Location: ' . BASE_URL . '/admin/eventos.php');
        exit;
    }
}

$categorias = $pdo->query("SELECT * FROM categorias_eventos ORDER BY ordem, nome")->fetchAll();

$titulo   = $evento['titulo']        ?? '';
$catId    = $evento['categoria_id']  ?? '';
$data     = $evento['data_evento']   ? date('Y-m-d\TH:i', strtotime($evento['data_evento'])) : '';
$localN   = $evento['local_nome']    ?? '';
$localE   = $evento['local_endereco']?? '';
$desc     = $evento['descricao']     ?? '';
$vagas    = $evento['vagas']         ?? '';
$status   = $evento['status']        ?? 'ativo';
$imagemAtual = $evento['imagem']     ?? '';

adminHeader($id ? 'Editar Evento' : 'Novo Evento', 'eventos');
?>

<div class="form-card">
    <?php if ($erro): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/admin/evento-salvar.php" enctype="multipart/form-data">
        <?php if ($id): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label>Título do evento *</label>
                <input type="text" name="titulo" required
                       value="<?= htmlspecialchars($titulo) ?>">
            </div>
            <div class="form-group">
                <label>Categoria *</label>
                <select name="categoria_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $catId == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Data e horário *</label>
                <input type="datetime-local" name="data_evento" required value="<?= $data ?>">
            </div>
            <div class="form-group">
                <label>Vagas (deixe em branco para ilimitado)</label>
                <input type="number" name="vagas" min="1"
                       value="<?= htmlspecialchars($vagas) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Local — Nome</label>
                <input type="text" name="local_nome"
                       value="<?= htmlspecialchars($localN) ?>" placeholder="Ex: Canoas/RS">
            </div>
            <div class="form-group">
                <label>Local — Endereço</label>
                <input type="text" name="local_endereco"
                       value="<?= htmlspecialchars($localE) ?>" placeholder="Endereço completo">
            </div>
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao"><?= htmlspecialchars($desc) ?></textarea>
        </div>

        <div class="form-group">
            <label>Imagem de capa</label>
            <?php if ($imagemAtual): ?>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px;">
                    Atual: <?= htmlspecialchars($imagemAtual) ?>
                </p>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($imagemAtual) ?>"
                     class="img-preview" style="display:block;max-width:200px;border-radius:8px;margin-bottom:8px;">
            <?php endif; ?>
            <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
            <p style="font-size:12px;color:#64748b;margin-top:5px;">
                JPG, PNG ou WebP. Tamanho ideal: 800×500px.
            </p>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="ativo"     <?= $status === 'ativo'     ? 'selected' : '' ?>>Ativo (visível no site)</option>
                <option value="inativo"   <?= $status === 'inativo'   ? 'selected' : '' ?>>Inativo (oculto)</option>
                <option value="encerrado" <?= $status === 'encerrado' ? 'selected' : '' ?>>Encerrado</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-admin btn-primary">
                <?= $id ? 'Salvar alterações' : 'Criar evento' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/eventos.php" class="btn-admin btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php adminFooter(); ?>
