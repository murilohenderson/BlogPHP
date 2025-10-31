<?php 

    Class Post {
        private $pdo;

        public function __construct($dbname,  $host, $user, $senha) {
            try {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host, $user, $senha);
            } catch (PDOException $e) {
                echo "Erro com banco de dados: ".$e->getMessage();
                exit();
            } catch (Exception $e) {
                echo "Erro genérico: ".$e->getMessage();
                exit();
            }
        }

        public function buscarPostagens() {
            $res = array();
            $cmd = $this->pdo->prepare("SELECT * FROM postagem ORDER BY data_publicacao DESC");
            $cmd->execute();
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        public function cadastrarPostagem($titulo, $descricao, $conteudo) {
            $cmd = $this->pdo->prepare("INSERT INTO postagem (titulo, descricao, conteudo, data_publicacao) VALUES (:t, :d, :c, NOW())");
            $cmd->bindValue(":t", $titulo);
            $cmd->bindValue(":d", $descricao);
            $cmd->bindValue(":c", $conteudo);
            $cmd->execute();

        }

        public function excluirPostagem($id) {
            $cmd = $this->pdo->prepare("DELETE FROM postagem WHERE id = :id");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }

        public function atualizarPostagem($id, $titulo, $descricao, $conteudo) {
            $cmd = $this->pdo->prepare("UPDATE postagem SET titulo = :t, descricao = :d, conteudo = :c WHERE id = :id");
            $cmd->bindValue(":t", $titulo);
            $cmd->bindValue(":d", $descricao);
            $cmd->bindValue(":c", $conteudo);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }

        public function buscarPostagemPorId($id) {
            $cmd = $this->pdo->prepare("SELECT * FROM postagem WHERE id = :id");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res;
        }


    }

?>