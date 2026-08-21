<?php

$host = 'localhost';
$user = 'root';$password = "";
$database = 'crud_aula';

$conn = new mysqli($host,$user, $password,$database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// CADASTRAR
if (isset($_POST['cadastrar'])) {

    $nome =$_POST['nome'];
    $email =$_POST['email'];

    $sql = 'INSERT INTO usuarios (nome, email) VALUES (?, ?)';$stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $nome, $email);$stmt->execute();

    header('Location: index.php');
    exit;
}

// EXCLUIR
if (isset($_GET['excluir'])) {

    $id =$_GET['excluir'];

    $sql = 'DELETE FROM usuarios WHERE id = ?';$stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);$stmt->execute();

    header('Location: index.php');
    exit;
}

// EDITAR (PROCESSAMENTO DO FORMULÁRIO)
if (isset($_POST['editar'])) {

    $id =$_POST['id'];
    $nome =$_POST['nome'];
    $email =$_POST['email'];

    $sql = 'UPDATE usuarios SET nome = ?, email = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssi", $nome,$email, $id);$stmt->execute();

    header('Location: index.php');
    exit;
}

// BUSCAR DADOS DO USUÁRIO PARA EDIÇÃO
$usuario_editando = null;
if (isset($_GET['editar'])) {$id_editar = $_GET['editar'];$sql_edit = 'SELECT id, nome, email FROM usuarios WHERE id = ?';
    $stmt_edit =$conn->prepare($sql_edit);$stmt_edit->bind_param("i", $id_editar);$stmt_edit->execute();
    $usuario_editando =$stmt_edit->get_result()->fetch_assoc();
}

// LISTAR USUÁRIOS
$sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários</title>
</head>

<body>

    <h1><?= $usuario_editando ? 'Editar Usuário' : 'Cadastro de Usuários' ?></h1>

    <form method="POST">

        <?php if ($usuario_editando) { ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario_editando['id']) ?>">
        <?php } ?>

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario_editando['nome'] ?? '') ?>" required>
        <br><br>

        <label>E-mail:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario_editando['email'] ?? '') ?>" required>
        <br><br>

        <?php if ($usuario_editando) { ?>
            <button type="submit" name="editar">Atualizar</button>
            <a href="index.php">Cancelar</a>
        <?php } else { ?>
            <button type="submit" name="cadastrar">Cadastrar</button>
        <?php } ?>

    </form>

    <h2>Usuários Cadastrados</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>

        <?php while ($usuario =$resultado->fetch_assoc()) { ?>

            <tr>
                <td>
                    <?= htmlspecialchars($usuario['id']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($usuario['nome']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($usuario['email']) ?>
                </td>
                <td>
                    <a href="index.php?editar=<?= $usuario['id'] ?>">Editar</a> |
                    <a href="index.php?excluir=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>

        <?php } ?>

    </table>

</body>

</html>