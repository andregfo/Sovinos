        <div class="parceiros">
            <ul>
                <li><a href="#"><img src="img/pagseguro.png" alt="PagSeguro" title="PagSeguro" /></a></li>
                <li><a href="#"><img src="img/parceiro1.png" alt="Parceiro" title="Parceiro" /></a></li>
                <li><a href="#"><img src="img/parceiro2.png" alt="Parceiro" title="Parceiro" /></a></li>
                <li class="ult"><a href="#"><img src="img/parceiro3.png" alt="Parceiro" title="Parceiro" /></a></li>
            </ul>
        </div>

        <div class="fornecedores">
            <ul>
                <li><a href="#"><img src="img/fornecedor1.png" alt="Fornecedor" /></a></li>
                <li><a href="#"><img src="img/fornecedor2.png" alt="Fornecedor" /></a></li>
                <li><a href="#"><img src="img/fornecedor3.png" alt="Fornecedor" /></a></li>
                <li><a href="#"><img src="img/fornecedor1.png" alt="Fornecedor" /></a></li>
                <li><a href="#"><img src="img/fornecedor2.png" alt="Fornecedor" /></a></li>
                <li class="ult"><a href="#"><img src="img/fornecedor3.png" alt="Fornecedor" /></a></li>
            </ul>
        </div>                

        <div class="rodape">
            <div class="logo-rodape">
                <a href="index"><img src="img/logo-sovinos-rodape.png" alt="Sovinos.com" /></a>
            </div>

            <div class="menu-rodape">
                <ul>
                    <li class="tit">Leilões</li>
                    <li><a href="#">Próximos leilões</a></li>
                    <li><a href="#">Últimos leilões</a></li>
                </ul>
                <ul>
                    <li class="tit">Como funciona?</li>
                    <li><a href="#">Passo-a-passo</a></li>
                    <li><a href="#">Perguntas frequêntes</a></li>
                    <li><a href="#">Minha conta</a></li>
                    <li><a href="#">Dicas</a></li>	
                </ul>
                <ul>
                    <li class="tit">Posso confiar?</li>
                    <li><a href="#">Auditoria</a></li>
                    <li><a href="#">Pagseguro</a></li>
                    <li><a href="#">Depoimentos</a></li>	
                </ul>
                <ul>
                    <li class="tit">Sobre</li>
                    <li><a href="#">Termos de uso</a></li>
                    <li><a href="#">Quem somos</a></li>
                    <li><a href="#">Redes sociais</a></li>
                    <li><a href="#">Mapa do site</a></li>	
                </ul>
                <ul>
                    <li class="tit">Fornecedores</li>
                    <li><a href="#">Americanas.com</a></li>
                    <li><a href="#">Submarino</a></li>	
                </ul>
            </div>
        </div>
        </div>
        </div>
        <div class="boxgeral-rodape"></div>
        <div class="copyright">
            <p><b>Sovinos.com</b> - Todos os direitos reservados</p>
        </div>
        <!-- jquery e script's -->
        <script type="text/javascript" src="js/jquery-1.4.1.js"></script>
        <script type="text/javascript" src="js/jquery.blinds-0.9.js"></script>
        <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript">
            $(window).load(function () {
                // start the slideshow
                $('.slideshow').blinds();
            })

            $(function(){
                $('#slider1').bxSlider({
                    displaySlideQty: 5,
                    moveSlideQty: 1
                });
            });
        </script>
        <script type="text/javascript">
            $('.button_submit').click(function(){              
                set_lance($(this).val());
            });
            
            function get_data_hora(){
                $.post(
                'data_hora_ajax.php',
                {  },
                function(data){
                    var exibe_time = document.getElementById("time");
                    var exibe_data = document.getElementById("data");

                    exibe_time.innerHTML = data.time;
                    exibe_data.innerHTML = data.dia + " de " + data.mes + " de " + data.ano;
                }, 'json'
            );
            }

            function get_lances(){
                $.post(
                'lances_ajax.php',
                { action: "get" },
                function(data){
                    if(data.result){
                        //alert('valor_'+data.lances[0]['id']);
                        for(i=0; i < data.lances.length; i++){
                            if(data.lances[i]['finalizou']){
                                $('#box_lance_'+data.lances[i]['id']).html("<h3>Arrematado!</h3><p>"+data.lances[i]['usuario']+"</p>");
                            } else {
                                if(data.lances[i]['comecou']) $('#targe_'+data.lances[i]['id']).css('display', 'none');
                                
                                var valor = $('#valor_'+data.lances[i]['id']).html();
                                var cont = $('#cont_'+data.lances[i]['id']).html();
                                var usuario = $('#usuario_'+data.lances[i]['id']).html();
                                //alert(valor);
                                (cont <= 10) ? $('#cont_'+data.lances[i]['id']).removeClass("contador-verde").addClass("contador-vermelho") : $('#cont_'+data.lances[i]['id']).removeClass("contador-vermelho").addClass("contador-verde");

                                if(valor != data.lances[i]['valor_lance']) $('#valor_'+data.lances[i]['id']).html("R$ "+data.lances[i]['valor_lance']);
                                if(cont != data.lances[i]['duracao']) $('#cont_'+data.lances[i]['id']).html(data.lances[i]['duracao']);
                                if(usuario != data.lances[i]['usuario']) $('#usuario_'+data.lances[i]['id']).html(data.lances[i]['usuario']);
                            }
                        }
                    }
                }, 'json'
            );
            }
            
            function set_lance(id_leilao){
                var iduser = <?php echo (isset($_SESSION['id_usuario'])) ? $_SESSION['id_usuario'] : 0; ?>;
                
                if(iduser == 0){
                    alert("É necessário efetuaro login para dar lances");
                    return false;
                }
                
                $.post(
                'lances_ajax.php',
                { action: "set", id: id_leilao, id_usuario: iduser },
                function(data){
                    if(data.result){
                        
                    }
                }, 'json'
            );
            }
            
            setInterval('get_data_hora()', 1000);
            setInterval('get_lances()', 1000);
        </script>
        <!-- fim script's -->

        <?php unset($_SESSION['msg_error']); ?>
    </body>
</html>