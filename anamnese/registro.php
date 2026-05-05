<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['participante_id'])) {
    header('Location: ' . BASE_URL . '/anamnese/');
    exit;
}

$erro  = '';
$dados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'      => trim($_POST['nome'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'cpf'       => preg_replace('/\D/', '', $_POST['cpf'] ?? ''),
        'nascimento'=> trim($_POST['nascimento'] ?? ''),
        'whatsapp'  => trim($_POST['whatsapp'] ?? ''),
        'senha'     => $_POST['senha'] ?? '',
        'confirma'  => $_POST['confirma'] ?? '',
    ];

    // Validações
    if (!$dados['nome'] || !$dados['email'] || !$dados['cpf'] || !$dados['nascimento'] || !$dados['senha']) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (empty($_POST['lgpd_aceite'])) {
        $erro = 'É necessário aceitar a Política de Privacidade para criar sua conta.';
    } elseif (strlen($dados['cpf']) !== 11) {
        $erro = 'CPF inválido. Digite apenas os números.';
    } elseif ($dados['senha'] !== $dados['confirma']) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($dados['senha']) < 8) {
        $erro = 'A senha deve ter ao menos 8 caracteres.';
    } else {
        // Validar maioridade (18 anos)
        $nasc = new DateTime($dados['nascimento']);
        $hoje = new DateTime();
        if ($hoje->diff($nasc)->y < 18) {
            $erro = 'É necessário ter 18 anos ou mais para se cadastrar.';
        }
    }

    if (!$erro) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO participantes (nome, email, cpf, data_nascimento, whatsapp, senha)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $dados['nome'],
                $dados['email'],
                $dados['cpf'],
                $dados['nascimento'],
                $dados['whatsapp'],
                password_hash($dados['senha'], PASSWORD_BCRYPT),
            ]);

            $id = $pdo->lastInsertId();
            $_SESSION['participante_id']   = $id;
            $_SESSION['participante_nome'] = $dados['nome'];
            header('Location: ' . BASE_URL . '/anamnese/');
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erro = 'E-mail ou CPF já cadastrado. <a href="login.php" style="color:#25D366;">Faça login</a>.';
            } else {
                $erro = 'Erro ao criar conta. Tente novamente.';
            }
        }
    }
}

$title       = 'Cadastro — Ficha de Anamnese';
$description = 'Crie sua conta para preencher a ficha de anamnese da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/anamnese/registro.php';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">
    <div class="anamnese-acesso" style="max-width:520px;">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="ChamaTrina" class="anamnese-logo">
        <h1>Criar conta</h1>
        <p>Crie sua conta para acessar e preencher a ficha de anamnese. Apenas maiores de 18 anos.</p>

        <?php if ($erro): ?>
            <div class="anamnese-alerta anamnese-alerta-erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" class="anamnese-form-acesso">
            <div class="campo">
                <label>Nome completo *</label>
                <input type="text" name="nome" required
                       value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>E-mail *</label>
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($dados['email'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>CPF *</label>
                <input type="text" name="cpf" required placeholder="000.000.000-00" maxlength="14"
                       value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>Data de nascimento *</label>
                <input type="date" name="nascimento" required
                       value="<?= htmlspecialchars($dados['nascimento'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>WhatsApp *</label>
                <input type="tel" name="whatsapp" required placeholder="(51) 99999-9999"
                       value="<?= htmlspecialchars($dados['whatsapp'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>Senha * (mínimo 8 caracteres)</label>
                <input type="password" name="senha" required minlength="8">
            </div>
            <div class="campo">
                <label>Confirmar senha *</label>
                <input type="password" name="confirma" required minlength="8">
            </div>
            <div class="campo" style="margin-top:20px;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-weight:normal;font-size:14px;color:var(--text);">
                    <input type="checkbox" name="lgpd_aceite" required
                           style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:#25D366;">
                    <span>
                        Autorizo o uso dos meus dados pessoais e de saúde para fins de participação em cerimônias espirituais, conforme a
                        <a href="<?= BASE_URL ?>/politica-privacidade.php" target="_blank"
                           style="color:#25D366;text-decoration:underline;">Política de Privacidade</a>.
                    </span>
                </label>
            </div>

            <button type="submit" class="btn whatsapp" style="width:100%;justify-content:center;margin-top:16px;">
                Criar conta
            </button>
        </form>

        <p style="margin-top:20px;text-align:center;color:#aaa;font-size:14px;">
            Já tem conta?
            <a href="<?= BASE_URL ?>/anamnese/login.php" style="color:#25D366;">Faça login</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
