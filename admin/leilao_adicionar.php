<?php include_once 'header.php'; ?>

<!-- Content wrapper -->
<div class="wrapper">
    <?php include_once 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <div class="title"><h5>Adicionar Leilão</h5></div>

        <!-- Form begins -->
        <form action="actions/leilaoAction.php" method="post" id="valid" class="mainForm" enctype="multipart/form-data">
            <!-- Input text fields -->
            <input type="hidden" name="action" value="add" />
            <input type="hidden" name="id_admin" value="<?php echo $_SESSION["id_admin"]; ?>" />
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
                                    <option value="<?php echo $resultset['id']; ?>"><?php echo $resultset['nome']; ?></option>
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
                            <input type="text" class="validate[required]" name="titulo" id="titulo"/>
                        </div>
                        <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Descrição:</label>
                        <textarea class="wysiwyg" rows="5" cols="" name="descricao"></textarea>  
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Imagem principal:</label>
                        <div class="formRight">
                            <input type="file" id="img" name="img[]" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Imagens secundárias:</label>
                        <div class="formRight">
                            <input type="file" id="img_sec" class="multi" accept="jpeg|jpg|png|gif" name="img_sec[]" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Duração:</label>
                        <div class="formRight">
                            <input type="text" class="validate[required,custom[onlyNumberSp]]" name="duracao" id="duracao" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Ínicio:</label>
                        <div class="formRight">
                            <input type="text" class="datetimepicker" name="data_comeca_em" />
                        </div>
                        <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Quantidade:</label>
                        <div class="formRight">
                            <input type="text" class="validate[required,custom[onlyNumberSp]]" name="quantidade" id="quantidade" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Valor:</label>
                        <div class="formRight">
                            <input type="text" id="s2" class="validate[required]" name="valor" value="0.00" />
                        </div>
                        <div class="fix"></div>
                    </div>

                    <div class="rowElem noborder">
                        <label>Frete Grátis:</label>
                        <div class="formRight">                        
                            <select name="frete">
                                <option value="1">Sim</option>
                                <option value="0" selected="selected" >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Arremate Grátis:</label>
                        <div class="formRight">                        
                            <select name="arremate">
                                <option value="1">Sim</option>
                                <option value="0" selected="selected" >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Destaque:</label>
                        <div class="formRight">                        
                            <select name="destaque">
                                <option value="1">Sim</option>
                                <option value="0" selected="selected" >Não</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="rowElem noborder">
                        <label>Status:</label>
                        <div class="formRight">                        
                            <select name="status">
                                <option value="1" selected="selected" >Ativo</option>
                                <option value="0">Inativo</option>
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
<?php include_once 'footer.php'; ?>