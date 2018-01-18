	{if isset($upela_offert)}
	<div class="panel">
	{if $upela_message}
		<p class="{$upela_message_class}">{$upela_message}</p>
		<br />
	{/if}
		<h3><i class="icon-truck"></i> {l s='Upela'}</h3>
		<p>
		{l s='MSG_UPELA_EXPEDITION_ID' mod="upela"} : <b>{$upela_offert->id_expeditions}</b><br />
		{l s='MSG_UPELA_OFFERT_ID' mod="upela"} : <b>{$upela_offert->id_offre}</b><br />
		{l s='MSG_UPELA_CARRIER' mod="upela"} : <b>{$upela_offert->transporteur} ({$upela_offert->code_transporteur})</b><br />
		{l s='MSG_UPELA_SERVICE' mod="upela"} : <b>{$upela_offert->service} ({$upela_offert->code_service})</b><br />
		{l s='MSG_UPELA_DELIVERY_DATE' mod="upela"} : <b>{$upela_offert->date_livraison}</b><br />
		{l s='MSG_UPELA_PACKAGES' mod="upela"} : <b>{$upela_offert->packages}</b><br />
		{l s='MSG_UPELA_WEIGHT' mod="upela"} : <b>{$upela_offert->weight}</b><br />		
		</p>
		<hr />
		<form action="{$upela_module_uri}" method="post" id="upela_ship_demand" name="upela_ship_demand"  class="form-inline" >
			<input type="hidden"  name="id_order" value="{$id_order}" />	
			<div class="form-group">
					<label for="upela_packages">{l s="MSG_UPELA_PACKAGES" mod="upela"} : </label>
					<input style="display:inline-block; width:40px!important; margin: 0 10px" type="text" id="upela_packages"  name="upela_packages" value="{$upela_offert->packages}" />
					<label for="upela_packages">{l s="MSG_UPELA_WEIGHT" mod="upela"} : </label>
					<input style="display:inline-block;  width:60px!important;  margin: 0 10px" type="text" id="upela_weight"  name="upela_weight" value="{$upela_offert->weight}" />
					<input style="display:inline-block; margin-left: 15px" type="submit" value="{l s="MSG_UPELA_RESEND_SHIPPING_DEMAND" mod="upela"}" name="resendShipDemand" />
			</div>
		</form>
		</div>
	{/if}
