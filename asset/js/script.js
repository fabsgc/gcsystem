/* ###################### BLOC CONNEXION ################### 
##########################################################*/

var nombre = 0;
var nombreConnexion = 0;

$(document).ready(function(e){
	$('.menu_right_button').click(function(){
		if(nombre == 0){
			$('#menu_right_button_slide').css('overflow-y', 'scroll');

			$('#menu_right_button_slide').animate({
				opacity : '+=1'
		 	},1, 'swing' );
		 	$('#menu_right_button_slide').animate({
				width : '+=250px'
		 	},400, 'swing' );

		 	nombre = 1;

		 	$(this).css('background-color', '#3793cd');
		 	$(this).css('border-bottom', '1px solid #2379b0');
			$(this).css('border-top', '1px solid #74b6e0');

			$('#menu_right .first').css('border-top', '');
		}
		else{
		 	nombre =1;

		 	$('.menu_right_button').css('background', '');
		 	$('.menu_right_button').css('border-bottom', '');
			$('.menu_right_button').css('border-top', '');

			$(this).css('background-color', '#3793cd');
		 	$(this).css('border-bottom', '1px solid #2379b0');
			$(this).css('border-top', '1px solid #74b6e0');

			$('#menu_right .first').css('border-top', '');
		}
	});

	$('#main').mouseover(function(){
		if(nombre == 1){
		 	$('#menu_right_button_slide').css('overflow-y', 'hidden');

		 	$('#menu_right_button_slide').animate({
				width : '-=250px'
		 	},400,'swing' );
		 	$('#menu_right_button_slide').animate({
				opacity : '-=1'
		 	},5, 'swing' );

		 	nombre = 0;

		 	$('.menu_right_button').css('background', '');
		 	$('.menu_right_button').css('border-bottom', '');
			$('.menu_right_button').css('border-top', '');

			$('#menu_right .first').css('border-top', '');
			$('#menu_right_button_slide').css('overflow-y', 'hidden');

		}
	});


	$('.bloc_connexion a').click(function(){
		if(nombreConnexion == 0){
			$('#connexion_hover_visible').animate({
				top : '+=33px'
		 	},1, 'swing' );

		 	$('#connexion_hover_visible').animate({
				height : '+=215px',
				opacity : '+=0.15'
		 	},450, 'swing' );

		 	nombreConnexion = 1;
		}
		else{
			$('#connexion_hover_visible').animate({
				height : '-=215px',
				opacity : '-=0.15'
		 	},450, 'swing' ).delay(200);

		 	$('#connexion_hover_visible').animate({
				height : '+=215px',
				opacity : '+=0.15'
		 	},450, 'swing' );

		 	nombreConnexion =1;
		}
	});

	$('#header_bottom').mouseover(function(){
		if(nombreConnexion == 1){
		 	$('#connexion_hover_visible').animate({
				height : '-=215px',
				opacity : '-=0.15'
		 	},450,'swing' ).delay(200);
		 	nombreConnexion = 0;

		 	$('#connexion_hover_visible').animate({
				top : '-=33px'
			},0, 'swing' );
		}
	});

	$('#body').mouseover(function(){
		if(nombreConnexion == 1){
		 	$('#connexion_hover_visible').animate({
				height : '-=215px',
				opacity : '-=0.15'
		 	},450,'swing' ).delay(200);
		 	nombreConnexion = 0;

		 	$('#connexion_hover_visible').animate({
				top : '-=33px'
			},0, 'swing' );
		}
	});

	$('#header_middle').mouseover(function(){
		if(nombreConnexion == 1){
		 	$('#connexion_hover_visible').animate({
				height : '-=215px',
				opacity : '-=0.15'
		 	},450,'swing' ).delay(200);
		 	nombreConnexion = 0;

		 	$('#connexion_hover_visible').animate({
				top : '-=33px'
			},0, 'swing' );
		}
	});

	$('#right').mouseover(function(){
		if(nombreConnexion == 1){
		 	$('#connexion_hover_visible').animate({
				height : '-=215px',
				opacity : '-=0.15'
		 	},450,'swing' ).delay(200);
		 	nombreConnexion = 0;

		 	$('#connexion_hover_visible').animate({
				top : '-=33px'
			},0, 'swing' );
		}
	});
});

/* ###################### TOOLBAR ########################## 
##########################################################*/

