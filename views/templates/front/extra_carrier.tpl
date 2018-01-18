<script type="text/javascript">
	{foreach $offerts as $key=>$offert}
		{literal}
			(function(){
				var id_carrier = {/literal}{$offert->id_carrier}{literal};
				var delay = {/literal}"{l s='MSG_UPELA_DELIVERY_DATE' mod='upela' js=1} {$offert->getFormattedDate()|addslashes}"{literal};
				var info = {/literal}"{$offert->transporteur|addslashes} (<i>{$offert->service|addslashes}</i>)"{literal};
				var resume_info = $("INPUT[name='id_carier[]'][value='"+id_carrier+"']").parents("TABLE").siblings(".resume").find("TD:nth(1)");
				if (resume_info.find(".upela-info").length == 0) {
					resume_info.append("<div class='upela-info'></div>");
				} // if
				resume_info.find(".upela-info").html(info);
				resume_info.find(".delivery_option_delay").html(delay);
			})();
		{/literal}
	{/foreach}
</script>