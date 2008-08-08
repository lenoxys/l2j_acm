<?php



$vm['_error_db_connect'] = 'Can\'t connect to the database server';
$vm['_error_db_select'] = 'Can\'t connect to the database';
$vm['_title'] = 'Helios - Account Management';
$vm['_title_page'] = 'Account Manager';
$vm['_exist_account'] = 'Existing Account';
$vm['_account'] = 'Account';
$vm['_password'] = 'Password';
$vm['_login_button'] = 'Log In';
$vm['_return'] = 'Return';
$vm['_new_account'] = 'New Account';
$vm['_new_account_text'] = 'In order to play on Lineage II private server you must create an account.';
$vm['_new_account_text2'] = 'You will need an account to play on Lineage II. If you already have an account, please SIGN IN now.

			Your account is non-transferable, and both your account name and password are private and should never be given out to anyone!

			<span style="color: red">Don\'t trust who say its GM/admin. WE  DON\'T NEED YOUR <u>ACCOUNT NAME</u> AND/OR <u>PASSWORD</u>! NEVER PROVIDE THEM!</span>

			All fields marked with a <img src="./template/images/required.gif" width="14" height="14" alt="" /> are required fields and need to be completed in order to fill out the signup form successfully.
';
$vm['_chg_pwd'] = 'Change your password';
$vm['_chg_pwd_text'] = '<span style="color: red">Don\'t trust who say its GM/admin. WE  DON\'T NEED YOUR <u>ACCOUNT NAME</u> AND/OR <u>PASSWORD</u>! NEVER PROVIDE THEM!</span>';
$vm['_chg_button'] = 'Change';
$vm['_password2'] = 'Confirm password';
$vm['_passwordold'] = 'Old password';
$vm['_email'] = 'Email';
$vm['_create_button'] = 'Create';
$vm['_forgot_password'] = 'Forgot your password ?';
$vm['_forgot_pwd'] = 'Forgot Password';
$vm['_forgot_pwd_text'] = 'Please verify the following information in order to update your password.

			If you do not remember what information you entered during registration, you will need to contact support.';
$vm['_forgot_button'] = 'Retreive';
$vm['_logout'] = 'You have logged out.';
$vm['_logout_link'] = 'Logout';
$vm['_wrong_auth'] = 'We were unable to verify your login. Either your login information was entered incorrectly, or the account system is currently unavailable.';
$vm['_no_id_no_pwd'] = 'Please enter an account name and password.';
$vm['_email_registered'] = 'This email is already registered in our database.';
$vm['_image_control'] = 'Image control isn\'t correct';
$vm['_pwd_difference'] = 'Please retape your password';
$vm['_pwd_incorrect'] = 'Please use another password';
$vm['_image_control_desc'] = 'To prevent automated registrations creation account requires you to enter a confirmation code.
If you can\'t read the image control, click on it for reload. ';
$vm['_account_created'] = 'Your account has been created.';
$vm['_account_actived'] = 'Your account has been actived.';
$vm['_REGWARN_UNAME1'] = 'Please enter an username.';
$vm['_REGWARN_UNAME2'] = 'Please enter a valid username.';
$vm['_REGWARN_MAIL'] = 'Please enter a valid e-mail address.';
$vm['_REGWARN_PASS'] = 'Please enter a valid password.  No spaces, more than 6 characters and contain 0-9,a-z,A-Z';
$vm['_REGWARN_VPASS1'] = 'Please verify the password.';
$vm['_REGWARN_VPASS2'] = 'Password and verification do not match, please try again.';
$vm['_REGWARN_INUSE'] = 'This username is already in use. Please try another.';
$vm['_REGWARN_EMAIL_INUSE'] = 'This e-mail is already registered. If you forgot the password click on "Lost your Password" and a new password will be sent to you.';
$vm['_WARN_NOT_LOGGED'] = 'You aren\'t logged';
$vm['_REGWARN_LIMIT_CREATING'] = 'Quota of created account is full. Come back later';

$vm['_password_request'] = 'Password request send.';
$vm['_activation_control'] = 'Activation key isn\'t correct';

$vm['_password_reseted'] = 'Password Reseted';
$vm['_control'] = 'Control key isn\'t correct';

$vm['_email_title_verif']			= '[SERVER] Address Verification';
$vm['_email_message_verif']			= "Your email verification code is : [CODE].<br><br>You can completed registration by cliking <a href=\"[URL]\">here</a>.<br><br>If you have any questions about the registration process, please contact [EMAIL_SUPPORT].";
$vm['_email_title_ok']				= 'Welcome To [SERVER]!';
$vm['_email_message_ok']			= "Thank you for registering with [SERVER]!<br><br>Your [SERVER] account name is: [ID]<br><br>Lastly, if you have a question and just can't find the answer, our customer support team is always available as near as your email box.<br><br>Once again, thank you for joining the world of [SERVER]. We look forward to seeing you in-game!<br><br>The [SERVER] Team";
$vm['_email_title_change_pwd']		= 'Password Reset Request';
$vm['_email_message_change_pwd']	= "Someone at [IP] want reset your Lineage II Game Account password for account [ID].<br>Click <a href=\"[URL]\">here</a> for reset your passord.<br>If you did not make this change, please don't care this mail'.<br><br>The [SERVER] Team";
$vm['_email_title_change_pwd_ok']	= 'Password Reset Success';
$vm['_email_message_change_pwd_ok']	= "Someone at [IP] has reset your Lineage II Game Account password for account [ID].<br> New password : [CODE]<br> If you did not make this change, please contact support immediately at [EMAIL_SUPPORT].";


$vm['_creating_acc_prob'] = 'Database problem : Account was not created. Please report this to the Staff.';

$vm['_chg_email']			= 'Change your email';
$vm['_email2']				= 'Confirm email';
$vm['_change_pwd_valid']	= 'Your password has been changed.';
$vm['_change_email_valid']	= 'Your email has been changed.';
$vm['_REGWARN_VEMAIL1']		= 'Email and verification do not match, please try again.';

$vm['_TERMS_AND_CONDITION']	= "<h2>Terms and conditions</h2><br /><br />1. GameMaster is always right.<br />2. If GameMaster is wrong refer to the first rule.";
$vm['_accept_button'] = 'Accept';

$vm = array_map('nl2br', $vm);

?>
