
<?php
require_once "conexao.php";

// CADASTRAR TAREFA
if (isset($_POST['cadastrar'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if ($titulo !== "") {
        $sql = "INSERT INTO tarefas (titulo, descricao)
                VALUES (?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao]);
    }

    header("Location: index.php");
    exit;
}

// CONCLUIR TAREFA
if (isset($_POST['concluir'])) {
    $id = $_POST['id'];

    $sql = "UPDATE tarefas
            SET status = 'Concluída'
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

// EXCLUIR TAREFA
if (isset($_POST['excluir'])) {
    $id = $_POST['id'];

    $stmt = $pdo->prepare(
        "DELETE FROM tarefas WHERE id = ?"
    );

    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

// EDITAR TAREFA
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if ($titulo !== "") {
        $sql = "UPDATE tarefas
                SET titulo = ?, descricao = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao, $id]);
    }

    header("Location: index.php");
    exit;
}

// BUSCAR TAREFA PARA EDIÇÃO
$tarefaEdicao = null;

if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare(
        "SELECT * FROM tarefas WHERE id = ?"
    );

    $stmt->execute([$_GET['editar']]);
    $tarefaEdicao = $stmt->fetch(PDO::FETCH_ASSOC);
}

// LISTAR TAREFAS
$tarefas = $pdo->query(
    "SELECT * FROM tarefas ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);

// CONTADORES
$total = count($tarefas);
$concluidas = 0;
$pendentes = 0;

foreach ($tarefas as $tarefa) {
    if ($tarefa['status'] == 'Concluída') {
        $concluidas++;
    } else {
        $pendentes++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
</head>

<body>

<h1>Gerenciador de Tarefas</h1>

<h3>Total: <?= $total ?></h3>
<h3>Concluídas: <?= $concluidas ?></h3>
<h3>Pendentes: <?= $pendentes ?></h3>

<hr>

<?php if ($tarefaEdicao): ?>

    <h2>Editar tarefa</h2>

    <form method="POST">
        <input type="hidden" name="id"
               value="<?= $tarefaEdicao['id'] ?>">

        <input type="text" name="titulo"
               value="<?= htmlspecialchars($tarefaEdicao['titulo']) ?>"
               required>

        <textarea name="descricao"><?= htmlspecialchars($tarefaEdicao['descricao'] ?? '') ?></textarea>

        <button type="submit" name="editar">
            Salvar alterações
        </button>
    </form>

    <a href="index.php">Cancelar</a>

<?php else: ?>

    <h2>Nova tarefa</h2>

    <form method="POST">
        <input type="text" name="titulo"
               placeholder="Nome da tarefa" required>

        <textarea name="descricao"
                  placeholder="Descrição"></textarea>

        <button type="submit" name="cadastrar">
            Cadastrar
        </button>
    </form>

<?php endif; ?>

<hr>

<h2>Minhas tarefas</h2>

<?php foreach ($tarefas as $tarefa): ?>

    <div>
        <h3>
            <?= htmlspecialchars($tarefa['titulo']) ?>
        </h3>

        <p>
            <?= htmlspecialchars($tarefa['descricao'] ?? '') ?>
        </p>

        <p>Status: <?= $tarefa['status'] ?></p>

        <a href="index.php?editar=<?= $tarefa['id'] ?>">
            Editar
        </a>

        <form method="POST" style="display:inline">
            <input type="hidden" name="id"
                   value="<?= $tarefa['id'] ?>">

            <?php if ($tarefa['status'] == 'Pendente'): ?>
                <button type="submit" name="concluir">
                    Concluir
                </button>
            <?php endif; ?>

            <button type="submit" name="excluir"
                    onclick="return confirm('Deseja excluir?')">
                Excluir
            </button>
        </form>

    </div>

    <hr>

<?php endforeach; ?>

</body>
</html>