function displayNoneToolbar()
{
	var buttonId = ['menu_right_button_slide_user', 'menu_right_button_slide_cp', 'menu_right_button_slide_mp', 'menu_right_button_slide_social', 'menu_right_button_slide_forum', 'menu_right_button_slide_write', 'menu_right_button_slide_folder', 'menu_right_button_slide_pastebin', 'menu_right_button_slide_admin', 'menu_right_button_slide_plus'];

	for (var i = 0; i < buttonId.length; i++) { 
		document.getElementById(buttonId[i]).style.display = "none";
	}
}

function displayBlockToolbar(id)
{
	document.getElementById(id).style.display = "block";

	$(document).ready(function(e){
		$('#menu_right_button_slide #contenu').animate({
			opacity : '-=1'
		},0, 'swing' ).delay(75);
	});

	$(document).ready(function(e){
		$('#menu_right_button_slide #contenu').animate({
			opacity : '+=1'
		},400, 'swing' );
	});
}

function openUser()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_user');
}

function openCp()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_cp');
}

function openMp()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_mp');
}

function openSocial()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_social');
}

function openForum()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_forum');
}

function openWrite()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_write');
}

function openFolder()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_folder');
}

function openPastebin(){
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_pastebin');
}

function openAdmin()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_admin');
}

function openPlus()
{
	displayNoneToolbar();
	displayBlockToolbar('menu_right_button_slide_plus');
}

function closeNotifBody(objet){
	$(objet.parentNode).animate({
         height : "0px",
         opacity : "0",
         margin : "0",
         padding: "0"
    },
        250,
        function(){$(objet.parentNode).remove();});
}

/* ###################### TOOLTIP {{{{{{{################### 
##########################################################*/

