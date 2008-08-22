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
						<li><a href="?action=chg_pwd">{$vm.chg_pwd}</a></li>
{if isset($email)}
                        <li><a href="?action=chg_email">{$email}</a></li>
{/if}
						<li><a href="?action=logged_out">{$vm.logout_link}</a></li>
					</ul>
				</p>

{include file="footer.tpl"}