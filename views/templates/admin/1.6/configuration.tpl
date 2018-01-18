{literal}
<style>
 .upela-field {margin-bottom:10px}
 .upela-field LABEL {font-weight: bold; font-size: 0.9em; width: 310px}
 .upela-field INPUT[type=number], #UPELA_AVERAGE_PACKAGE_WEIGHT {width: 50px}
 .upela-logo {background-image: url('{/literal}{$upela_module_path}{literal}upela.png'); background-repeat: no-repeat; background-color: #EBEDF4; padding-left: 24px; background-position: 4px; margin-right: 20px}
 .buildinfo {text-align: right; font-size:0.8em}
 .kiwik-footer-logo {height: 48px; vertical-align: middle; padding-right: 5px}
 .kiwik-link {font-weight: bold; text-decoration: underline}
 .prestashop-link {font-weight: normal; text-decoration: none}
 .averages {display: inline-block}
 .averages BUTTON {margin-left: 10px}
</style>
{/literal}

{if isset($errors) && count($errors)>0}
<div class="alert alert-danger">
	{foreach $errors as $error}
		<p>{$error}</p>
	{/foreach}
</div>
{/if}


{if isset($warnings) && count($warnings)>0}
<div class="alert alert-warning">
	{foreach $warnings as $warning}
		<p>{$warning}</p>
	{/foreach}
</div>
{/if}

{if isset($infos) && count($infos)>0}
<div class="alert alert-success">
	{foreach $infos as $info}
		<p>{$info}</p>
	{/foreach}
</div>
{/if}

<div class="panel">
	<h3><i class="upela-logo"></i> {l s='MSG_UPELA_TITLE' mod='upela'} - {l s='MSG_UPELA_INTRO_TITLE' mod='upela'}</h3>
	<p>{l s='MSG_UPELA_INTRO' mod='upela'}</p>
	<p>{l s='MSG_UPELA_MORE_INFO' mod='upela'} <a href="http://www.upela.com" target="_new">www.upela.com</a></p>
</div>

 
<form method="post" action="{$current_url}"  class="form-horizontal">
	<div class="panel">
		<h3><i class="upela-logo"></i> {l s='MSG_UPELA_API_CREDENTIALS' mod='upela'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3"  for="UPELA_API_LOGIN">{l s='MSG_UPELA_API_LOGIN' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text"  maxlength="64"  value="{$UPELA_API_LOGIN}" name="UPELA_API_LOGIN" id="UPELA_API_LOGIN" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_API_PASSWORD">{l s='MSG_UPELA_API_PASSWORD' mod='upela'} :</label>		
			<div class="col-lg-3"><input type="text" value="{$UPELA_API_PASSWORD}" name="UPELA_API_PASSWORD" id="UPELA_API_PASSWORD" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_API_MODE">{l s='MSG_UPELA_API_MODE' mod='upela'} :</label>
			<div class="col-lg-3">
			<select name="UPELA_API_MODE" id="UPELA_API_MODE">
				{foreach $modes as $id=>$mode}
					<option value="{$id}" {if $UPELA_API_MODE == $id}selected='selected'{/if}>{$mode.name} [{$mode.url}]</option>
				{/foreach}
			</select>
			</div>
		</div>
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_UPELA_CHOICE' mod='upela'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SELECTION">{l s='MSG_UPELA_SELECTION' mod='upela'} :</label>
			<div class="col-lg-3">
			<select name="UPELA_SELECTION" id="UPELA_SELECTION">
				{foreach $choices as $choice=>$name}
					<option value="{$choice}" {if $UPELA_SELECTION == $choice}selected='selected'{/if}>{$name}</option>
				{/foreach}
			</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_EXTRA_MARGE">{l s='MSG_UPELA_EXTRA_MARGE' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_EXTRA_MARGE}" name="UPELA_EXTRA_MARGE" id="UPELA_EXTRA_MARGE" min="0" max="100" /></div>
		</div>
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_UPELA_SHIPPING_ADDRESS' mod='upela'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_COUNTRY">{l s='MSG_UPELA_SHIP_COUNTRY' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_COUNTRY}" name="UPELA_SHIP_COUNTRY" id="UPELA_SHIP_COUNTRY" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_POSTAL_CODE">{l s='MSG_UPELA_SHIP_POSTAL_CODE' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_POSTAL_CODE}" name="UPELA_SHIP_POSTAL_CODE" id="UPELA_SHIP_POSTAL_CODE" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_CITY">{l s='MSG_UPELA_SHIP_CITY' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_CITY}" name="UPELA_SHIP_CITY" id="UPELA_SHIP_CITY" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_ADDRESS1">{l s='MSG_UPELA_SHIP_ADDRESS1' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_ADDRESS1}" name="UPELA_SHIP_ADDRESS1" id="UPELA_SHIP_ADDRESS1" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_ADDRESS2">{l s='MSG_UPELA_SHIP_ADDRESS2' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_ADDRESS2}" name="UPELA_SHIP_ADDRESS2" id="UPELA_SHIP_ADDRESS2" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_ADDRESS3">{l s='MSG_UPELA_SHIP_ADDRESS3' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_ADDRESS3}" name="UPELA_SHIP_ADDRESS3" id="UPELA_SHIP_ADDRESS3" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_NAME">{l s='MSG_UPELA_SHIP_NAME' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_NAME}" name="UPELA_SHIP_NAME" id="UPELA_SHIP_NAME" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_COMPANY">{l s='UPELA_SHIP_COMPANY' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_COMPANY}" name="UPELA_SHIP_COMPANY" id="UPELA_SHIP_COMPANY" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_PHONE">{l s='MSG_UPELA_SHIP_PHONE' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_PHONE}" name="UPELA_SHIP_PHONE" id="UPELA_SHIP_PHONE" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_EMAIL">{l s='UPELA_SHIP_EMAIL' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_SHIP_EMAIL}" name="UPELA_SHIP_EMAIL" id="UPELA_SHIP_EMAIL" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_SHIP_IS_PROFFESIONAL">{l s='MSG_UPELA_SHIP_IS_PROFFESIONAL' mod='upela'} :</label>
			<div class="col-lg-3">
			<select name="UPELA_SHIP_IS_PROFFESIONAL" id="UPELA_SHIP_IS_PROFFESIONAL">
				<option value="1" {if $UPELA_SHIP_IS_PROFFESIONAL}selected='selected'{/if}>{l s='MSG_YES' mod='upela'}</option>
				<option value="0" {if !$UPELA_SHIP_IS_PROFFESIONAL}selected='selected'{/if}>{l s='MSG_NO' mod='upela'}</option>
			</select>
			</div>
		</div>
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_UPELA_PICKUP' mod='upela'}</h3>
		<p><i>{l s='MSG_UPELA_LAST_PICKUP_DATE' mod='upela'} : </i></p>
		{if $pickup_demands}
		<table>
			{foreach $pickup_demands as $pickup_demand}
				<tr><td>{$pickup_demand.code_transporteur}</td><td>{$pickup_demand.date_send}</td></tr>
			{/foreach}		
		</table>
		{else}
		---
		{/if}
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_UPELA_PICKUP' mod='upela'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_AVERAGE_PACKAGES_NUMBER">{l s='MSG_UPELA_AVERAGE_PACKAGES_NUMBER' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_AVERAGE_PACKAGES_NUMBER}" name="UPELA_AVERAGE_PACKAGES_NUMBER" id="UPELA_AVERAGE_PACKAGES_NUMBER" /></div>
			<div class="upela-average-packages averages"></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_AVERAGE_PACKAGE_WEIGHT">{l s='MSG_UPELA_AVERAGE_PACKAGE_WEIGHT' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_AVERAGE_PACKAGE_WEIGHT}" name="UPELA_AVERAGE_PACKAGE_WEIGHT" id="UPELA_AVERAGE_PACKAGE_WEIGHT" /></div>
			<div class="upela-average-weight averages"></div>
			<script type="text/javascript">
				var averages = {$averages};
				{literal}
				$(function(){
					$.each(averages, function(i, value){
						var number_button = $('<button type="button" value="'+value.avg_packages+'">&laquo;&laquo; ' + value.name + ' : ' + value.avg_packages + '</button>');
						if (value.avg_packages > 0) {
							number_button.click(function(){$('#UPELA_AVERAGE_PACKAGES_NUMBER').val($(this).val());});
						} else {
							number_button.attr('disabled', true);
						} // if															
						number_button.appendTo('.upela-average-packages');
					}); // $.each	
				}); // $()
				{/literal}
			</script>
		</div> 
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_AVERAGE_DIMENSIONS">{l s='MSG_UPELA_AVERAGE_DIMENSIONS' mod='upela'} :</label>
			<div class="col-lg-6" style="padding-left:0">
				<div class="col-lg-3"><input type="text" style="width: 100%;" value="{$UPELA_AVERAGE_DIMENSION_X}" name="UPELA_AVERAGE_DIMENSION_X" id="UPELA_AVERAGE_DIMENSION_X" title="{l s='MSG_UPELA_AVERAGE_DIMENSION_X' mod='upela'}" /></div>
				<div class="col-lg-1" style="text-align:center"> x </div>
				<div class="col-lg-3"><input type="text" style="width: 100%;" value="{$UPELA_AVERAGE_DIMENSION_Y}" name="UPELA_AVERAGE_DIMENSION_Y" id="UPELA_AVERAGE_DIMENSION_Y" title="{l s='MSG_UPELA_AVERAGE_DIMENSION_Y' mod='upela'}"/></div>
				<div class="col-lg-1" style="text-align:center"> x </div>
				<div class="col-lg-3"><input type="text" style="width: 100%;" value="{$UPELA_AVERAGE_DIMENSION_Z}" name="UPELA_AVERAGE_DIMENSION_Z" id="UPELA_AVERAGE_DIMENSION_Z" title="{l s='MSG_UPELA_AVERAGE_DIMENSION_Z' mod='upela'}"/></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_PICKUP_READY_TIME">{l s='MSG_UPELA_PICKUP_READY_TIME' mod='upela'} <i>({l s='MSG_UPELA_PICKUP_TIME_FORMAT' mod='upela'})</i>:</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_PICKUP_READY_TIME}" name="UPELA_PICKUP_READY_TIME" id="UPELA_PICKUP_READY_TIME" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_PICKUP_CLOSE_TIME">{l s='MSG_UPELA_PICKUP_CLOSE_TIME' mod='upela'} <i>({l s='MSG_UPELA_PICKUP_TIME_FORMAT' mod='upela'})</i>:</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_PICKUP_CLOSE_TIME}" name="UPELA_PICKUP_CLOSE_TIME" id="UPELA_PICKUP_CLOSE_TIME" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_CONTENT">{l s='MSG_UPELA_CONTENT' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_CONTENT}" name="UPELA_CONTENT" id="UPELA_CONTENT" /></div>
		</div>
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_UPELA_SHIP' mod='upela'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3" for="UPELA_DELIVERY_DELAY">{l s='MSG_UPELA_DELIVERY_DELAY' mod='upela'} :</label>
			<div class="col-lg-3"><input type="text" value="{$UPELA_DELIVERY_DELAY}" name="UPELA_DELIVERY_DELAY" id="UPELA_DELIVERY_DELAY" min="1" max="31" /></div>
		</div>
	</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_SAVE' mod='upela'}</h3>
		<div class="panel-footer">
			<button class="btn btn-default pull-right" id="configuration_form_submit_btn" name="submitUpela" type="submit">
			<i class="process-icon-save"></i> <span>{l s='MSG_SAVE' mod='upela'}</span></button>
		</div>
</div>
	<div class="panel">
		<h3><i  class="upela-logo"></i>{l s='MSG_MODULE_INFORMATION' mod='upela'}</h3>
		<img class="kiwik-footer-logo" src="http://www.studio-kiwik.fr/images/logo-module.png?src=upela" />
		{l s='MSG_MODULE_NAME_AND_VERSION' mod='upela'} 1.1.1.
		{l s='MSG_EDITOR' mod='upela'} <a href="http://www.studio-kiwik.fr/?src=upela" class="kiwik-link">{l s='MSG_KIWIK' mod='upela'}</a>,
		<a href="http://www.prestashop.com/fr/agences-web-partenaires/or/kiwik" class="prestashop-link">{l s='MSG_EDITOR_CERTIFIED_PRESTASHOP' mod='upela'}</a>
		<p class="buildinfo">phing;upela;Atlas;2014-11-17 17:58:24;v1.6;bb2b1b91260ab31baf81a0a7322b3a55f64f55fe;1.1.1</p>
	</div>
</form>

</div>