if(window.innerWidth > 999)
{
	!function($){"use strict"
	var transitionEnd
	$(document).ready(function(){$.support.transition=(function(){var thisBody=document.body||document.documentElement
	,thisStyle=thisBody.style
	,support=thisStyle.transition!==undefined||thisStyle.WebkitTransition!==undefined||thisStyle.MozTransition!==undefined||thisStyle.MsTransition!==undefined||thisStyle.OTransition!==undefined
	return support})()
	if($.support.transition){transitionEnd="TransitionEnd"
	if($.browser.webkit){transitionEnd="webkitTransitionEnd"}else if($.browser.mozilla){transitionEnd="transitionend"}else if($.browser.opera){transitionEnd="oTransitionEnd"}}})
	var Twipsy=function(element,options){this.$element=$(element)
	this.options=options
	this.enabled=true
	this.fixTitle()}
	Twipsy.prototype={show:function(){var pos
	,actualWidth
	,actualHeight
	,placement
	,$tip
	,tp
	if(this.hasContent()&&this.enabled){$tip=this.tip()
	this.setContent()
	if(this.options.animate){$tip.addClass('fade')}
	$tip
	.remove()
	.css({top:0,left:0,display:'block'})
	.prependTo(document.body)
	pos=$.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth
	,height:this.$element[0].offsetHeight})
	actualWidth=$tip[0].offsetWidth
	actualHeight=$tip[0].offsetHeight
	placement=maybeCall(this.options.placement,this,[$tip[0],this.$element[0]])
	switch(placement){case'below':tp={top:pos.top+pos.height+this.options.offset,left:pos.left+pos.width/2-actualWidth/2}
	break
	case'above':tp={top:pos.top-actualHeight-this.options.offset,left:pos.left+pos.width/2-actualWidth/2}
	break
	case'left':tp={top:pos.top+pos.height/2-actualHeight/2,left:pos.left-actualWidth-this.options.offset}
	break
	case'right':tp={top:pos.top+pos.height/2-actualHeight/2,left:pos.left+pos.width+this.options.offset}
	break}
	$tip
	.css(tp)
	.addClass(placement)
	.addClass('in')}}
	,setContent:function(){var $tip=this.tip()
	$tip.find('.twipsy-inner')[this.options.html?'html':'text'](this.getTitle())
	$tip[0].className='twipsy'}
	,hide:function(){var that=this
	,$tip=this.tip()
	$tip.removeClass('in')
	function removeElement(){$tip.remove()}
	$.support.transition&&this.$tip.hasClass('fade')?$tip.bind(transitionEnd,removeElement):removeElement()}
	,fixTitle:function(){var $e=this.$element
	if($e.attr('title')||typeof($e.attr('data-original-title'))!='string'){$e.attr('data-original-title',$e.attr('title')||'').removeAttr('title')}}
	,hasContent:function(){return this.getTitle()}
	,getTitle:function(){var title
	,$e=this.$element
	,o=this.options
	this.fixTitle()
	if(typeof o.title=='string'){title=$e.attr(o.title=='title'?'data-original-title':o.title)}else if(typeof o.title=='function'){title=o.title.call($e[0])}
	title=(''+title).replace(/(^\s*|\s*$)/,"")
	return title||o.fallback}
	,tip:function(){return this.$tip=this.$tip||$('<div class="twipsy" />').html(this.options.template)}
	,validate:function(){if(!this.$element[0].parentNode){this.hide()
	this.$element=null
	this.options=null}}
	,enable:function(){this.enabled=true}
	,disable:function(){this.enabled=false}
	,toggleEnabled:function(){this.enabled=!this.enabled}
	,toggle:function(){this[this.tip().hasClass('in')?'hide':'show']()}}
	function maybeCall(thing,ctx,args){return typeof thing=='function'?thing.apply(ctx,args):thing}
	$.fn.twipsy=function(options){$.fn.twipsy.initWith.call(this,options,Twipsy,'twipsy')
	return this}
	$.fn.twipsy.initWith=function(options,Constructor,name){var twipsy
	,binder
	,eventIn
	,eventOut
	if(options===true){return this.data(name)}else if(typeof options=='string'){twipsy=this.data(name)
	if(twipsy){twipsy[options]()}
	return this}
	options=$.extend({},$.fn[name].defaults,options)
	function get(ele){var twipsy=$.data(ele,name)
	if(!twipsy){twipsy=new Constructor(ele,$.fn.twipsy.elementOptions(ele,options))
	$.data(ele,name,twipsy)}
	return twipsy}
	function enter(){var twipsy=get(this)
	twipsy.hoverState='in'
	if(options.delayIn==0){twipsy.show()}else{twipsy.fixTitle()
	setTimeout(function(){if(twipsy.hoverState=='in'){twipsy.show()}},options.delayIn)}}
	function leave(){var twipsy=get(this)
	twipsy.hoverState='out'
	if(options.delayOut==0){twipsy.hide()}else{setTimeout(function(){if(twipsy.hoverState=='out'){twipsy.hide()}},options.delayOut)}}
	if(!options.live){this.each(function(){get(this)})}
	if(options.trigger!='manual'){binder=options.live?'live':'bind'
	eventIn=options.trigger=='hover'?'mouseenter':'focus'
	eventOut=options.trigger=='hover'?'mouseleave':'blur'
	this[binder](eventIn,enter)[binder](eventOut,leave)}
	return this}
	$.fn.twipsy.Twipsy=Twipsy
	$.fn.twipsy.defaults={animate:true
	,delayIn:0
	,delayOut:0
	,fallback:''
	,placement:'above'
	,html:false
	,live:false
	,offset:0
	,title:'title'
	,trigger:'hover'
	,template:'<div class="twipsy-arrow" style="z-index: 1500;"></div><div class="twipsy-inner"></div>'}
	$.fn.twipsy.rejectAttrOptions=['title']
	$.fn.twipsy.elementOptions=function(ele,options){var data=$(ele).data()
	,rejects=$.fn.twipsy.rejectAttrOptions
	,i=rejects.length
	while(i--){delete data[rejects[i]]}
	return $.extend({},options,data)}}(window.jQuery||window.ender);
}

/* ###################### EDITEUR AJAX PREVIEW ############# 
##########################################################*/

function editorGcAjaxPreview(id){
	setWidth(id);

	if(document.getElementById(''+id+'').value != ""){
		$( document ).ready(function() {
			$.ajax({
		  		type: "POST",
		  		url: "/other/ajax/editor",
		  		data: { message: document.getElementById(''+id+'').value}
			}).done(function( msg ) {
				document.getElementById('preview_'+id+'').style.display = 'block';
				document.getElementById('preview_'+id+'').innerHTML = msg;
				parseCode();
			});
		});
	}
}

function parseCode(){
	SyntaxHighlighter.config.stripBrs = false;      
    SyntaxHighlighter.highlight();
}


