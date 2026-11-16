<?php
require_once "conexao.php";

function limpar($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

// CADASTRAR
if (isset($_POST['cadastrar'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if ($titulo !== "") {
        $stmt = $pdo->prepare(
            "INSERT INTO tarefas (titulo, descricao) VALUES (?, ?)"
        );
        $stmt->execute([$titulo, $descricao]);
    }

    header("Location: index.php");
    exit;
}

// CONCLUIR
if (isset($_POST['concluir'])) {
    $stmt = $pdo->prepare(
        "UPDATE tarefas SET status = 'Concluída' WHERE id = ?"
    );
    $stmt->execute([$_POST['id']]);

    header("Location: index.php");
    exit;
}

// EXCLUIR
if (isset($_POST['excluir'])) {
    $stmt = $pdo->prepare(
        "DELETE FROM tarefas WHERE id = ?"
    );
    $stmt->execute([$_POST['id']]);

    header("Location: index.php");
    exit;
}

// LISTAR
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaskFlow | Gerenciador de Tarefas</title>

    <link rel="stylesheet" href="estilizacao.css">
</head>

<body>

<div class="container">

    <header>
        <h1>TaskFlow</h1>
        <p>Organize suas tarefas e acompanhe seu progresso.</p>
    </header>

    <section class="stats">

        <div class="stat">
            <p>Total de tarefas</p>
            <h2 class="blue"><?= $total ?></h2>
        </div>

        <div class="stat">
            <p>Concluídas</p>
            <h2 class="green"><?= $concluidas ?></h2>
        </div>

        <div class="stat">
            <p>Pendentes</p>
            <h2 class="orange"><?= $pendentes ?></h2>
        </div>

    </section>

    <section class="panel">

        <h2>+ Nova tarefa</h2>

        <form method="POST">

            <input
                type="text"
                name="titulo"
                placeholder="Digite o título da tarefa"
                maxlength="150"
                required
            >

            <textarea
                name="descricao"
                placeholder="Descreva sua tarefa..."
            ></textarea>

            <button class="cadastrar" type="submit" name="cadastrar">
                Adicionar tarefa
            </button>

        </form>

    </section>

    <section class="panel">

        <h2>Minhas tarefas (<?= $total ?>)</h2>

        <?php if ($total == 0): ?>

            <div class="vazio">
                <h3>Nenhuma tarefa cadastrada</h3>
                <p>Adicione sua primeira tarefa acima!</p>
            </div>

        <?php else: ?>

            <?php foreach ($tarefas as $tarefa): ?>

                <div class="tarefa">

                    <div>
                        <h3><?= limpar($tarefa['titulo']) ?></h3>

                        <p><?= limpar($tarefa['descricao']) ?></p>

                        <?php if ($tarefa['status'] == 'Concluída'): ?>

                            <span class="status concluida">
                                Concluída
                            </span>

                        <?php else: ?>

                            <span class="status pendente">
                                Pendente
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="acoes">

                        <?php if ($tarefa['status'] == 'Pendente'): ?>

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $tarefa['id'] ?>"
                                >

                                <button
                                    class="concluir"
                                    type="submit"
                                    name="concluir"
                                >
                                    Concluir
                                </button>

                            </form>

                        <?php endif; ?>

                        <form
                            method="POST"
                            onsubmit="return confirm('Deseja excluir esta tarefa?')"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $tarefa['id'] ?>"
                            >

                            <button
                                class="excluir"
                                type="submit"
                                name="excluir"
                            >
                                Excluir
                            </button>

                        </form>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

    <footer>
        TaskFlow — Sistema de Gerenciamento de Tarefas
    </footer>

</div>

</body>
</html>
