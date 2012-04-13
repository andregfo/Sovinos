<?php
$datetime_atual = date("Y-m-d H:i:s", mktime(gmdate("H") - 3, gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y")));

$query_leiloes = "SELECT id, duracao, comeca_em FROM leiloes WHERE status = 1 AND finalizado = 0 ORDER BY comeca_em ASC ";

$result_leiloes = mysql_query($query_leiloes);
$num_leiloes = mysql_num_rows($result_leiloes);

if ($num_leiloes > 0) {
    while ($leilao = mysql_fetch_assoc($result_leiloes)) {
        if ($leilao['comeca_em'] <= $datetime_atual) {
            if ($leilao['duracao'] == 0) {
                $query_up = "UPDATE leiloes SET arrematado_em = '" . $datetime_atual . "', finalizado = 1 WHERE id = " . $leilao['id'];
                $result_up = mysql_query($query_up);

                $query_lances = "SELECT id FROM lances WHERE id_leilao = " . $leilao['id'] . " ORDER BY id DESC LIMIT 0, 1";
                $result_lances = mysql_query($query_lances);
                $num_lances = mysql_num_rows($result_lances);

                if ($num_lances > 0) {
                    $lance = mysql_fetch_assoc($result_lances);

                    $query = "INSERT INTO vencedores VALUES (NULL, " . $lance['id'] . ")";
                    $result = mysql_query($query);
                }
            } else {
                $leilao['duracao'] = $leilao['duracao'] - 1;

                $query_up = "UPDATE leiloes SET duracao = " . $leilao['duracao'] . " WHERE id = " . $leilao['id'];
                $result_up = mysql_query($query_up);
            }
        }
    }
}
?>