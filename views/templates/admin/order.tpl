<br />
{if isset($upela_offert)}
{if $upela_message}
	<p class="{$upela_message_class}">{$upela_message}</p>
{/if}
<fieldset>
	<legend><img src="../modules/upela/upela.gif"> {l s='Upela'}</legend>
	{l s='MSG_UPELA_EXPEDITION_ID' mod="upela"} : <b>{$upela_offert->id_expeditions}</b><br />
	{l s='MSG_UPELA_OFFERT_ID' mod="upela"} : <b>{$upela_offert->id_offre}</b><br />
	{l s='MSG_UPELA_CARRIER' mod="upela"} : <b>{$upela_offert->transporteur} ({$upela_offert->code_transporteur})</b><br />
	{l s='MSG_UPELA_SERVICE' mod="upela"} : <b>{$upela_offert->service} ({$upela_offert->code_service})</b><br />
	{l s='MSG_UPELA_DELIVERY_DATE' mod="upela"} : <b>{$upela_offert->date_livraison}</b><br />
	{l s='MSG_UPELA_PACKAGES' mod="upela"} : <b>{$upela_offert->packages}</b><br />
	{l s='MSG_UPELA_WEIGHT' mod="upela"} : <b>{$upela_offert->weight}</b><br />		
	<hr />
	<form action="{$upela_module_uri}" method="post" id="upela_ship_demand" name="upela_ship_demand" >
		<input type="hidden"  name="id_order" value="{$id_order}" />
		{l s="MSG_UPELA_PACKAGES" mod="upela"} : <input type="text" id="upela_packages"  name="upela_packages" value="{$upela_offert->packages}" style="width:20px"/> &nbsp; &nbsp;  
		{l s="MSG_UPELA_WEIGHT" mod="upela"} : <input type="text" id="upela_weight"  name="upela_weight" value="{$upela_offert->weight}" style="width:30px" />  &nbsp; &nbsp; 
		<input type="submit" value="{l s="MSG_UPELA_RESEND_SHIPPING_DEMAND" mod="upela"}" name="resendShipDemand"/>
	</form>
</fieldset>
{/if}
