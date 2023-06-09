<?php

    class Pessoa 
    {
            private $pdo;

        public function __construct($dbname, $host, $user, $pass) 
        {

            try {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$pass);
        
            } catch (PDOException $e) {            
                echo "Erro com banco de dados: " . $e->getMessage();
                exit();

            } catch (Exception $e) {
                echo "Erro genérico: " . $e->getMessage();
                exit();
            }
        }

        public function buscarDados() 
        {
            $res = array();
            $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }


        public function cadastrarPessoa($nome, $telefone, $email)
        {
            // antes de cadatrar, verificar se ja possui o email
            $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :email");
            $cmd->bindValue(":email",$email);
            $cmd->execute();

            if($cmd->rowCount() > 0) //email ja existe
            {            
                return false;
            } else // nao foi encontrado email no banco, entao pode cadastrar
            {
                $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email) VALUES (:nome, :telefone, :email)");

                $cmd->bindValue(":nome",$nome);
                $cmd->bindValue(":telefone",$telefone);
                $cmd->bindValue(":email",$email);
                $cmd->execute();
                
                return true;
            }
        }

        public function excluirPessoa($id)
        {
            $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
        }
        // buscar dados de uma pessoa especifica
        public function buscarDadosPessoa($id)
        {
            $res = array();

            $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);

            return $res;
        }

        public function atualizarDados($id, $nome, $telefone, $email)
        {
            $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :nome, telefone = :telefone, email = :email WHERE id = :id");
            $cmd->bindValue(":nome", $nome);
            $cmd->bindValue(":telefone", $telefone);
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            return true;
            
        }


    }
