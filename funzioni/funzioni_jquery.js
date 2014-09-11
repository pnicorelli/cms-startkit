this.imagePreview = function(){	
	/* CONFIG */
		
		xOffset = 10;
		yOffset = 30;
		
	/* END CONFIG */
	$("a.preview").hover(function(e){
		e.preventDefault();
		
		this.t = this.title;
		this.title = "";	
		var c = (this.t != "") ? "<br/>" + this.t : "";
		$("body").append("<p id='preview'><img src='"+ this.name +"' />"+ c +"</p>");								 
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#preview").remove();
    });	
	$("a.preview").mousemove(function(e){
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};


//funzione per i rollover delle immagini
this.imageRollover = function(){
	
	$("a img.rollover").hover(function(){
		$(this).attr("src",$(this).attr("name01"));	
	}, function(){
		$(this).attr("src",$(this).attr("name00"));
	});

};


vistaCodice = function() {

	$(".vedi_ris p").click(function(){
		campo =  $(this).parent().find(":input");
		testo = campo.attr('value');
		nomeCampo = campo.attr('name');
		classeCampo = campo.attr('class');

		if($(this).html()=="[Vista Sito]")
		{

			testoDiv = testo.replace(/\n/g,"<br />");
			$(this).html("[Vista Codice]")

			//$(this).parent().find("div").html("<input type='text' name='"+nomeCampo+"' value='"+testo+"' class=\""+classeCampo+"\"><div class=\""+classeCampo+"\" style=\"background-color:#efefef; padding-bottom:3px; overflow:auto;\">"+testoDiv+"</div>");

			$(this).parent().find("textarea").css("display","none");
			$(this).after("<div class=\""+classeCampo+"\" style=\"background-color:#efefef; padding-bottom:3px; overflow:auto;\">"+testoDiv+"</div>");
		}
		else
		{
			$(this).html("[Vista Sito]")
			$(this).parent().find("textarea").css("display","block");
			$(this).parent().find("div").remove();
		}
	})
}

