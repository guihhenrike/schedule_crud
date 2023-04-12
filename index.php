<?php
    require_once 'classe-pessoa.php';
    $pessoa = new Pessoa("projeto_crud","localhost","root","1503");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Agenda de cadastro</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php
    if(isset($_POST['nome'])) // clicou no botao cadastrar ou editar
    {
        if(isset($_GET['id_up']) && !empty($_GET['id_up'])) //editar
        {
            $id_update = addslashes($_GET['id_up']);
            $nome = addslashes($_POST['nome']);
            $telefone = addslashes($_POST['telefone']);
            $email = addslashes($_POST['email']);
    
            if(!empty($nome) && !empty($telefone) && !empty($email))
            {
                //editar
                $pessoa->atualizarDados($id_update, $nome, $telefone, $email);
                header("location: index.php");
                
                ?>
                    <div id="success-message"  class="alert alert-success" role="alert">
                        <?php echo "Dados atualizados com sucesso !"; ?>
                    </div>
                <?php 
                
            }else
            {   
            ?>
                <div class="alert alert-warning" role="alert">
                   <?php echo "Preencha todos os campos"; ?>
                </div>
            <?php 
            }
        } else { //cadastrar
            $nome = addslashes($_POST['nome']);
            $telefone = addslashes($_POST['telefone']);
            $email = addslashes($_POST['email']);
    
            if(!empty($nome) && !empty($telefone) && !empty($email))
            {
                //cadastrar
                if(!$pessoa->cadastrarPessoa($nome, $telefone, $email))
                {
                ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo "Email já está cadastrado !"; ?>
                    </div>
                <?php 
                }
            }else
            {   
            ?>
                <div class="alert alert-warning" role="alert">
                   <?php echo "Preencha todos os campos"; ?>
                </div>
            <?php 
            }
        }


    }       
        ?>
    <?php 
        if(isset($_GET['id_up']))
        {
            $id_update = addslashes($_GET['id_up']);
            $res = $pessoa->buscarDadosPessoa($id_update);
        }
    ?>  
    <section id="esquerda" class="container">
        <form action="" method="POST" id="form-cad">
            <h2 id="titulo-form">Cadastrar Pessoa</h2>
            <label for="name" id="nome-lab" >Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if(isset($res)){echo $res['nome'];} ?>" required>
            <label for="phone" id="telefone-lab">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if(isset($res)){echo $res['telefone'];} ?>" required>
            <label for="mail" id="email-lab" >Email</label>
            <input type="text" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];} ?>" required>
            <input type="submit" value="<?php if(isset($res)) {echo "Atualizar";}else{echo "Cadastrar";} ?>">
        </form>
    </section>
   
    <section id="direita" class="container">
        <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="1">Email</td>
                <td>Editar/Apagar</td>
            </tr>
         
        <?php
            $dados = $pessoa->buscarDados();
            if(count($dados) > 0)
            {
                for ($i=0; $i < count($dados); $i++)
                {
                    echo "<tr>";

                    foreach($dados[$i] as $key => $value)
                    {
                        if($key != "id")
                        {
                            echo "<td>$value</td>";
                        }
                    }
                    ?>
                    <td id="config">
                        <a class="far fa-edit"      href="index.php?id_up=<?php echo $dados[$i]['id']; ?>" style="color: black"></a>
                        <a class="fas fa-trash-alt" href="index.php?id=<?php echo $dados[$i]['id']; ?>" style="color: black"></a>
                    </td>

                    <?php
                    echo "</tr>";
                }
            } else
            {
                ?>
                <div class="alert alert-warning" role="alert">
                   <?php echo "Ainda não há usuários cadastrados !"; ?>
                </div>
            <?php }
        ?>            
        </table>
    </section>
</body>
</html>

<?php

    if(isset($_GET['id'])) 
    {
        $id_pessoa = addslashes($_GET['id']);
        $pessoa->excluirPessoa($id_pessoa);
        header("location: index.php");
    }
?>
