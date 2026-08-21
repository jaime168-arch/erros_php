<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'crud_aula';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// CADASTRAR
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO produtos (nome, categoria, preco, descricao) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $nome, $categoria, $preco, $descricao);
    $stmt->execute();

    header('Location: index.php');
    exit;
}

// EXCLUIR
if (isset($_GET['excluir'])) {

    $id = $_GET['excluir'];

    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header('Location: index.php');
    exit;
}

// EDITAR (PROCESSAMENTO)
if (isset($_POST['editar'])) {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE produtos SET nome = ?, categoria = ?, preco = ?, descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $nome, $categoria, $preco, $descricao, $id);
    $stmt->execute();

    header('Location: index.php');
    exit;
}

// BUSCAR PRODUTO PARA EDIÇÃO
$produto_editando = null;
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $sql_edit = "SELECT id, nome, categoria, preco, descricao FROM produtos WHERE id = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id_editar);
    $stmt_edit->execute();
    $produto_editando = $stmt_edit->get_result()->fetch_assoc();
}

// LISTAR PRODUTOS
$sql = "SELECT id, nome, categoria, preco, descricao FROM produtos ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD de Produtos</title>
</head>

<body>

    <h1><?= $produto_editando ? 'Editar Produto' : 'Cadastro de Produtos' ?></h1>

    <form method="POST">

        <?php if ($produto_editando) { ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($produto_editando['id']) ?>">
        <?php } ?>

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($produto_editando['nome'] ?? '') ?>" required>
        <br><br>

        <label>Categoria:</label>
        <input type="text" name="categoria" value="<?= htmlspecialchars($produto_editando['categoria'] ?? '') ?>" required>
        <br><br>

        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto_editando['preco'] ?? '') ?>" required>
        <br><br>

        <label>Descrição:</label>
        <textarea name="descricao"><?= htmlspecialchars($produto_editando['descricao'] ?? '') ?></textarea>
        <br><br>

        <?php if ($produto_editando) { ?>
            <button type="submit" name="editar">Atualizar</button>
            <a href="index.php">Cancelar</a>
        <?php } else { ?>
            <button type="submit" name="cadastrar">Cadastrar</button>
        <?php } ?>

    </form>

    <h2>Produtos Cadastrados</h2>

    <?php if ($resultado->num_rows > 0) { ?>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>

            <?php while ($produto = $resultado->fetch_assoc()) { ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($produto['id']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($produto['nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($produto['categoria']) ?>
                    </td>
                    <td>
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($produto['descricao']) ?>
                    </td>
                    <td>
                        <a href="index.php?editar=<?= $produto['id'] ?>">Editar</a> |
                        <a href="index.php?excluir=<?= $produto['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>

            <?php } ?>

        </table>

    <?php } else { ?>

        <p>Nenhum produto cadastrado.</p>

    <?php } ?>

</body>

</html>