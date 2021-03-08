{*
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

 <div class="panel">
	<h3><i class="icon-envelope"></i> {l s='Relaunch Message' mod='ordersconfig'}</h3>
    {if $log}
        <div class="alert alert-info">{l s='An email has been sent to client ' mod='ordersconfig'} {l s='du client ' mod='ordersconfig'}<a href="{Context::getContext()->link->getAdminLink('AdminCustomers', true, [], ['id_customer' => $customer_details->id,'viewcustomer' => 1])}">
{$customer_details->lastname}  {$customer_details->firstname}
</a></div>
        <div class="order_email_details">
        <p>{l s='ORDER ID :' mod='ordersconfig'} <span class="badge badge-success">{$log["id_order"]}</span></p>
        <p>{l s='E-MAIL ADRESS  :' mod='ordersconfig'} <span class="primary">{$customer_details->email}</span></p>
        <p>{l s='E-MAIL SEND DATE :' mod='ordersconfig'} <span class="primary">{$log["date_send"]}</span></p>
        <p>{l s='MESSAGE : ' mod='ordersconfig'} {$log["message"]}</p>
    
    </div>
    {else}
     <p>{l s='No data currently for this order ' mod='ordersconfig'} {$log["message"]}</p>
    {/if}
</div>

