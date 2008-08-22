<?php



$vm['_error_db_connect'] = 'Nelze se připojit k databázovému serveru';
$vm['_error_db_select'] = 'Nelze se připojit k databázi';
$vm['_title'] = 'Helios - Account Management';
$vm['_title_page'] = 'Account Manager';
$vm['_exist_account'] = 'Přihlášení';
$vm['_account'] = 'Účet';
$vm['_password'] = 'Heslo';
$vm['_login_button'] = 'Přihlásit se';
$vm['_return'] = 'Zpět';
$vm['_new_account'] = 'Registrace';
$vm['_new_account_text'] = 'Pro hraní na Lineage II serveru Otherside si musíte vytvořit účet.';
$vm['_new_account_text2'] = 'Pro hraní na Lineage II serveru Otherside potřebujete účet. Pokud už účet máte, běžte zpět a přihlaste se.

			Váš účet je nepřenositelný, Vaše uživatelské jméno a heslo jsou soukromou informací a neměli byste je nikomu sdělovat!

			<span style="color: red">Nevěřte lidem, kteří po Vás chtějí heslo k Vašemu účtu a tvrdí, že jsou Game Master či Admin. Vaše heslo nepotřebujeme znát z žádného důvodu, proto jej nikdy nikomu nesdělujte!</span>

			Všechny položky formuláře jsou povinné a pro založení účtu je třeba je vyplnit.
';
$vm['_chg_pwd'] = 'Změnit heslo';
$vm['_chg_pwd_text'] = '<span style="color: red">Nevěřte lidem, kteří po Vás chtejí heslo k Vašemu účtu a tvrdí, že jsou Game Master či Admin. Vaše heslo nepotřebujeme znát z žádného důvodu, proto jej nikdy nikomu nesdělujte!</span>';
$vm['_chg_button'] = 'Změnit';
$vm['_password2'] = 'Potvrzení hesla';
$vm['_passwordold'] = 'Staré heslo';
$vm['_email'] = 'Email';
$vm['_create_button'] = 'Vytvořit';
$vm['_forgot_password'] = 'Zapoměli jste své heslo ?';
$vm['_forgot_pwd'] = 'Zapomenuté heslo';
$vm['_forgot_pwd_text'] = 'Zadejte následující informace do formuláře pro změnu Vašeho hesla.

			Pokud si své údaje nepamatujete, kontaktujte adminy webu pomocí fóra Otherside, či Game Mastera během hry.';
$vm['_forgot_button'] = 'Odeslat';
$vm['_logout'] = 'Byli jste odhlášeni.';
$vm['_logout_link'] = 'Odhlásit';
$vm['_wrong_auth'] = 'Nepodařilo se ověřit Váš účet. Buď je zadané uživatelské jméno či heslo špatně, nebo je systém správy herních účtů nedostupný.';
$vm['_no_id_no_pwd'] = 'Pro přihlášení zadejte jméno Vašeho účtu a heslo.';
$vm['_email_registered'] = 'Tento e-mail je již v databázi herních účtů registrován.';
$vm['_image_control'] = 'Ověření obrázku neproběhlo v pořádku';
$vm['_pwd_difference'] = 'Zadejte své heslo znovu';
$vm['_pwd_incorrect'] = 'Použijte jiné heslo';
$vm['_image_control_desc'] = 'Z důvodu prevence proti automatizované tvorbě účtů musíte opsat potvrzovací kód.
Pokud se Vám nedaří obrázek přečíst, klikem na něj se načte jiný. ';
$vm['_account_created'] = 'Váš účet byl vytvořen. Zkontrolujte svůj mailbox a účet aktivujte.';
$vm['_account_actived'] = 'Váš účet byl aktivován.';
$vm['_REGWARN_UNAME1'] = 'Zadejte uživatelské jméno.';
$vm['_REGWARN_UNAME2'] = 'Zadejte platné uživatelské jméno.';
$vm['_REGWARN_MAIL'] = 'Zadejte funkční e-mail.';
$vm['_REGWARN_PASS'] = 'Zadejte vhodné heslo. Bez mezer, více než 6 znaků obsahujících 0-9,a-z,A-Z';
$vm['_REGWARN_VPASS1'] = 'Ověřte své heslo.';
$vm['_REGWARN_VPASS2'] = 'Heslo a jeho ověření nesouhlasí, zkuste to znovu.';
$vm['_REGWARN_INUSE'] = 'Toto uživatelské jméno již v databázi je. Zvolte prosím jiné.';
$vm['_REGWARN_EMAIL_INUSE'] = 'Tento e-mail je již registrován. Pokud jste zapoměli své heslo, klikněte na "Zapoměli jste své heslo?" a nové heslo Vám bude zasláno.';
$vm['_WARN_NOT_LOGGED'] = 'Nejste přihlášen.';
$vm['_REGWARN_LIMIT_CREATING'] = 'Omezení počtu vytvořených účtů je vyčerpáno, zkuste to pozdeji.';

