# Correção de bugs - CRUD PHP/SQL

Documentação pedida do prof Ícaro Botelho e mostrando as falhas no index.php nos dois erros chamados nas pastas: erro1 e erro2. E como a solução foi aplicada nos dois códigos.

---

## Erro do primeiro código: erro1

### 1. Sintaxe Inválida nas tags curtas de PHP
No HTML, a sintaxe de impressão rápida estava sem espaçamento entre tag '<?=' e a variável '$usuario':'''php

<!-- Errado (Como estava no código feito pelo Ícaro): -->
<td><?=$usuario['id'] ?></td>
<td><?=$usuario['nome'] ?></td>
<td><?=$usuario['email'] ?></td>
<a href="index.php?excluir=<?=$usuario['id'] ?>">

<!-- Corrigido pelo aluno: Jaime Rodrigues: -->
<td><?= $usuario['id'] ?></td>
<td><?= $usuario['nome'] ?></td>
<td><?= $usuario['email'] ?></td>
<a href="index.php?excluir=<?= $usuario['id'] ?>">
