<script type="text/javascript">
	{foreach $offerts as $key=>$offert}
		{literal}
			(function(){

				var id_carrier = {/literal}{$offert->id_carrier}{literal};
				var delay = {/literal}"{l s='MSG_UPELA_DELIVERY_DATE' mod='upela' js=1} {$offert->getFormattedDate()|addslashes}"{literal};
				var info = {/literal}"{$offert->transporteur|addslashes} (<i>{$offert->service|addslashes}</i>)"{literal};
				var resume_info = null;
				$('.delivery_option_radio').each(function(i,element){
					if ($(element).val().split(',').indexOf(id_carrier.toString()) > -1){
							var resume_info = $(element).parents("TR").find("TD:nth(2)");
							if (resume_info){
								if (resume_info.find(".upela-info").length == 0) {
									var name = resume_info.find("strong").text();
									resume_info
										.html("<strong> " + name + "</strong>")
										.append("<div class='upela-info'></div>")
										.append("<div class='delivery_option_delay'></div>");
								} // if
								resume_info
									.find(".upela-info").html(info);
								resume_info
									.find(".delivery_option_delay").html(delay);	
							}
							
					}
				});
			})();
		{/literal}
	{/foreach}
</script>