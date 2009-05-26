{include file="header.tpl"}

				<h2>{$vm.select_item}</h2>
                <p>{$vm.select_desc}</p>
				<p>
{if isset($error)}
					<div class="error"><div class="error_container">{$error}</div></div>
{/if}
{if isset($valid)}
					<div class="valid"><div class="valid_container">{$valid}</div></div>
{/if}
					<ul class="menu">
{dynamic}{section name=i loop=$items}
						<li><a href="{$items[i].link}">{$items[i].name}</a></li>
{/section}{/dynamic}
					</ul>
                    <hr class="clear">
                    <input type="button" onClick="document.location='./'" class="button" value="{$vm.return}" />
				</p>

{include file="footer.tpl"}