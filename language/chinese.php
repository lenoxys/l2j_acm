<?php

// made by me :D

$vm['_error_db_connect']				= '無法連接到資料庫伺服器';
$vm['_error_db_select']					= '無法連接到資料庫';
$vm['_title']							= '天堂2 - 遊戲帳號管理';
$vm['_title_page']						= '遊戲帳號管理';
$vm['_exist_account']					= '舊有帳號';
$vm['_account']							= '帳號';
$vm['_password']						= '密碼';
$vm['_login_button']					= '登入';
$vm['_return']							= '返回';
$vm['_new_account']						= '新帳號';
$vm['_new_account_text']				= '若要玩天堂II模擬器的話，您必須創建一個帳戶。';
$vm['_new_account_text2']				= '要玩天堂II模擬器的話你就需要有個帳號。假如你已有帳號了，請現在按 返回 並登入。

			你的帳號戶是不可轉讓的，且您的帳號和密碼是私密的！記得不要將他們給任何人！

			<span style="color: red">在任何情況下，GM絕不會跟你要帳號或密碼等資料。</span>

			所有有用 <em class="me"></em> 標示的欄位都是必填欄位，需完整正確的填入資料才能完成帳號的建立。
';
$vm['_chg_pwd']							= '變更你的密碼';
$vm['_chg_pwd_text']					= '<span style="color: red">在任何情況下，GM絕不會跟你要帳號或密碼等資料。</span>';
$vm['_chg_button']						= '變更';
$vm['_password2']						= '確認密碼';
$vm['_passwordold']						= '舊有使用的密碼';
$vm['_email']							= '電子信箱';
$vm['_create_button']					= '創新帳號';
$vm['_forgot_password']					= '忘了你的密碼 ?';
$vm['_forgot_pwd']						= '忘了密碼';
$vm['_forgot_pwd_text']					= '請在下面欄位正確填入你的資料就能重設你的密碼。

			假如你忘了你當初註冊時所填入的資料，你就需要尋求協助。';
$vm['_forgot_button']					= '重新獲得';
$vm['_logout']							= '你已登出。';
$vm['_logout_link']						= '登出';
$vm['_wrong_auth']						= '我們無法驗證您的登錄。可能是你的登錄資料輸入有誤，或帳號系統目前無法使用。';
$vm['_no_id_no_pwd']					= '請輸入一組帳號名稱跟密碼。';
$vm['_email_registered']				= '這個電子信箱已經在我們的資料庫註冊過了。';
$vm['_image_control']					= '圖片驗證碼不正確';
$vm['_pwd_difference']					= '請重新輸入你的密碼';
$vm['_pwd_incorrect']					= '請用別的密碼';
$vm['_image_control_desc']				= '為了防止自動註冊，創建帳號需要您輸入驗證碼。如果您看不清楚圖片內的驗證碼，可以在圖上面點一下重載入一張圖。';
$vm['_account_created_act']				= 'Your account has been created.';
$vm['_account_created_noact']			= '你已成功創立帳號，但在登入前，你必須去電子信箱收信並啟用您的帳號。';
$vm['_account_actived']					= '你的帳號已經啟用了。';
$vm['_REGWARN_UNAME1']					= '請輸入一個使用者名稱。';
$vm['_REGWARN_UNAME2']					= '請輸入一個有效的使用者名稱。';
$vm['_REGWARN_MAIL']					= '請輸入一個有效的電子信箱地址。';
$vm['_REGWARN_PASS']					= '請輸入一個有效的密碼。不得有空格，至少要有六個字且只能用0-9,a-z,A-Z這些字元';
$vm['_REGWARN_VPASS1']					= '請檢查密碼。';
$vm['_REGWARN_VPASS2']					= '密碼跟確認密碼不一致，請重新輸入。';
$vm['_REGWARN_INUSE']					= '這個帳號已被使用。請輸入不一樣的。';
$vm['_REGWARN_EMAIL_INUSE']				= '這個電子信箱已被註冊過了。假如你忘了你的密碼，請按 "忘了你的密碼？" 然後系統會傳送一組新密碼給你。';
$vm['_WARN_NOT_LOGGED']					= '你尚未登入';
$vm['_REGWARN_LIMIT_CREATING']			= '創新帳號的配額已滿。晚一點再來吧';

