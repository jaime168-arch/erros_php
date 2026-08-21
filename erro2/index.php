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

    $sql = "INSERT INTO produtos 
            (nome, categoria, preco, descricao) 
            VALUES (?, ?, ?, ?)";

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

// EDITAR
if (isset($_POST['editar'])) {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE produtos SET 
            nome = ?, 
            categoria = ?, 
            preco = ?, 
            descricao = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssdsi",
        $nome,
        $categoria,
        $preco,
        $descricao,
        $id
    );

    $stmt->execute();

    header('Location: index.php');
    exit;
}

// LISTAR PRODUTOS
$sql = "SELECT id, nome, categoria, preco, descricao FROM produtos ORDER BY id DESC";
$resultado = $conn->query($sql);

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD de Produtos</title>
</head>

<body>

    <h1>Cadastro de Produtos</h1>

    <form method="POST">

        <label>Nome:</label>
        <input type="text" name="nome" required>
        <br><br>

        <label>Categoria:</label>
        <input type="text" name="categoria" required>
        <br><br>

        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" required>
        <br><br>

        <label>Descrição:</label>
        <textarea name="descricao"></textarea>
        <br><br>

        <button type="submit" name="cadastrar">
            Cadastrar
        </button>

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
                        <?= $produto['id'] ?>
                    </td>
                    <td>
                        <?= $produto['nome'] ?>
                    </td>
                    <td>
                        <?= $produto['categoria'] ?>
                    </td>
                    <td>
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </td>
                    <td>
                        <?= $produto['descricao'] ?>
                    </td>
                    <td>
                        <a href="index.php?excluir=<?= $produto['id'] ?>">Excluir</a>
                    </td>
                </tr>

            <?php } ?>

        </table>

    <?php } else { ?>

        <p>Nenhum produto cadastrado.</p>

    <?php } ?>

</body>

</html>