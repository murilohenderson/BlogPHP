<?php
    require_once 'Post.php';

    // Conexão ao banco de dados
    try {
        $p = new Post("dbblog", "127.0.0.1", "root", "root");
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }


    $modoEdicao = false;
    $postagemParaEditar = null;
    $mensagem_erro = ''; 

    // --- LÓGICA DE DELETE ---
    if (isset($_GET['idDelete'])) {
        $idPostagem = $_GET['idDelete'];
        $p->excluirPostagem($idPostagem);
        header("Location: index.php");
        exit();
    }

    // --- LÓGICA DE CRIAR POSTAGEM ---
    if (isset($_POST['publicar-post'])) {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $conteudo = $_POST['conteudo'];
        if (!empty($titulo) && !empty($conteudo) && !empty($descricao)) {
            $p->cadastrarPostagem($titulo, $descricao, $conteudo);
            header("Location: index.php");
            exit();
        } else {
            $mensagem_erro = "Preencha todos os campos para publicar.";
        }
    }

    // --- LÓGICA DE ENTRAR NO MODO DE EDIÇÃO (GET) ---
    if (isset($_GET['idUpdate']) && !isset($_POST['atualizar-post'])) {
        $modoEdicao = true;
        $postagemParaEditar = $p->buscarPostagemPorId($_GET['idUpdate']);
    }

    // --- LÓGICA DE ATUALIZAR POSTAGEM (POST) ---
    if (isset($_POST['atualizar-post'])) {
        $idPostagem = $_GET['idUpdate'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $conteudo = $_POST['conteudo'];

        if (!empty($titulo) && !empty($conteudo) && !empty($descricao)) {
            $p->atualizarPostagem($idPostagem, $titulo, $descricao, $conteudo);
            header("Location: index.php");
            exit();
        } else {
            $mensagem_erro = "Erro ao atualizar. Preencha todos os campos.";
            $modoEdicao = true;
            $postagemParaEditar = [
                'id' => $idPostagem,
                'titulo' => $titulo,
                'descricao' => $descricao,
                'conteudo' => $conteudo
            ];
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php if (!empty($mensagem_erro)): ?>
        <script>alert('<?php echo htmlspecialchars($mensagem_erro); ?>');</script>
    <?php endif; ?>

    <header class="site-header">
        <nav class="site-header-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </nav>
    </header>

    <main class="conteudo-principal">

        <input type="button" value="<?php if ($modoEdicao) { echo 'Cancelar Edição'; } else { echo 'Criar postagem'; } ?>" id="criar-postagem">

        <section class="conteudo-principal-criar-postagem <?php if ($modoEdicao) echo 'formulario-visible'; ?>" id="formulario">

            <h3><?php if ($modoEdicao) { echo 'Editar Postagem'; } else { echo 'Criar postagem'; } ?></h3>

            <form action="<?php if ($modoEdicao) { echo $_SERVER['PHP_SELF'] . '?idUpdate=' . $postagemParaEditar['id']; } else { echo $_SERVER['PHP_SELF']; } ?>" method="POST">

                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" value="<?php if ($modoEdicao) { echo htmlspecialchars($postagemParaEditar['titulo']); } ?>" required>

                <label for="descricao">Descricao:</label>
                <input type="text" name="descricao" id="descricao" value="<?php if ($modoEdicao) { echo htmlspecialchars($postagemParaEditar['descricao']); } ?>">

                <label for="conteudo">Conteúdo:</label>
                <textarea name="conteudo" id="conteudo" rows="10" required><?php if ($modoEdicao) { echo htmlspecialchars($postagemParaEditar['conteudo']); } ?></textarea>

                <?php if ($modoEdicao): ?>
                    <input type="submit" value="Atualizar" name="atualizar-post" class="btn-submit">
                <?php else: ?>
                    <input type="submit" value="Publicar" name="publicar-post" class="btn-submit">
                <?php endif; ?>

            </form>
        </section>

        <section class="listagem">
            <?php
                $postagens = $p->buscarPostagens();
                if (count($postagens) > 0) {
                    foreach ($postagens as $postagem) {
                        echo "<article class='postagem'>";
                        echo "<h2>" . htmlspecialchars($postagem['titulo']) . "</h2>";
                        echo "<p class='descricao'>" . htmlspecialchars($postagem['descricao']) . "</p>";
                        echo "<p>" . htmlspecialchars(substr($postagem['conteudo'], 0, 200)) . "...</p>";
                        echo "<small>Publicado em: " . date('d/m/Y H:i:s', strtotime($postagem['data_publicacao'])) . "</small>";
                        echo "<div id='acoes'>";
                        echo "<a href='index.php?idUpdate=" . $postagem['id'] . "' class='btn-editar'>Editar</a>";
                        // Adiciona uma confirmação em JS para excluir
                        echo "<a href='index.php?idDelete=" . $postagem['id'] . "' class='btn-excluir' onclick='return confirm(\"Tem certeza que deseja excluir esta postagem?\")'>Excluir</a>";
                        echo "</div> ";
                        echo "</article>";
                    }
                } else {
                    echo "<p>Nenhuma postagem encontrada.</p>";
                }
            ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>Blog &copy; <?php echo date('Y'); ?></p>
    </footer>

    <script src="script.js"></script>
</body>

</html>