$vm['_password_request'] = 'Požadavek na potvrzení žádosti byl odeslán.';
$vm['_activation_control'] = 'Aktivační klíč není správný.';

$vm['_password_reseted'] = 'Heslo bylo změněno.';
$vm['_control'] = 'Kontrolní klíč není správný.';

$vm['_email_title_verif']			= '[SERVER] Ověření adresy';
$vm['_email_message_verif']			= "Váš aktivační kód je : [CODE].<br><br>Dokončit registraci můžete kliknutím na <a href=\"[URL]\">tento</a> odkaz.<br><br>Pokud máte nějaké dotazy k registračnímu procesu, kontaktujte [EMAIL_SUPPORT].";
$vm['_email_title_ok']				= 'Vítejte na [SERVER]!';
$vm['_email_message_ok']			= "Váš učet je: [ID]<br><br>Děkujeme za připojení do světa [SERVER].<br><br>[SERVER] team";
$vm['_email_title_change_pwd']		= 'Požadavek na změnu hesla';
$vm['_email_message_change_pwd']	= "Někdo s IP [IP] chce změnit heslo Vašeho učtu [ID] na Lineage II serveru [SERVER].<br>Pokud jste to Vy, klikněte <a href=\"[URL]\">zde</a> a Vaše heslo bude změněno.<br>Pokud změnu nepožadujete, tento e-mail prostě smažte.<br><br>[SERVER] team";
$vm['_email_title_change_pwd_ok']	= 'Změna hesla proběhla úspěšně';
$vm['_email_message_change_pwd_ok']	= "Heslo Vašeho učtu [ID] na Lineage II serveru [SERVER] bylo úspěšně změněno.<br> Nové heslo : [CODE]<br> Tato změna byla provedena z IP [IP], pokud to nejste Vy, neprodleně kontaktujte [EMAIL_SUPPORT].";


$vm['_creating_acc_prob'] = 'Database problem : Account was not created. Please report this to the Staff.';

$vm['_chg_email']			= 'Change your email';
$vm['_chg_email_text']		= '<span style="color: red">Nevěřte lidem, kteří po Vás chtejí heslo k Vašemu účtu a tvrdí, že jsou Game Master či Admin. Vaše heslo nepotřebujeme znát z žádného důvodu, proto jej nikdy nikomu nesdělujte!</span>';
$vm['_email2']				= 'Confirm email';
$vm['_change_pwd_valid']	= 'Your password has been changed.';
$vm['_change_email_valid']	= 'Your email has been changed.';
$vm['_REGWARN_VEMAIL1']		= 'Email and verification do not match, please try again.';

$vm['_TERMS_AND_CONDITION']	= "<h2>Terms and conditions</h2><br /><br />1. GameMaster is always right.<br />2. If GameMaster is wrong refer to the first rule.";
$vm['_accept_button'] = 'Accept';

$vm = array_map('nl2br', $vm);

?>
