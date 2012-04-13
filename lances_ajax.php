<?php
include_once('admin/includes/conexao.php');

function getLances() {
    $datetime_atual = date("Y-m-d H:i:s", mktime(gmdate("H") - 3, gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y")));
    $lances = array();
    $resultado = array();

    //AND comeca_em >= '$datetime_atual'
    $query_leiloes = "SELECT id, duracao, comeca_em FROM leiloes WHERE status = 1 AND finalizado = 0 ORDER BY comeca_em ASC ";

    $result_leiloes = mysql_query($query_leiloes);
    $num_leiloes = mysql_num_rows($result_leiloes);

    if ($num_leiloes > 0) {
        $resultado['result'] = TRUE;

        while ($leilao = mysql_fetch_assoc($result_leiloes)) {
            $leilao['comecou'] = FALSE;
            $leilao['finalizou'] = FALSE;

            if ($leilao['comeca_em'] <= $datetime_atual) {
                $leilao['comecou'] = TRUE;

                if ($leilao['duracao'] == 0) $leilao['finalizou'] = TRUE;
                
                /*if ($leilao['duracao'] == 0) {
                    $query_up = "UPDATE leiloes SET arrematado_em = '" . $datetime_atual . "', finalizado = 1 WHERE id = " . $leilao['id'];
                    $result_up = mysql_query($query_up);

                    $leilao['finalizou'] = TRUE;
                } else {
                    $leilao['duracao'] = $leilao['duracao'] - 1;

                    $query_up = "UPDATE leiloes SET duracao = " . $leilao['duracao'] . " WHERE id = " . $leilao['id'];
                    $result_up = mysql_query($query_up);
                }*/
            }

            $leilao['duracao'] = ($leilao['duracao'] < 10) ? "0" . $leilao['duracao'] : $leilao['duracao'];
            $lance_valor = 0;
            $lance_usuario = "---";

            $query_lances = "SELECT l.valor_lance, u.login FROM lances l, usuarios u WHERE l.id_leilao = " . $leilao['id'] . " ORDER BY l.id DESC LIMIT 0, 1";
            //echo $query_lances;
            $result_lances = mysql_query($query_lances);
            $num_lances = mysql_num_rows($result_lances);

            if ($num_lances > 0) {
                $lance = mysql_fetch_assoc($result_lances);
                //print_r($lance);
                $lance_valor = $lance['valor_lance'];
                $lance_usuario = $lance['login'];
            }

            $leilao['usuario'] = $lance_usuario;
            $leilao['valor_lance'] = number_format($lance_valor, 2, ',', '.');

            $lances[] = $leilao;
        }
    } else {
        $resultado['result'] = FALSE;
    }

    $resultado['lances'] = $lances;
    return $resultado;
}

function setLance($id_leilao, $id_usuario, $valor_lance, $duracao, $comeca_em) {
    $datetime_atual = date("Y-m-d H:i:s", mktime(gmdate("H") - 3, gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y")));
    $resultado['result'] = FALSE;

    $query = "INSERT INTO lances VALUES (NULL, " . $id_leilao . ", " . $id_usuario . ", '" . $valor_lance . "', '" . $datetime_atual . "')";
    $result = mysql_query($query);

    $query = "SELECT id FROM lances WHERE id_leilao = " . $id_leilao . " AND id_usuario = " . $id_usuario . " AND lance_em = '" . $datetime_atual . "'";
    $result = mysql_query($query);
    $num_result = mysql_num_rows($result);

    if ($num_result > 0) {
        if($comeca_em <= $datetime_atual){
            if ($duracao > 15)
                $nova_duracao = $duracao + 2;
            elseif ($duracao > 10 && $duracao <= 15)
                $nova_duracao = $duracao + 6;
            else
                $nova_duracao = $duracao + 11;

            $query = "UPDATE leiloes SET duracao = " . $nova_duracao . " WHERE id = " . $id_leilao;
            $result = mysql_query($query);
        }

        $resultado['result'] = TRUE;
    }
    return $resultado;
}

$action = $_REQUEST['action'];
$id_leilao = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;

if ($action == "get") {
    $retorno = getLances();

    echo json_encode($retorno);
} else if ($action == "set") {
    $usuario = 0;
    $valor_lance = 0.01;
    $id_usuario = $_REQUEST['id_usuario'];
    
    $query_num_lances = "SELECT num_lances FROM usuarios WHERE id = " . $id_usuario;
    $result_num_lances = mysql_query($query_num_lances);
    $num_num_lances = mysql_num_rows($result_num_lances);

    if ($num_num_lances > 0) {    
        $query_lances = "SELECT id_usuario, valor_lance FROM lances WHERE id_leilao = " . $id_leilao . " ORDER BY id DESC LIMIT 0, 1";
        $result_lances = mysql_query($query_lances);
        $num_lances = mysql_num_rows($result_lances);

        if ($num_lances > 0) {
            $lance = mysql_fetch_assoc($result_lances);

            $usuario = $lance['id_usuario'];
            $valor_lance = $lance['valor_lance'] + 0.01;
        }

        if($usuario != $id_usuario){    
            $query_duracao = "SELECT duracao, comeca_em FROM leiloes WHERE id = " . $id_leilao;
            $result_duracao = mysql_query($query_duracao);
            $num_duracao = mysql_num_rows($result_duracao);

            if ($num_duracao > 0) {
                $leilao = mysql_fetch_assoc($result_duracao);

                $duracao = $leilao['duracao'];
                $comeca_em = $leilao['comeca_em'];
            }

            $retorno = setLance($id_leilao, $id_usuario, $valor_lance, $duracao, $comeca_em);
        } else{
            $resultado['result'] = FALSE;
            $resultado['reason'] = "O último lance já é seu.";
            
            $retorno = $resultado;
        }
    } else{
        $resultado['result'] = FALSE;
        $resultado['reason'] = "Você não tem mais lances, compre mais para continuar na disputa.";
        
        $retorno = $resultado;
    }

    echo json_encode($retorno);
}
?>