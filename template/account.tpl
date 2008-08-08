

				<h2>{vm_title_page}</h2>
				<p>{vm_account_text}</p>




				<h2>&nbsp;</h2>
				<p>
				<!-- BEGIN error -->
				<div class="error"><div class="error_container">{error.ERROR}</div></div>
				<!-- END error -->
				<!-- BEGIN valid -->
				<div class="valid"><div class="valid_container">{valid.VALID}</div></div>
				<!-- END valid -->
					<ul class="menu">
						<li><a href="?action=chg_pwd">{vm_chg_pwd}</a></li>
						<!-- BEGIN email -->
                        <li><a href="?action=chg_email">{email.vm_chg_email}</a></li>
                        <!-- END email -->
						<li><a href="?action=logged_out">{vm_logout_link}</a></li>
					</ul>
				</p>
