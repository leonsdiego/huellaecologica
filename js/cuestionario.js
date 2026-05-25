$(window).load(function(){
$('#tableSelect').delegate('tbody > tr', 'click', function(){
    $(this).find('td input[name="seleccion_usuario"]').prop('checked', true);
})
$(".subrayado").click(function() {
	if(!$(this).hasClass('alternativaSeleccionada')){
    $(this).toggleClass('alternativaSeleccionada');
    $(this).siblings().removeClass('alternativaSeleccionada');}
});
$(".subrayado").hover(function() {
	$(this).children().css('cursor', 'pointer');
	if(!$(this).hasClass('alternativaSeleccionada')){
    $(this).children().css('backgroundColor', '#0C0');
	}
}, function() {
    $(this).children().css('backgroundColor', '');
})
});