<?php

session_start();
include('../includes/conexao.php');

$acao = $_REQUEST['action'];

switch ($acao) {
    case 'add':
        $nome = utf8_decode($_POST['nome']);
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $status = $_POST['status'];
        $criado_em = date('Y-m-d H:m:i');

        $query = "INSERT INTO admin VALUES (NULL, '" . $login . "', '" . $senha . "', '" . $nome . "', '" . $criado_em . "', " . $status . ")";
        $result = mysql_query($query);

        $query = "SELECT id FROM admin WHERE nome = '" . $nome . "' AND login = '" . $login . "' AND senha = '" . $senha . "'";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Administrador cadastrado com sucesso!';

            header('location: ../administrador');
        } else {
            $_SESSION['msg_error'] = 'Erro ao cadastrar o administrador!';

            header('location: ../administrador');
        }

        break;

    case 'del':
        $id = $_REQUEST['id'];

        $query = "DELETE FROM admin WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT * FROM admin WHERE id = " . $id;
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_error'] = 'Erro ao excluir o administrador!';

            header('location: ../administrador');
        } else {
            $_SESSION['msg_success'] = 'Administrador excluído com sucesso!';

            header('location: ../administrador');
        }

        break;

    case 'sch_id':
        $id = $_REQUEST['id'];

        $query = "SELECT * FROM admin WHERE id = " . $id;
        $result = mysql_query($query);
        $admin = mysql_fetch_assoc($result);

        $nome = $admin['nome'];
        $login = $admin['login'];
        $senha = $admin['senha'];
        $status = $admin['status'];

        $_SESSION['id_edit'] = $id;
        $_SESSION['nome_edit'] = $nome;
        $_SESSION['login_edit'] = $login;
        $_SESSION['senha_edit'] = $senha;
        $_SESSION['status_edit'] = $status;

        header("location: ../administrador_editar.php");

        break;

    case 'edt':
        $id = $_POST['id'];
        $nome = utf8_decode($_POST['nome']);
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $status = $_POST['status'];

        $query = "UPDATE admin SET nome = '" . $nome . "', login = '" . $login . "', senha = '" . $senha . "', status = " . $status . " WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT id FROM admin WHERE nome = '" . $nome . "' AND login = '" . $login . "' AND senha = '" . $senha . "' AND status = " . $status;
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Administrador editado com sucesso!';

            header('location: ../administrador');
        } else {
            $_SESSION['msg_error'] = 'Erro ao editar o administrador!';

            header('location: ../administrador');
        }

        break;

    case 'atv':
        $id = $_REQUEST['id'];

        $query = "UPDATE admin SET status = 1 WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT id FROM admin WHERE id = " . $id . " AND status = 1";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Administrador ativado com sucesso!';

            header('location: ../administrador');
        } else {
            $_SESSION['msg_error'] = 'Erro ao ativar o administrador!';

            header('location: ../administrador');
        }

        break;

    case 'itv':
        $id = $_REQUEST['id'];

        $query = "UPDATE admin SET status = 0 WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT id FROM admin WHERE id = " . $id . " AND status = 0";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Administrador inativado com sucesso!';

            header('location: ../administrador');
        } else {
            $_SESSION['msg_error'] = 'Erro ao inativar o administrador!';

            header('location: ../administrador');
        }

        break;
}
?>