function setWidth(id){
	var width = document.getElementById(''+id+'').offsetWidth - 16;
	$('#preview_'+id).width(width);
}

/* ###################### FANCY BOX ######################## 
##########################################################*/

$(document).ready(function() {
	$("a#single_image").fancybox();
	
	/* Using custom settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});

	/* Apply fancybox to multiple items */
	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});
});

/* ###################### BUTTON RADIO EDIT PROFIL ######### 
##########################################################*/

function setProfilEditCompetence(competence, value){
	var competences = document.getElementsByClassName('label_competence_'+competence);
	var i;
    
    for (i = 0; i < competences.length; i++) {
        competences[i].innerHTML = '';
    }

	document.getElementById('label_competence_'+competence+'_'+value).innerHTML = '<div class="checked"></div>';
	$("input[name=competence_"+competence+"][value=" + value + "]").attr('checked', 'checked');
}

/* ###################### BOITE DE DIALOGUE ######### 
##########################################################*/

function OpenDialog(widthWindow, heightWindow, titleWindow, url){
	document.getElementById('dialog-modal').innerHTML = '<iframe style="width: '+widthWindow+'px; height:'+(heightWindow-48)+'px; overflow-x: hidden;" src="'+url+'"></iframe>';

	$("#dialog-modal" ).dialog({
    	width: widthWindow,
      	height: heightWindow,
      	modal: true,
      	title : titleWindow,
      	closeOnEscape: true
    });
}

/* ###################### MP DELETE SELECTION ######### 
##########################################################*/

var checkboxCheckedNumber = 0;

//appuie bouton tout sélectionner
function mpDefaultSelectAll(){
	var checkbox = document.getElementById("mp_checkbox_all");
	var checkBoxAll = document.getElementsByClassName('mp_checkbox');
	var i = 0;

	if(checkbox.checked){
		checkboxCheckedNumber = checkBoxAll.length;
		document.getElementById('buttonDelMp').style.display = 'inline-block';
		document.getElementById('buttonReadMp').style.display = 'inline-block';

		for(i=0;i< checkBoxAll.length;i++){
			checkBoxAll[i].checked = true;
		}
	}
	else{
		checkboxCheckedNumber = 0;
		document.getElementById('buttonDelMp').style.display = 'none';
		document.getElementById('buttonReadMp').style.display = 'none';

		for(i=0;i< checkBoxAll.length;i++){
			checkBoxAll[i].checked = false;
		}
	}
}

//appuie sur un bouton
function mpDefaultSelectOne(id){
	var checkbox = document.getElementById(id);
	var checkBoxAll = document.getElementsByClassName('mp_checkbox');
	var i = 0;
	var allChecked = true; //si elles sont toutes cochées

	if(checkbox.checked){
		checkboxCheckedNumber++;
		document.getElementById('buttonDelMp').style.display = 'inline-block';
		document.getElementById('buttonReadMp').style.display = 'inline-block';

		for(i=0;i< checkBoxAll.length;i++){
			if(checkBoxAll[i].checked == false){
				allChecked = false;
			}
		}

		if(allChecked == true){
			document.getElementById("mp_checkbox_all").checked = true;
		}
		else{
			document.getElementById("mp_checkbox_all").checked = false;
		}
	}
	else{
		checkboxCheckedNumber--;
		document.getElementById("mp_checkbox_all").checked = false;

		if(checkboxCheckedNumber <= 0){
			document.getElementById('buttonDelMp').style.display = 'none';
			document.getElementById('buttonReadMp').style.display = 'none';
		}
	}
}

/* ###################### MP READ ######### 
##########################################################*/
function mpReadOpenAdd(){
	if($('#inputAdd').height() == 0){
		$('#inputAdd').animate({
         	height : "50px",
        	opacity : "1"
    	},250);

    	$('#input_destinataire_list').focus();
    	$('#buttonAdd').attr("src","/asset/image/static/minus32_white.png");
	}
	else{
		$('#inputAdd').animate({
         	height : "0px",
        	opacity : "0"
    	},250);

    	$('#input_destinataire_list').val('');
    	$('#buttonAdd').attr("src","/asset/image/static/plus32_white.png");
	}
}

function mpReadAnswer(){
	$("html,body").animate({scrollTop:$("#form").offset().top},"slow");
}