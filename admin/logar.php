<?php
session_start();
session_destroy();
session_start();

include_once ('includes/conexao.php');

$acao = $_POST["acao"];

if (!empty($acao)) {
    switch ($acao) {
        case 'efetuar_login':
            $usuario = $_POST["login"];
            $senha = $_POST["password"];

            $sql = " SELECT * FROM admin ";
            $sql .= " WHERE login = '$usuario' and senha = '$senha'";

            $query = mysql_query($sql);
            $resultset = mysql_fetch_array($query);
            if (mysql_num_rows($query) > 0 || !empty($resultset)) {
                if (!$resultset['status']) {
                    $msg = "Usuário Inativo.";

                    $_SESSION['msg_error'] = $msg;

                    header('location: login');
                } else {
                    $id_admin = $resultset['id'];
                    $login_admin = $resultset['login'];

                    $_SESSION["id_admin"] = $id_admin;
                    $_SESSION["login_admin"] = $login_admin;

                    header('location: index');
                }
            } else {
                $msg = "Usuário ou Senha incorreto.";

                $_SESSION['msg_error'] = $msg;

                header('location: login');
            }
            break;
        case 'recuperar_senha':
            
            break;
        default:
            break;
    }
}
?>
