/**
 * efeito alert
 */
$(function () {
    // pegar elemente com corpo da mensagem
    var corpo_alert = $("#alert-message");
 
    // verificar se o elemente esta presente na pagina
    if (corpo_alert.length){
        // gerar efeito para o elemento encontrado na pagina
        setTimeout(function(){
            corpo_alert.fadeOut(4000);
      }, 3000);
    }
    
    $('input.typeahead').typeahead({
        ajax: { 
            url: '/contatos/search',    // url do serviço AJAX
            triggerLength: 2,           // mínimo de caracteres
            displayField: 'nome',       // campo do JSON utilizado de retorno
        }
    });
    
});