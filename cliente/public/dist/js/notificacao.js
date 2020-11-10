function atualizaNotificacao(){
    $.getJSON('/notificacao/notifica', function(data){
        $(".notifications-menu").find('.label').text(data.length);

        if (data.length==0){
            textoHeader = 'Você não tem nenhuma notificação';
        }else if (data.length==1){
            textoHeader = 'Você tem ' + data.length + ' notificação.';
        }else{
            textoHeader = 'Você tem ' + data.length + ' notificações.';
        }
        $(".notifications-menu").find('.header').text(textoHeader);

        $notificacaoLista = $(".notifications-menu").find('.menu');
        $notificacaoLista.text('');
        $.each(data, function (key, item) {
            notificacao = '<li><a href="#" data-id="' + item.idnotificacao + '" data-url="' + item.url_noti + '">\n' +
                ' <i class="fa ' + item.icone  +' text-aqua"></i> ' + item.texto + '\n' +
                '</a></li>';
            $notificacaoLista.append(notificacao);
        });
        $notificacaoLista.find("a").on("click", function(e){
            $.getJSON('/notificacao/leitura/' + $(this).data("id"), function(data){
                if (data.status==1){
                    if (data.mensagem!=''){
                        document.location = data.mensagem;
                    }else{
                        atualizaNotificacao();
                    }

                }
            });

        })
    });
}

atualizaNotificacao();

