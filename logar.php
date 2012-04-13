<?php
session_start();

$pagina_anterior = $_SESSION['pagina_anterior'];

session_destroy();
session_start();

include_once ('admin/includes/conexao.php');

$acao = $_POST["acao"];

if (!empty($acao)) {
    switch ($acao) {
        case 'efetuar_login':
            $usuario = $_POST["login"];
            $senha = $_POST["password"];

            $sql = " SELECT id, login, num_lances, status FROM usuarios ";
            $sql .= " WHERE login = '$usuario' and senha = '$senha'";

            $query = mysql_query($sql);
            $resultset = mysql_fetch_array($query);
            if (mysql_num_rows($query) > 0 || !empty($resultset)) {
                if (!$resultset['status']) {
                    $msg = "Usuário Inativo.";

                    $_SESSION['msg_error'] = $msg;

                    header('location: '. $pagina_anterior);
                } else {
                    $id_admin = $resultset['id'];
                    $login_admin = $resultset['login'];
                    $num_lances_admin = $resultset['num_lances'];

                    $_SESSION["id_usuario"] = $id_admin;
                    $_SESSION["login_usuario"] = $login_admin;
                    $_SESSION["num_lances_usuario"] = $num_lances_admin;

                    header('location: index');
                }
            } else {
                $msg = "Usuário ou Senha incorreto.";

                $_SESSION['msg_error'] = $msg;

                header('location: '. $pagina_anterior);
            }
            break;
        case 'recuperar_senha':
            
            break;
        default:
            break;
    }
}
?>
