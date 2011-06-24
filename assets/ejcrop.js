/**
 * Javascript for EJcrop extension.
 *
 * @copyright © Digitick <www.digitick.net> 2011
 * @license GNU Lesser General Public License v3.0
 * @author Ianaré Sévi
 * @author Jacques Basseck
 * 
 */

function getCoords(coords, imageType) {
	$('#'+ imageType +'_x').val(coords.x);
	$('#'+ imageType +'_y').val(coords.y);
	$('#'+ imageType +'_x2').val(coords.x2);
	$('#'+ imageType +'_y2').val(coords.y2);
	$('#'+ imageType +'_w').val(coords.w);
	$('#'+ imageType +'_h').val(coords.h);
}

function reinitHidden(imageType) {
	$('#'+ imageType +'_x').val(0);
	$('#'+ imageType +'_y').val(0);
	$('#'+ imageType +'_x2').val(0);
	$('#'+ imageType +'_y2').val(0);
	$('#'+ imageType +'_w').val(0);
	$('#'+ imageType +'_h').val(0);
}
	
function reinitThumb(id) {
	$('#mirror_' + id).hide();
	$('#thumb_' + id).show();
}
	
function cancelCrop(jcrop) {
	var buttons = jcrop.ui.holder.next(".jcrop-buttons");
	jcrop.disable();
	buttons.find(".jcrop-start").show();
	buttons.find(".jcrop-crop, .jcrop-cancel").hide();
	reinitThumb(jcrop.ui.holder.prev("img").attr("id"));
}

function jcrop_initWithButtons(id, options) {
	var jcrop = {};

	$('body').delegate('#button_'+id,'click', function(e){
		$('#submit_'+id+', #cancel_'+id).show();
		$('#button_'+id).hide();
		if (!jcrop.id){
			jcrop.id = $.Jcrop('#'+id, options);
		}
		jcrop.id.enable();
		var dim = jcrop.id.getBounds();
		jcrop.id.animateTo([dim[0]/4, dim[1]/4,dim[0]/2,dim[1]/2]);
	});
			
	$('body').delegate('#submit_'+id,'click', function(e){
		$('#button_'+id).show();
		$('#submit_'+id+', #cancel_'+id).hide();
		thumbnailAjaxRequest(id);
		jcrop.id.release();
		jcrop.id.disable();
	});
	
	$('body').delegate('#cancel_'+id,'click', function(e){
		jcrop.id.release();
	});
}
