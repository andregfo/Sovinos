<?php include('header.php'); ?>

<div class="banners">
    <!-- slideshow images -->
    <div class="fullbanner">  
        <div class="slideshow"> 
            <ul> 
                <li><a href="#"><img src="img/fullbanner-01.jpg" alt="" /></a></li>
                <?php
                $query_banners = "SELECT * FROM banners WHERE status = 1 ORDER BY ordem ASC";
                $result_banners = mysql_query($query_banners);
                $num_banners = mysql_num_rows($result_banners);

                if ($num_banners > 0) {
                    while ($banner = mysql_fetch_assoc($result_banners)) {
                        echo '<li><a href="#"><img src="admin/uploads/banner/img/' . $banner['img_src'] . '" alt="' . $banner['titulo'] . '" /></a></li>';
                    }
                }
                ?>
            </ul> 
        </div> 
        <!-- change image links -->
        <div class="num-nav">
            <ul>
                <?php
                for ($i = 0; $i <= $num_banners; $i++) {
                    echo '<li><a href="#" class="change_link" onclick="$(\'.slideshow\').blinds_change(' . $i . ')">' . ($i + 1) . '</a></li>';
                }
                ?>
            </ul>
        </div><!--num-nav -->
    </div>
    <!--slideshow images -->

    <a href="#" class="banner-cadastro">
        <img src="img/banner-cadastro.png" title="Cadastre-se e ganhe 5 lances" alt="Cadastre-se e ganhe 5 lances!" />
    </a>

    <ul class="social-icones">
        <li><a href="#"><img src="img/icon-rss.png" title="RSS" alt="RSS" /></a></li>
        <li><a href="#"><img src="img/icon-orkut.png" title="Orkut" alt="Orkut" /></a></li>
        <li><a href="#"><img src="img/icon-twitter.png" title="Twitter" alt="Twitter" /></a></li>
        <li class="ico-ult"><a href="#"><img src="img/icon-facebook.png" title="Facebook" alt="Facebook" /></a></li>
    </ul>

</div>

