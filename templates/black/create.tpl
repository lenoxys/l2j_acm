{include file="header.tpl"}

			<div>
				<h2>{$vm.new_account}</h2>
				<p>
				{$vm.new_account_text}
				</p>
{if isset($image)}
				<p>{$vm.image_control_desc}</p>
{/if}
			</div>

			<form name="create" method="POST" action="./">
				<h1>&nbsp;</h1>
{if isset($error)}
				<div class="error"><div class="error_container">{$error}</div></div>
{/if}
				<div class="field"><label>{$vm.account}</label><span class="field"><input type="text" id="Luser" name="Luser" value="{$vm.post_id}" autocomplete="off" /></span><em>&#8226;</em></div>
				<div class="field"><label>{$vm.password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" /></span><em>&#8226;</em></div>
				<div class="field"><label>{$vm.password2}</label><span class="field"><input type="password" id="Lpwd2" name="Lpwd2" autocomplete="off" /></span><em>&#8226;</em></div>
				<div class="field"><label>{$vm.email}</label><span class="field"><input type="text" id="Lemail" name="Lemail" value="{$vm.post_email}" autocomplete="off"></span><em>&#8226;</em></div>
{if isset($image)}
				<div class="field"><label><img src="./img.php" id="L_image" onclick="reloadImage(this);"></label><span class="field"><input type="text" id="Limage" name="Limage" autocomplete="off"></span><em>&#8226;</em></div>
{/if}
				<input type="hidden" name="action" value="registration">
				<hr class="clear">
				<input type="button" onClick="document.location='./'" class="button" value="{$vm.return}" />
				<input class="button" type="submit" value="{$vm.create_button}">
			</form>

{include file="footer.tpl"}