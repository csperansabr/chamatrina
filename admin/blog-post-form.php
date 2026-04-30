<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if (!$post) {
        header('Location: ' . BASE_URL . '/admin/blog-posts.php');
        exit;
    }
}

$categorias = $pdo->query("SELECT * FROM blog_categorias ORDER BY nome")->fetchAll();
$autores    = $pdo->query("SELECT id, nome, email FROM admin_usuarios ORDER BY nome, email")->fetchAll();
$adminAtual = $_SESSION['admin_id'] ?? 0;

$titulo      = $post['titulo']       ?? '';
$slug        = $post['slug']         ?? '';
$resumo      = $post['resumo']       ?? '';
$conteudo    = $post['conteudo']     ?? '';
$catId       = $post['categoria_id'] ?? '';
$autorId     = $post['autor_id']     ?? $adminAtual;
$status      = $post['status']       ?? 'rascunho';
$publicadoEm = $post['publicado_em'] ? date('Y-m-d\TH:i', strtotime($post['publicado_em'])) : '';
$imagemAtual = $post['imagem_capa']  ?? '';

adminHeader($id ? 'Editar Post' : 'Novo Post', 'blog-posts');
?>

<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">

<style>
.ql-toolbar { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12) !important; border-radius: 8px 8px 0 0; }
.ql-container { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.12) !important; border-radius: 0 0 8px 8px; min-height: 320px; font-size: 15px; color: #e2e8f0; }
.ql-editor.ql-blank::before { color: #475569; font-style: normal; }
.ql-toolbar button, .ql-toolbar .ql-picker { color: #94a3b8 !important; }
.ql-toolbar button:hover, .ql-toolbar button.ql-active { color: #c4b5fd !important; }
.ql-toolbar .ql-stroke { stroke: #94a3b8 !important; }
.ql-toolbar button:hover .ql-stroke, .ql-toolbar button.ql-active .ql-stroke { stroke: #c4b5fd !important; }
.ql-toolbar .ql-fill { fill: #94a3b8 !important; }
.ql-picker-options { background: #1e293b !important; border-color: rgba(255,255,255,0.12) !important; }
.ql-picker-item { color: #e2e8f0 !important; }
</style>

<div class="form-card" style="max-width:900px;">
    <form method="POST" action="<?= BASE_URL ?>/admin/blog-post-salvar.php"
          enctype="multipart/form-data" id="post-form">
        <?php if ($id): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>

        <!-- Título -->
        <div class="form-group">
            <label>Título *</label>
            <input type="text" name="titulo" id="input-titulo" required
                   value="<?= htmlspecialchars($titulo) ?>"
                   placeholder="Título do post"
                   style="font-size:18px;font-weight:600;">
        </div>

        <!-- Slug -->
        <div class="form-group">
            <label>Slug (URL) <span style="color:#64748b;font-size:12px;">gerado automaticamente</span></label>
            <input type="text" name="slug" id="input-slug" required
                   value="<?= htmlspecialchars($slug) ?>"
                   placeholder="url-do-post"
                   pattern="[a-z0-9-]+">
            <p style="font-size:12px;color:#64748b;margin-top:4px;">
                URL: <?= BASE_URL ?>/blog-post.php?slug=<span id="slug-preview"><?= htmlspecialchars($slug) ?></span>
            </p>
        </div>

        <!-- Resumo -->
        <div class="form-group">
            <label>Resumo <span style="color:#64748b;font-size:12px;">exibido na listagem (máx. 200 caracteres)</span></label>
            <textarea name="resumo" maxlength="200" rows="3"
                      placeholder="Breve descrição do post…"><?= htmlspecialchars($resumo) ?></textarea>
        </div>

        <!-- Editor de conteúdo -->
        <div class="form-group">
            <label>Conteúdo *</label>
            <div id="editor"><?= $conteudo ?></div>
            <input type="hidden" name="conteudo" id="conteudo-hidden">
        </div>

        <div class="form-row">
            <!-- Categoria -->
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id">
                    <option value="">Sem categoria</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $catId == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Autor -->
            <div class="form-group">
                <label>Autor</label>
                <select name="autor_id">
                    <?php foreach ($autores as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $autorId == $a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nome'] ?: $a['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="select-status">
                    <option value="rascunho"  <?= $status === 'rascunho'  ? 'selected' : '' ?>>Rascunho (não visível)</option>
                    <option value="publicado" <?= $status === 'publicado' ? 'selected' : '' ?>>Publicar agora</option>
                    <option value="agendado"  <?= $status === 'agendado'  ? 'selected' : '' ?>>Agendar publicação</option>
                </select>
            </div>

            <!-- Data de publicação -->
            <div class="form-group" id="campo-data" style="<?= $status !== 'agendado' ? 'opacity:.4;pointer-events:none;' : '' ?>">
                <label>Data de publicação</label>
                <input type="datetime-local" name="publicado_em" value="<?= $publicadoEm ?>">
                <p style="font-size:12px;color:#64748b;margin-top:4px;">
                    Obrigatório para posts agendados.
                </p>
            </div>
        </div>

        <!-- Imagem de capa -->
        <div class="form-group">
            <label>Imagem de capa</label>
            <?php if ($imagemAtual): ?>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($imagemAtual) ?>"
                     style="display:block;max-width:280px;border-radius:8px;margin-bottom:10px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:#94a3b8;cursor:pointer;margin-bottom:10px;">
                    <input type="checkbox" name="remover_imagem" value="1"> Remover imagem atual
                </label>
            <?php endif; ?>
            <input type="file" name="imagem_capa" accept="image/jpeg,image/png,image/webp">
            <p style="font-size:12px;color:#64748b;margin-top:5px;">JPG, PNG ou WebP. Tamanho ideal: 1200×630px.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-admin btn-primary">
                <?= $id ? 'Salvar alterações' : 'Criar post' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/blog-posts.php" class="btn-admin btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
// Editor Quill
var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Escreva o conteúdo do post aqui…',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['blockquote', 'link'],
            [{ align: [] }],
            ['clean']
        ]
    }
});

// Ao submeter, copia o HTML do editor para o campo oculto
document.getElementById('post-form').addEventListener('submit', function () {
    document.getElementById('conteudo-hidden').value = quill.root.innerHTML;
});

// Slug automático a partir do título
var tituloInput = document.getElementById('input-titulo');
var slugInput   = document.getElementById('input-slug');
var slugPreview = document.getElementById('slug-preview');
var slugEditado = <?= $id ? 'true' : 'false' ?>;

tituloInput.addEventListener('input', function () {
    if (slugEditado) return;
    var s = this.value.toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    slugInput.value   = s;
    slugPreview.textContent = s;
});
slugInput.addEventListener('input', function () {
    slugEditado = true;
    slugPreview.textContent = this.value;
});

// Habilita/desabilita campo de data conforme status
document.getElementById('select-status').addEventListener('change', function () {
    var campoData = document.getElementById('campo-data');
    if (this.value === 'agendado') {
        campoData.style.opacity = '1';
        campoData.style.pointerEvents = 'auto';
    } else {
        campoData.style.opacity = '.4';
        campoData.style.pointerEvents = 'none';
    }
});
</script>

<?php adminFooter(); ?>
