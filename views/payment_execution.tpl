<p class="warning">
x sadfasdfsdf
</p>

{capture name=path}
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}"
       title="{l s='Go back to the Checkout' mod='xcash'}">{l s='Checkout' mod='xcash'}</a>
    <span class="navigation-pipe">{$navigationPipe}</span>{l s='xcash payment' mod='xcash'}
{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='xcash'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
    <p class="warning">{l s='Your shopping cart is empty.' mod='cheque'}</p>
{else}
    <h3>{l s='xcash Payment' mod='xcash'}</h3>
    <p>
        <img src="{$this_path_xcash}logo.png" alt="{l s='xcash' mod='xcash'}" width="100"
             style="float:left; margin: 0px 10px 5px 0px;"/>
        {l s='You have chosen to pay by xcash.' mod='xcash'}
        <br/><br/>
        {l s='Here is a short summary of your order:' mod='xcash'}
    </p>
    <p style="margin-top:20px;">
        - {l s='The total amount of your order comes to:' mod='xcash'}
        <span id="amount" class="price">{displayPrice price=$total}</span>
        {if $use_taxes == 1}
            {l s='(tax incl.)' mod='xcash'}
        {/if}
    </p>
    <p>
        <b>{l s='Please confirm your order by clicking \'I confirm my order\'.' mod='xcash'}</b>
    </p>
    <p class="cart_navigation" id="cart_navigation">
        <a href="{$gateway_url|escape:'html'}" class="button_large">{l s='I confirm my order' mod='xcash'}</a>
        <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}"
           class="button_large">{l s='Other payment methods' mod='xcash'}</a>
    </p>
{/if}
