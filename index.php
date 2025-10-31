<?php
    require_once 'Post.php';

    $p = new Post("dbblog", "127.0.0.1", "root", "root");

    $modoEdicao = false;
    $postagemParaEditar = null;
 
    if(isset($_GET['idDelete'])) {
        $idPostagem = $_GET['idDelete'];
        $p->excluirPostagem($idPostagem);
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['publicar-post'])) {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $conteudo = $_POST['conteudo'];
        if(!empty($titulo) && !empty($conteudo) && !empty($descricao)) {
            $p->cadastrarPostagem($titulo, $descricao, $conteudo);
            header("Location: index.php");
            exit();
        } else {
            ?>
            <script>alert("Preencha todos os campos");</script>
        <?php
        }
    }
    if (isset($_GET['idUpdate']) && !isset($_POST['atualizar-post'])) {
        $modoEdicao = true;
        $postagemParaEditar = $p->buscarPostagemPorId($_GET['idUpdate']);
    }

    if(isset($_POST['atualizar-post'])) {
        $idPostagem = $_GET['idUpdate'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $conteudo = $_POST['conteudo'];
        if(!empty($titulo) && !empty($conteudo) && !empty($descricao)) {
            $p->atualizarPostagem($idPostagem, $titulo, $descricao, $conteudo);
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Erro ao atualizar. Preencha todos os campos.');</script>";
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
    <script src="script.js"></script>
</head>
<body>
    <header class="site-header">
        <nav class="site-header-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </nav>  
    </header>

    <!-- Conteúdo principal -->
    <main class="conteudo-principal">
        <input type="button" value="Criar postagem" id="criar-postagem">
        
        <section class="conteudo-principal-criar-postagem" id="formulario">
            <h3>Criar postagem</h3>
            <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" value="<?php if(isset($postagens)){echo $postagens['titulo'];}?>" required>
                <label for="descricao">Descricao:</label>
                <input type="text" name="descricao" id="descricao">
                <label for="conteudo">Conteúdo:</label>
                <textarea name="conteudo" id="conteudo" rows="10" required></textarea>
                <input type="submit" value="Publicar" name="publicar-post">
            </form>
        </section>
        <section class="listagem">
            <?php
                $postagens = $p->buscarPostagens();
                foreach($postagens as $postagem) {
                    echo "<article class='postagem'>";
                    echo "<h2>".htmlspecialchars($postagem['titulo'])."</h2>";
                    echo "<p>".htmlspecialchars(substr($postagem['conteudo'], 0, 200))."...</p>";
                    echo "<small>Publicado em: ".date('d/m/Y H:i:s', strtotime($postagem['data_postagem']))."</small>";
                    echo "<div id='acoes'>";
                    echo "<a href='index.php?idUpdate=". $postagem['id']."'>Editar</a>";
                    echo "<a href='index.php?idDelete=".$postagem['id']."'>Excluir</a>";
                    echo "</div> ";
                    echo "</article>";
                }
            ?>

        </section>
    </main>
    <footer class="site-footer">
        <p>Blog &copy; 2024</p>

    </footer>
</body>
</html>