$vm['_password_request']				= '密碼重置郵件已送出。';
$vm['_activation_control']				= '啟動的鑰匙不正確';

$vm['_password_reseted']				= '密碼已重設定，請至你的信箱收取新的密碼';
$vm['_control']							= '控制鑰匙不正確';

$vm['_email_title_verif']				= '[SERVER] 遊戲帳號確認信件';
$vm['_email_message_verif']				= "你的電子郵件驗證碼是：[CODE]。<br><br>你只要按下<a href=\"[URL]\">這裡</a>就能完成你的註冊。<br><br>假如你對註冊流程有任何疑問，請用 [EMAIL_SUPPORT] 聯絡我們。";
$vm['_email_title_ok']					= '歡迎來到 [SERVER]!';
$vm['_email_message_ok']				= "你已經在 [SERVER] 完成註冊！<br><br>你的 [SERVER] 遊戲帳號是：[ID]<br><br>最後，假如你在遊戲中有任何問題而找不到人解答，我們的客服團隊服務信箱隨時接受您的電子郵件詢問。<br><br>再一次感謝您進入 [SERVER] 的世界。我們期待你參與我們的遊戲世界！<br><br> [SERVER] 管理團隊";
$vm['_email_title_change_pwd']			= 'Password Reset Request';
$vm['_email_message_change_pwd']		= "有人(IP為：[IP])藉由你的天堂II遊戲帳號[ID]進行密碼重設動作。<br>若你想重設你的帳號請按下<a href=\"[URL]\">這裡</a>。<br>假如你不想重設密碼，請不要理會這封信件。<br><br>[SERVER] 管理團隊";
$vm['_email_title_change_pwd_ok']		= '密碼重設完成';
$vm['_email_message_change_pwd_ok']		= "有人(IP為：[IP])藉由你的天堂II遊戲帳號[ID]進行密碼重設動作。<br>新的密碼是：[CODE]<br>假如不是你要求作這項變更的，請立即跟我們聯絡！我們的信箱是 [EMAIL_SUPPORT]";

$vm['_email_title_change_email_ok']		= '你的電子信箱已更改完成';
$vm['_email_message_change_email_ok']	= "有人(IP為：[IP])藉由你的天堂II遊戲帳號[ID]進行電子信箱更改動作。<br>新的信箱是 [CODE]<br>假如你未曾要求作這項變更，請立即跟我們聯絡！我們的信箱是 [EMAIL_SUPPORT]";

$vm['_creating_acc_prob']				= '資料庫問題：無法建立帳號。請向管理人員回報。';

$vm['_chg_email']						= '變更你的電子信箱';
$vm['_chg_email_text']					= '<span style="color: red">在任何情況下，GM絕不會跟你要帳號或密碼等資料。</span>';
$vm['_email2']							= '確認電子信箱';
$vm['_change_pwd_valid']				= '你的密碼已更改。';
$vm['_change_email_valid']				= '你的電子信箱已更改。';
$vm['_REGWARN_VEMAIL1']					= '電子信箱帳號兩次輸入不吻合，請再試一次';

$vm['_TERMS_AND_CONDITION']				= "<h2>約定條款</h2><br /><br />1. GM永遠都是對的。<br />2. 假如GameMaster有錯，請看第一條規則。";
$vm['_accept_button']					= '接受';



/*
-----------------------------------------------------------------
---------------------- Account Services -------------------------
-----------------------------------------------------------------
*/

$vm['_accounts_services']				= '帳號伺服器';
$vm['_select_worlds']					= '選擇你的 天堂2 世界';
$vm['_select_character']				= '選擇你的人物角色';
$vm['_any_character']					= '找出任何遊戲角色';

