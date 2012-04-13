<?php include_once 'header.php'; ?>

<!-- Content wrapper -->
<div class="wrapper">
    <?php include_once 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <div class="title"><h5>Editar Leilão</h5></div>

        <!-- Form begins -->
        <form action="actions/leilaoAction.php" method="post" id="valid" class="mainForm">
            <!-- Input text fields -->
            <input type="hidden" name="action" value="edt" />
            <input type="hidden" name="id" value="<?php echo $_SESSION['id_edit']?>"/>
            <input type="hidden" name="id_admin" value="<?php echo $_SESSION['id_admin'] ?>" />
            <fieldset>
                <div class="widget">
                    <div class="head"><h5 class="iList">Leilão</h5></div>

                    <!--<div class="rowElem noborder">
                        <label>Categoria:</label>
                        <div class="formRight">                        
                            <select name="categoria">
                                <?php
                                include_once ('includes/conexao.php');

                                $sql = " SELECT * FROM categorias ";
                                $query = mysql_query($sql);

                                while ($resultset = mysql_fetch_array($query)):
                                    ?>
                                    <option <?php echo ($_SESSION['categoria_edit'] == $resultset['id'] ) ? 'selected = selected' : ''; ?> value="<?php echo $resultset['id']; ?>"><?php echo $resultset['nome']; ?></option>
                                    <?php
                                endwhile;
                                ?>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>-->

                    <div class="rowElem noborder">
                        <label>Título:</label>
                        <div class="formRight">
                            <input type="text" class="validate[required]" name="titulo" id="titulo" value="<?php echo utf8_encode($_SESSION['titulo_edit']) ?>" />
                        </div>
                        <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Descrição:</label>
                        <textarea class="wysiwyg" rows="5" cols="" name="descricao"><?php echo utf8_encode($_SESSION['descricao_edit']) ?></textarea>  
                    </div>

                    <div class="rowElem noborder">
                        <label>Imagem principal:</label>
                        <div class="formRight">
                            <input type="file" id="img" name="img[]" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Duração:</label>
                        <div class="formRight">
                            <input type="text" class="validate[required,custom[onlyNumberSp]]" name="duracao" id="duracao" value="<?php echo $_SESSION['duracao_edit'] ?>" />
                                   </div>
                                   <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Ínicio:</label>
                        <div class="formRight">
                            <input type="text" class="datetimepicker" name="data_comeca_em" value="<?php echo $_SESSION['comeca_em_edit'] ?>" />
                                   </div>
                                   <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Quantidade:</label>
                        <div class="formRight">
                            <input type="text" class="validate[required,custom[onlyNumberSp]]" name="quantidade" id="quantidade" value="<?php echo $_SESSION['quantidade_edit'] ?>" />
                                   </div>
                                   <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Valor:</label>
                        <div class="formRight">
                            <input type="text" id="s2" class="validate[required]" name="valor" value="<?php echo $_SESSION['valor_edit'] ?>" />
                                   </div>
                                   <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Frete Grátis:</label>
                        <div class="formRight">                        
                            <select name="frete">
                                <option value="1" <?php echo ($_SESSION['frete_edit']) ? 'selected = selected' : ''; ?> >Sim</option>
                                <option value="0" <?php echo (!$_SESSION['frete_edit']) ? 'selected = selected' : ''; ?> >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Arremate Grátis:</label>
                        <div class="formRight">                        
                            <select name="arremate">
                                <option value="1" <?php echo ($_SESSION['arremate_edit']) ? 'selected = selected' : ''; ?> >Sim</option>
                                <option value="0" <?php echo (!$_SESSION['arremate_edit']) ? 'selected = selected' : ''; ?> >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Destaque:</label>
                        <div class="formRight">                        
                            <select name="destaque">
                                <option value="1" <?php echo ($_SESSION['destaque_edit']) ? 'selected = selected' : ''; ?> >Sim</option>
                                <option value="0" <?php echo (!$_SESSION['destaque_edit']) ? 'selected = selected' : ''; ?> >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Status:</label>
                        <div class="formRight">                        
                            <select name="status">
                                <option <?php echo ($_SESSION['status_edit']) ? 'selected = selected' : ''; ?> value="1">Ativo</option>
                                <option <?php echo (!$_SESSION['status_edit']) ? 'selected = selected' : ''; ?> value="0">Inativo</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>

                    <input type="submit" value="Cadastrar" class="greyishBtn submitForm" />
                    <div class="fix"></div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="fix"></div>
</div>
<?php
//excluindo as informações do usuário usadas para edição
unset($_SESSION['id_edit']);
unset($_SESSION['titulo_edit']);
unset($_SESSION['descricao_edit']);
unset($_SESSION['duracao_edit']);
unset($_SESSION['comeca_em_edit']);
unset($_SESSION['quantidade_edit']);
unset($_SESSION['valor_edit']);
unset($_SESSION['frete_edit']);
unset($_SESSION['arremate_edit']);
unset($_SESSION['destaque_edit']);
unset($_SESSION['status_edit']);
?>
<?php include_once 'footer.php'; ?>