<div class="leiloes">
    <div class="tit-home">
        <h2>Estes são os leilões de hoje. Dê seu lance!</h2>
    </div>

    <?php
    $datetime_atual = date("Y-m-d H:i:s", mktime(gmdate("H") - 3, gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y")));

    //AND comeca_em >= '$datetime_atual'
    $query_leiloes = "SELECT *, DATE_FORMAT(comeca_em, '%d/%m/%Y') AS data_inicio, DATE_FORMAT(comeca_em, '%T') AS hora_inicio FROM leiloes WHERE status = 1 AND finalizado = 0 ORDER BY comeca_em ASC ";
    $result_leiloes = mysql_query($query_leiloes);
    $num_leiloes = mysql_num_rows($result_leiloes);

    $count = 1;
    if ($num_leiloes > 0) {
        while ($leilao = mysql_fetch_assoc($result_leiloes)) {
            $lance_valor = 0;
            $lance_usuario = "---";

            $query_lances = "SELECT l.valor_lance, u.login FROM lances l, usuarios u WHERE l.id_leilao = " . $leilao['id'] . " ORDER BY l.id DESC LIMIT 0, 1";
            $result_lances = mysql_query($query_lances);
            $num_lances = mysql_num_rows($result_lances);

            if ($num_lances > 0) {
                $lance = mysql_fetch_assoc($result_lances);

                $lance_valor = $lance['valor_lance'];
                $lance_usuario = $lance['login'];
            }
            ?>
            <div class="<?php echo  ($count %3 == 0) ? 'produto-ult' : 'produto'; ?>" id="leilao_<?php echo $leilao['id'] ?>">
                <?php if ($leilao['comeca_em'] > $datetime_atual): ?>
                    <a style="text-decoration:none;" href="<?php echo $leilao['slug']; ?>" title="">
                        <div style="position:relative; left: -10px;" id="targe_auction_<?php echo $leilao['id'] ?>">
                            <div class="product-targe" id="targe_<?php echo $leilao['id'] ?>">
                                Início do Cronômetro:
                                <div class="product-targe_time"><?php echo $leilao['data_inicio'] ?> - <?php echo $leilao['hora_inicio'] ?></div>
                                <span class="product-targe_weekday">(Horário de Brasília)</span>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                <a href="<?php echo $leilao['slug']; ?>" class="desc-prod">
                    <h3><?php echo $leilao['titulo']; ?></h3>
                    
                    <?php if($leilao['frete_gratis'] || $leilao['arremate_gratis']): ?>
                        <div style="position:relative;" id="icons_auction_<?php echo $leilao['id'] ?>">
                            <?php if($leilao['arremate_gratis']): ?>
                                <div class="product-icon icon-free" title="Valor de Arremate Grátis"></div>
                            <?php endif; ?>
                            <?php if($leilao['frete_gratis']): ?>
                                <div class="product-icon icon-shipping" title="Frete Grátis"></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="img-prod">
                        <img src="admin/uploads/leilao/img/<?php echo $leilao['img_src']; ?>" alt="<?php echo $leilao['titulo']; ?>" title="<?php echo $leilao['titulo']; ?>" /> 
                    </div>
                </a>	

                <div class="valor-prod">
                    <span id="valor_<?php echo $leilao['id'] ?>">R$ <?php echo number_format($lance_valor, 2, ',', '.'); ?></span>
                    <!--<p>95% de economia</p>-->
                </div>

                <div class="box-lance" id="box_lance_<?php echo $leilao['id'] ?>">
                    <div class="box-contador contador-verde" id="cont_<?php echo $leilao['id'] ?>"><?php echo $leilao['duracao']; ?></div>

                    <div class="lance-usuario">
                        <!--<a href="#">--><button type="button" class="button_submit" value="<?php echo $leilao['id'] ?>"><img src="img/bt-lance.png" title="Lance" alt="Lance" /></button><!--</a>-->
                        <p id="usuario_<?php echo $leilao['id'] ?>" class="usuario_lance"><?php echo $lance_usuario; ?></p>
                    </div>
                </div>
            </div>
            <?php
            $count++;
        }
    }
    ?>
</div>
<div class="paginacao">
    <ul>
        <li class="bt-grande"><a href="#">Anterior</a></li>
        <li class="num"><a href="#" class="ativo">1</a></li>
        <li class="num"><a href="#">2</a></li>
        <li class="num"><a href="#">3</a></li>
        <li class="bt-grande"><a href="#">Próximo</a></li>
    </ul>
</div>

<div class="prox-leiloes">
    <div class="tit-home">
        <h2>Próximos leilões em destaque</h2>
    </div>
    
    <?php
    $query_leiloes_dest = "SELECT titulo, slug, img_src, DATE_FORMAT(comeca_em, '%d/%m') AS data_inicio, DATE_FORMAT(comeca_em, '%T') AS hora_inicio FROM leiloes WHERE status = 1 AND finalizado = 0 AND destaque = 1 ORDER BY comeca_em ASC LIMIT 0, 7";
    $result_leiloes_dest = mysql_query($query_leiloes_dest);
    $num_leiloes_dest = mysql_num_rows($result_leiloes_dest);

    if ($num_leiloes_dest > 0) {
        echo '<div class="slide-prox-leiloes"><div class="conteudo-prox-leiloes"><ul id="slider1" class="multiple">';
        while ($leilao_dest = mysql_fetch_assoc($result_leiloes_dest)) {
            ?>
            <li>
                <p class="tit">
                    <a href="<?php echo $leilao_dest['slug']; ?>"><?php echo $leilao_dest['titulo']; ?></a>
                </p>
                <img src="admin/uploads/leilao/thumb/<?php echo $leilao_dest['img_src']; ?>" class="img-destslide" alt="<?php echo $leilao['titulo']; ?>" title="<?php echo $leilao['titulo']; ?>" />
                <p><?php echo $leilao_dest['data_inicio']; ?> - <?php echo $leilao_dest['hora_inicio']; ?></p> 
            </li>
            <?php
        }
        echo '</ul></div></div>';
    }
    ?>
</div>

<div class="ultimos-ganhadores">
    <div class="tit-home">
        <h2>Confira os últimos ganhadores!</h2>
    </div>

    <div class="box-item-gan">
        <div class="item-img">
            <a href="#" title="#"><img src="img/tv-32-led-sony-bravia-1.png" alt="" title="" width="140" height="100"  /></a>
        </div>
        <div class="item-desc">
            <h3> Câmera Digital 14 MP com 18x Zoom Óptico, Filma em HD e LCD 3'' - Fuji </h3>
            <p>
                Possui zoom potente, excelente qualidade de imagem para imagens de vídeo HD e ainda por meio de diversas 
                funções de suporte com as renomadas lentes Fujinon.
            </p>
        </div>
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Valor de mercado:</p>
                <span>R$ 568,89</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Vendido por:</p>
                <span>R$ 31,23</span>
            </div>
        </div>
        <div class="item-usuario">
            <p class="vd-tit">Arrematado por:</p>
            <span>ramonoliver</span>
        </div>			
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Iniciado em:</p>
                <span>01/01/2012 - 15:00</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Arrematado em:</p>
                <span>14/01/2012 - 13:00</span>
            </div>
        </div>
    </div>
    <div class="box-item-gan">
        <div class="item-img">
            <a href="#" title="#"><img src="img/faqueiro-aco-classic-home-1.png" alt="" title="" width="140" height="100"  /></a>
        </div>
        <div class="item-desc">
            <h3> Câmera Digital 14 MP com 18x Zoom Óptico, Filma em HD e LCD 3'' - Fuji </h3>
            <p>
                Possui zoom potente, excelente qualidade de imagem para imagens de vídeo HD e ainda por meio de diversas 
                funções de suporte com as renomadas lentes Fujinon.
            </p>
        </div>
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Valor de mercado:</p>
                <span>R$ 568,89</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Vendido por:</p>
                <span>R$ 31,23</span>
            </div>
        </div>
        <div class="item-usuario">
            <p class="vd-tit">Arrematado por:</p>
            <span>ramonoliver</span>
        </div>			
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Iniciado em:</p>
                <span>01/01/2012 - 15:00</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Arrematado em:</p>
                <span>14/01/2012 - 13:00</span>
            </div>
        </div>
    </div>
    <div class="box-item-gan">
        <div class="item-img">
            <a href="#" title="#"><img src="img/tv-32-led-sony-bravia-1.png" alt="" title="" width="140" height="100"  /></a>
        </div>
        <div class="item-desc">
            <h3> Câmera Digital 14 MP com 18x Zoom Óptico, Filma em HD e LCD 3' - Fuji </h3>
            <p>
                Possui zoom potente, excelente qualidade de imagem para imagens de vídeo HD e ainda por meio de diversas 
                funções de suporte com as renomadas lentes Fujinon.
            </p>
        </div>
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Valor de mercado:</p>
                <span>R$ 568,89</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Vendido por:</p>
                <span>R$ 31,23</span>
            </div>
        </div>
        <div class="item-usuario">
            <p class="vd-tit">Arrematado por:</p>
            <span>ramonoliver</span>
        </div>			
        <div class="item-valor-data">
            <div class="item-merc">
                <p class="vd-tit">Iniciado em:</p>
                <span>01/01/2012 - 15:00</span>
            </div>
            <div class="item-arre">
                <p class="vd-tit">Arrematado em:</p>
                <span>14/01/2012 - 13:00</span>
            </div>
        </div>
    </div>
</div>
<div class="paginacao">
    <ul>
        <li class="bt-grande"><a href="#">Anterior</a></li>
        <li class="num"><a href="#" class="ativo">1</a></li>
        <li class="num"><a href="#">2</a></li>
        <li class="num"><a href="#">3</a></li>
        <li class="bt-grande"><a href="#">Próximo</a></li>
    </ul>
</div>

<?php include('footer.php'); ?>