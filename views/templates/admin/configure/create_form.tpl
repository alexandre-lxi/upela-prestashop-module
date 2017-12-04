{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author    UPELA
* @copyright 2017-2018 MPG Upela
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<script>
    $(function () {
        $("#company_country").change(function () {
            if ($(this).val() === "FR") {
                $("#company_siret").removeAttr('disabled');
            } else {
                $("#company_siret").attr('disabled', 'disabled');
            }

        });
    });
</script>

{if isset($postSuccess)}
    {foreach from=$postSuccess item=ps}
        <div class="alert alert-success">{$ps|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}

{if isset($postErrors)}
    {foreach from=$postErrors item=pe}
        <div class="alert alert-danger">{$pe|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}

{$createform}
