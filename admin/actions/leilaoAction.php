<?php

session_start();
include('../includes/conexao.php');
include('../includes/funcoes.php');
include('../includes/simple_image.php');

$acao = $_REQUEST['action'];

switch ($acao) {
    case 'add':
        $valor_float = floatval(str_replace(',', '', $_POST['valor']));
        $img_src = "";

        $id_admin = $_POST['id_admin'];
        $titulo = utf8_decode($_POST['titulo']);
        $slug = create_slug($_POST['titulo']);
        $descricao = utf8_decode($_POST['descricao']);
        $duracao = $_POST['duracao'];
        $comeca_em = toMysqlDateTime($_POST['data_comeca_em']);
        $finaliza_em = NULL;
        $quantidade = $_POST['quantidade'];
        $valor = number_format($valor_float, 2, '.', '');
        $frete = $_POST['frete'];
        $arremate = $_POST['arremate'];
        $destaque = $_POST['destaque'];
        $num_lances = 0;
        $finalizado = 0;
        $status = $_POST['status'];

        if (!empty($_FILES) && !$_FILES['img']['error'][0]) {
            $dir = '../uploads/leilao/img/';
            $dir_thumb = '../uploads/leilao/thumb/';

            $img = explode(".", $_FILES["img"]["name"][0]);
            $nome_imagem = $img[0];
            $ext_imagem = $img[1];

            $cod = substr(md5(uniqid(time())), 0, 10);

            $src_imagem = $cod . "." . $ext_imagem;
            $img_src = $src_imagem;

            $image = new SimpleImage();
            $image_thumb = new SimpleImage();

            $tmp_name = $_FILES["img"]["tmp_name"][0];

            $file = $dir . basename($src_imagem);
            $file_thumb = $dir_thumb . basename($src_imagem);

            $image->load($tmp_name);
            $image->resize(215, 200);
            $image->save($file);

            $image_thumb->load($tmp_name);
            $image_thumb->resize(100, 70);
            $image_thumb->save($file_thumb);
        }

        $query = "INSERT INTO leiloes VALUES (NULL, " . $id_admin . ", '" . $titulo . "', '" . $slug . "', '" . $descricao . "', '" . $img_src . "', " . $duracao . ", '" . $comeca_em . "', '" . $finaliza_em . "', " . $quantidade . ", '" . $valor . "', " . $num_lances . ", " . $frete . ", " . $arremate . ", " . $destaque . ", " . $finalizado . ", " . $status . ")";
        $result = mysql_query($query);

        $query = "SELECT id FROM leiloes WHERE titulo = '" . $titulo . "' AND descricao = '" . $descricao . "'";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            if (!empty($_FILES['img_sec'])) {
                $leilao = mysql_fetch_assoc($result);
                $id_leilao = $leilao['id'];

                foreach ($_FILES["img_sec"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["img_sec"]["tmp_name"][$key];

                        $nome = explode(".", $_FILES["img_sec"]["name"][$key]);
                        $nome_imagem = $nome[0];
                        $ext_imagem = $nome[1];

                        $cod = substr(md5(uniqid(time())), 0, 10);

                        $src_imagem = $cod . "." . $ext_imagem;

                        $file = $dir . basename($src_imagem);
                        $file_thumb = $dir_thumb . basename($src_imagem);

                        $image->load($tmp_name);
                        $image->resize(215, 200);
                        $image->save($file);

                        $image_thumb->load($tmp_name);
                        $image_thumb->resize(100, 70);
                        $image_thumb->save($file_thumb);

                        $query = "INSERT INTO imagens VALUES (NULL, " . $id_leilao . ", '" . $src_imagem . "')";
                        $result = mysql_query($query);
                    }
                }
            }

            $_SESSION['msg_success'] = 'Leilão cadastrado com sucesso!';

            header('location: ../leilao');
        } else {
            $_SESSION['msg_error'] = 'Erro ao cadastrar o leilão!';

            header('location: ../leilao');
        }

        break;

    case 'del':
        $id = $_REQUEST['id'];

        $query = "DELETE FROM leiloes WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT * FROM leiloes WHERE id = " . $id;
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_error'] = 'Erro ao excluir o leilão!';

            header('location: ../leilao');
        } else {
            $_SESSION['msg_success'] = 'Leilão excluído com sucesso!';

            header('location: ../leilao');
        }

        break;

    case 'sch_id':
        $id = $_REQUEST['id'];

        $query = 'SELECT id, titulo, descricao, duracao,  DATE_FORMAT(comeca_em, "%d/%m/%Y %T") AS comeca_em, quantidade_item, valor_item, frete_gratis AS frete, arremate_gratis AS arremate, destaque, status FROM leiloes WHERE id = ' . $id;
        $result = mysql_query($query);
        $leilao = mysql_fetch_assoc($result);

        //$categoria = $leilao['id_categoria'];
        $titulo = $leilao['titulo'];
        $descricao = $leilao['descricao'];
        $duracao = $leilao['duracao'];
        $comeca_em = $leilao['comeca_em'];
        $quantidade = $leilao['quantidade_item'];
        $valor = $leilao['valor_item'];
        $frete = $leilao['frete'];
        $arremate = $leilao['arremate'];
        $destaque = $leilao['destaque'];
        $status = $leilao['status'];

        $_SESSION['id_edit'] = $id;
        //$_SESSION['categoria_edit'] = $categoria;
        $_SESSION['titulo_edit'] = $titulo;
        $_SESSION['descricao_edit'] = $descricao;
        $_SESSION['duracao_edit'] = $duracao;
        $_SESSION['comeca_em_edit'] = $comeca_em;
        $_SESSION['quantidade_edit'] = $quantidade;
        $_SESSION['valor_edit'] = $valor;
        $_SESSION['frete_edit'] = $frete;
        $_SESSION['arremate_edit'] = $arremate;
        $_SESSION['destaque_edit'] = $destaque;
        $_SESSION['status_edit'] = $status;

        header("location: ../leilao_editar.php");

        break;

    case 'edt':
        $valor_float = floatval(str_replace(',', '', $_POST['valor']));

        $id = $_POST['id'];
        $id_admin = $_POST['id_admin'];
        //$categoria = $_POST['categoria'];
        $titulo = utf8_decode($_POST['titulo']);
        $slug = create_slug($_POST['titulo']);
        $descricao = utf8_decode($_POST['descricao']);
        $duracao = $_POST['duracao'];
        $comeca_em = toMysqlDateTime($_POST['data_comeca_em']);
        $finaliza_em = getFinalDate($_POST['data_comeca_em'], $duracao);
        $quantidade = $_POST['quantidade'];
        $valor = number_format($valor_float, 2, '.', '');
        $frete = $_POST['frete'];
        $arremate = $_POST['arremate'];
        $destaque = $_POST['destaque'];
        $num_lances = 0;
        $status = $_POST['status'];

        if (!empty($_FILES) && !$_FILES['img']['error'][0]) {
            $dir = '../uploads/leilao/img/';
            $dir_thumb = '../uploads/leilao/thumb/';

            $img = explode(".", $_FILES["img"]["name"][0]);
            $nome_imagem = $img[0];
            $ext_imagem = $img[1];

            $cod = substr(md5(uniqid(time())), 0, 10);

            $src_imagem = $cod . "." . $ext_imagem;
            $img_src = $src_imagem;

            $image = new SimpleImage();
            $image_thumb = new SimpleImage();

            $tmp_name = $_FILES["img"]["tmp_name"][0];

            $file = $dir . basename($src_imagem);
            $file_thumb = $dir_thumb . basename($src_imagem);

            $image->load($tmp_name);
            $image->resize(215, 200);
            $image->save($file);

            $image_thumb->load($file);
            $image_thumb->resize(100, 70);
            $image_thumb->save($file_thumb);

            $query = "UPDATE leiloes SET id_admin = " . $id_admin . ", titulo = '" . $titulo . "', descricao = '" . $descricao . "', img_src = '" . $img_src . "', duracao = " . $duracao . ", comeca_em = '" . $comeca_em . "', quantidade_item = " . $quantidade . ", valor_item = '" . $valor . "', frete_gratis = " . $frete . ", arremate_gratis = " . $arremate . ", destaque = " . $destaque . ", status = " . $status . " WHERE id = " . $id;
            $result = mysql_query($query);
        } else {
            $query = "UPDATE leiloes SET id_admin = " . $id_admin . ", titulo = '" . $titulo . "', descricao = '" . $descricao . "', duracao = " . $duracao . ", comeca_em = '" . $comeca_em . "', quantidade_item = " . $quantidade . ", valor_item = '" . $valor . "', frete_gratis = " . $frete . ", arremate_gratis = " . $arremate . ", destaque = " . $destaque . ", status = " . $status . " WHERE id = " . $id;
            $result = mysql_query($query);
        }

        $query = "SELECT id FROM leiloes WHERE titulo = '" . $titulo . "' AND descricao = '" . $descricao . "'";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Leilão editado com sucesso!';

            header('location: ../leilao');
        } else {
            $_SESSION['msg_error'] = 'Erro ao editar o leilão!';

            header('location: ../leilao');
        }

        break;

    case 'atv':
        $id = $_REQUEST['id'];

        $query = "UPDATE leiloes SET status = 1 WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT id FROM leiloes WHERE id = " . $id . " AND status = 1";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Leilão ativado com sucesso!';

            header('location: ../leilao');
        } else {
            $_SESSION['msg_error'] = 'Erro ao ativar o leilão!';

            header('location: ../leilao');
        }

        break;

    case 'itv':
        $id = $_REQUEST['id'];

        $query = "UPDATE leiloes SET status = 0 WHERE id = " . $id;
        $result = mysql_query($query);

        $query = "SELECT id FROM leiloes WHERE id = " . $id . " AND status = 0";
        $result = mysql_query($query);
        $num_result = mysql_num_rows($result);

        if ($num_result > 0) {
            $_SESSION['msg_success'] = 'Leilão inativado com sucesso!';

            header('location: ../leilao');
        } else {
            $_SESSION['msg_error'] = 'Erro ao inativar o leilão!';

            header('location: ../leilao');
        }

        break;
}
?>