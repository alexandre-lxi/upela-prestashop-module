<script type="text/javascript">
	{foreach $offerts as $key=>$offert}
		{literal}
			(function(){
				var id_carrier = {/literal}{$offert->id_carrier}{literal};
				var delay = {/literal}"{l s='MSG_UPELA_DELIVERY_DATE' mod='upela' js=1} {$offert->getFormattedDate()|addslashes}"{literal};
				var info = {/literal}"{$offert->transporteur|addslashes} (<i>{$offert->service|addslashes}</i>)"{literal};
				var resume_info = $("INPUT#id_carrier"+id_carrier).parents("TR");
				resume_info.find(".carrier_infos").html(info);
				resume_info.find(".carrier_name LABEL").html("{/literal}{$offert->transporteur|addslashes}{literal}");
				resume_info.find(".carrier_infos").html(info);
				resume_info.find(".carrier_infos").append('<br />' + delay);
			})();
		{/literal}
	{/foreach}
</script>