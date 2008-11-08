{include file="header.tpl"}

				<h2>{$vm.title_page}</h2>
				<p>{$vm.account_text}</p>

				<h2>&nbsp;</h2>
				<p>
{if isset($error)}
					<div class="error"><div class="error_container">{$error}</div></div>
{/if}
{if isset($valid)}
					<div class="valid"><div class="valid_container">{$valid}</div></div>
{/if}
					<ul class="menu">
{dynamic}{section name=i loop=$modules}
						<li><a href="{$modules[i].link}">{$modules[i].name}</a></li>
{/section}{/dynamic}
					</ul>
				</p>

{include file="footer.tpl"}