$vm['_character_fix']					= '修改你的遊戲角色';
$vm['_character_fix_desc']				= '修改你的遊戲角色';
$vm['_character_fix_confirm']			= '"%s" 在 "%s"伺服器 將被修改';
$vm['_character_fix_yes']				= '你的遊戲角色已修改完成。';
$vm['_character_fix_no']				= '你的遊戲角色無法修改。';

$vm['_character_unstuck']				= '移動你的人物角色到附近的城鎮(奇岩城鎮廣場)。該角色請先登出遊戲';
$vm['_character_unstuck_desc']			= '當你的人物角色被卡住無法動彈時，請試著打 /脫離 指令。If it is determined that the character really is confined in the map,該角色就會快速移動到附近的地方。If it is determined otherwise,那五分鐘脫逃功能將會啟動，而五分鐘後角色會移到附近的城鎮。

若你的遊戲角色在遊戲中無法使用脫逃指令，選擇要移動到附近城鎮的角色。';
$vm['_character_unstuck_confirm']		= '"%s"(在"%s"伺服器的)將被移動';
$vm['_character_unstuck_yes']			= '你的遊戲角色已被移到附近的城鎮。';
$vm['_character_unstuck_no']			= '你的遊戲角色無法移到附近的城鎮。';

$vm['_allow_time']						= '你必須再等 %s小時，在於上次的%s動作過後。';

$vm['_acc_serv_off']					= '帳號伺服器目前為離線狀態。';
$vm['_acc_serv_offline']				= '只有離線的角色才能使用帳號伺服器的變更動作。';
$vm['_acc_serv_ban']					= '被踢除的使用者無法使用帳號伺服器。';

$vm['_error_select_char']				= '目前我們無法讓你的遊戲角色使用帳號伺服器。';

$vm['_character_sex']					= '遊戲角色的性別變更';
$vm['_character_sex_desc']				= "選你想要變更性別的遊戲角色。

你只能選擇一個遊戲角色。下面列出了所有伺服器裡符合變更的遊戲角色。闇天使角色無法進行性別的變更。

角色在性別變更後，髮型、髮色跟臉型將會設定成'A-類型'。

<i>Please not that your selection is applied during a maintenance windows. It may take up ten business days before the change appears in game.</i>";
$vm['_character_sex_confirm']			= '你選擇將角色"%s"(在"%s"伺服器上)，變更其性別由%s變成%s。';
$vm['_character_sex_yes']				= '遊戲角色的性別已更改完成。';
$vm['_character_sex_no']				= '遊戲角色的性別無法進行更改。';
$vm['_character_sex_0']					= '男';
$vm['_character_sex_1']					= '女';
$vm['_acc_serv_gender_kamael']			= '闇天使角色無法使用性別更改伺服器。';
$vm['_acc_serv_gender_time']			= '你一個禮拜內最多只能更換角色的性別一次。';

$vm['_character_name']					= 'Rename Character';
$vm['_character_name_desc']				= "選擇你要更改名稱的角色。

In order to change character's name, you must create a new level 1 character in game with the new name you desire. This new character must remain at level 1 in order for the renaming to take place.

Please be aware that this level 1 character and all ites associated items will be deleted after the name change is executed.

<i>Please not that your selection is applied during a maintenance windows. It may take up ten business days before the change appears in game.</i>";
$vm['_character_name_confirm']			= 'Change Name';
$vm['_character_name_yes']				= 'Change Name';
$vm['_character_name_no']				= 'Change Name';

$vm['_character_world_t']				= 'World';
$vm['_character_name_t']				= 'Name';
$vm['_character_gender_t']				= 'Gender';


$vm['_confirm']							= '確認';
$vm['_back']							= '退回上一步';

$vm['_REGWARN_UNAME3']					= 'Please choose an username different of your password.';

$vm['_cookie_prob']						= 'You will need to activate the cookies in your web browser before log in or create a new account.';

$vm['_allow_with_karma']				= 'Account services are avaible only for non-karmed player.';

?>