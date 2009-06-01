{include file="header.tpl"}

			{$vm.terms_and_condition}<br /><br />
			<form name="create" method="POST" action="./{$session_id}">
				<input type="hidden" name="action" value="show_create">
				<input type="hidden" name="ack" value="ack">
				<hr class="clear">
				<input type="button" onClick="document.location='./{$session_id}'" class="button" value="{$vm.return}" />
				<input class="button" type="submit" value="{$vm.accept_button}">
			</form>

{include file="footer.tpl"}