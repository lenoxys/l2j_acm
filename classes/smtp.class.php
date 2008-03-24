<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

/*******************************************************************************
*
* Nom de la source :
*       Class SMTP
* Nom du fichier par défaut :
*       Class.SMTP.php
* Auteur :
*       Nuel Guillaume alias Immortal-PC
* Site Web :
*       http://immortal-pc.info/
*
*******************************************************************************/

class SMTP {

    // Nom du domaine ou nom du serveur
    var $NomDuDomaine = '';

    // De Qui
    var $From = 'root@localhost';// Adresse de l' expéditeur
    var $FromName = 'Root';// Nom de l' expéditeur
    var $ReplyTo = 'root@localhost';// Adresse de retour

    // A Qui
    var $To = '';
    // Utilisation : $Bcc = 'mail1,mail2,....';
    var $Bcc = '';// Blind Carbon Copy, c'est à dire que les adresses qui sont contenue ici seront invisibles pour tout le monde
    var $Cc = '';

    // Priorité
    var $Priority = 3;// Priorité accordée au mail (valeur allant de 1 pour Urgent à 3 pour normal et 6 pour bas)

    // Encodage
    var $ContentType = 'html';//Contenu du mail (texte, html...) (txt , html, txt/html)
    var $Encoding = '8bit'; // Ancienne valeur quoted-printable
    var $ISO = 'iso-8859-15';
    var $MIME = '1.0';// La version mime

    // Confirmation de reception
    var $Confimation_reception = '';// Entrez l' adresse où sera renvoyé la confirmation

    // Le mail
    var $Sujet = '';
    var $Body = '';
    var $Body_txt = '';

    // Fichier(s) joint(s)
    var $File_joint = array();

    // Nombre tour
    var $Tour = 0;


    //**************************************************************************
    // Paramètre de connection SMTP
    //**************************************************************************
    var $Authentification_smtp = false;

    var $serveur = '';// Serveur SMTP
    var $port = 25;// Port SMTP
    var $login_smtp = '';// Login pour le serveur SMTP
    var $mdp_smtp = '';// Mot de passe pour le serveur SMTP
    var $time_out = 10;// Durée de la connection avec le serveur SMTP


    //**************************************************************************
    // Variables temporaires
    //**************************************************************************
    var $smtp_connection = '';// Variable de connection
    var $erreur = '';
    var $debug = false;

//------------------------------------------------------------------------------

    //**************************************************************************
    // Fonction de déclaration de connection SMTP
    //**************************************************************************
    function SMTP($serveur='', $user='', $pass='', $port=25, $NomDuDomaine='', $debug=false){
        if($serveur){
            $this->serveur = $serveur;
        }
        if($user){
            $this->Authentification_smtp = true;
            $this->login_smtp = $user;
            $this->mdp_smtp = $pass;
        }
        $this->port = $port;
        if($NomDuDomaine){
            $this->NomDuDomaine = $NomDuDomaine;
        }
        $this->debug = DEBUG;
    }


    //**************************************************************************
    // Fonction de connection SMTP
    //**************************************************************************
    function Connect_SMTP(){
        // Connection au serveur SMTP
        $this->smtp_connection = fsockopen($this->serveur, // Serveur
                                     $this->port,          // Port de connection
                                     $num_erreur,    // Numéros de l' erreur
                                     $msg_erreur,    // Message d' erreur
                                     $this->time_out);     // Durée de la connection en secs
        if(!$this->smtp_connection){// Vérification de la connection
            $this->erreur = 'Impossible de se connecter au serveur SMTP !!!<br />'."\r\n"
            .'Numéro de l&#39; erreur: '.$num_erreur.'<br />'."\r\n"
            .'Message renvoyé: '.$msg_erreur.'<br />'."\r\n";
            return false;
        }

        // Suppression du message d' accueil
        $reponce = $this->get_smtp_data();
        // Debug
        if($this->debug){
            echo '<div style="color:#993300;">Connection</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
        }

        // On règle le timeout du serveur SMTP car parfois, le serveur SMTP peut être un peut lent à répondre
        // Windows ne comprend pas la fonction socket_set_timeout donc on vérifi que l' on travail sous Linux
        if(substr(PHP_OS, 0, 3) !== 'WIN'){
           socket_set_timeout($this->smtp_connection, $this->time_out, 0);
        }

        //**********************************************************************
        // Commande EHLO et HELO
        if($this->NomDuDomaine === ''){// On vérifi si le nom de domaine à été renseigné
            if($_SERVER['SERVER_NAME'] !== ''){
                $this->NomDuDomaine = $_SERVER['SERVER_NAME'];
            }else{
                $this->NomDuDomaine = 'localhost.localdomain';
            }
        }

        if(!$this->Commande('EHLO '.$this->NomDuDomaine, 250)){// Commande EHLO
            // Deusième commande EHLO -> HELO
            if(!$this->Commande('HELO '.$this->NomDuDomaine, 250, 'Le serveur refuse l&#39; authentification (EHLO et HELO) !!!')){// Commande HELO
                return false;
            }
        }


        if($this->Authentification_smtp){// On vérifi si l' on a besoin de s' authentifier
            //******************************************************************
            // Authentification
            //******************************************************************
            if(!$this->Commande('AUTH LOGIN', 334, 'Le serveur refuse l&#39; authentification (AUTH LOGIN) !!!')){
                return false;
            }


            //******************************************************************
            // Authentification : Login
            //******************************************************************
            $tmp = $this->Commande(base64_encode($this->login_smtp), 334, 'Login ( Nom d&#39; utilisateur ) incorrect !!!', 0);
            if(!$tmp['no_error']){
                return false;
            }
            // Debug
            if($this->debug){
                echo '<div style="color:#993300;">Envoie du login.</div>',"\r\n",str_replace("\r\n", '<br />', $tmp['msg']);
            }


            //******************************************************************
            // Authentification : Mot de passe
            //******************************************************************
            $tmp = $this->Commande(base64_encode($this->mdp_smtp), 235, 'Mot de passe incorrect !!!', 0);
            if(!$tmp['no_error']){
                return false;
            }
            // Debug
            if($this->debug){
                echo '<div style="color:#993300;">Envoie du mot de passe.</div>',"\r\n",str_replace("\r\n", '<br />', $tmp['msg']);
            }

        }

        //**********************************************************************
        // Connecté au serveur SMTP
        //**********************************************************************
        return true;
    }


    //**************************************************************************
    // Foncton de set
    //**************************************************************************
    function set_from($name, $email=''){
        $this->FromName = $name;
        if(!empty($email)){
            $this->From = $email;
        }
        unset($name, $email);
    }


    //**************************************************************************
    // Foncton d' ajout de pièce jointe
    //**************************************************************************
    function add_file($url_file){
    	if(!$url_file){
			$this->erreur = 'Champs manquant !!!<br />'."\r\n";
			return false;
		}
		if(!($fp = @fopen($url_file, 'a'))){
			$this->erreur = 'Fichier introuvable !!!<br />'."\r\n";
			return false;
		}
		fclose($fp);

		$file_name = explode('/', $url_file);
		$file_name = $file_name[count($file_name)-1];
		$mime = parse_ini_file('./mime.ini');
		$ext = explode('.', $file_name);
		$ext = $ext[count($ext)-1];

		if(IsSet($this->File_joint[$file_name])){
			$file_name = explode('_', str_replace('.'.$ext, '', $file_name));
			if(is_numeric($file_name[count($file_name)-1])){
				$file_name[count($file_name)-1]++;
				$file_name = implode('_', $file_name);
			}else{
				$file_name = implode('_', $file_name);
				$file_name .= '_1';
			}
			$file_name .= '.'.$ext;
		}
		$this->File_joint[$file_name] = array(
										'url' => $url_file,
										'mime' => $mime[$ext]
										);
		unset($file_name, $mime, $ext);
    }


    //**************************************************************************
    // Entêtes (Headers)
    //**************************************************************************
    function headers(){
		// Id unique
		$Boundary1 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';
		$Boundary2 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';
		$Boundary3 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';

        $header = '';
        $No_body = 0;

        // Adresse de l'expéditeur (format : Nom <adresse_mail>)
        if(!empty($this->From)){
            $header .= 'X-Sender: '.$this->From."\n";// Adresse réelle de l'expéditeur
        }
		// La version mime
        if(!empty($this->MIME)){
            $header .= 'MIME-Version: '.$this->MIME."\n";
        }
        $header .= sprintf("Message-ID: <%s@%s>%s", md5(uniqid(time())), $this->NomDuDomaine, "\n")
        .'Date: '.date('r')."\n"
        .'Content-Type: Multipart/Mixed;'."\n"
        .'  boundary="'.$Boundary1.'"'."\n"
        // Logiciel utilisé pour l' envoi des mails
		.'X-Mailer: PHP '.phpversion()."\n";
		// Adresse de l'expéditeur (format : Nom <adresse_mail>)
        if(!empty($this->From)){
            if(!empty($this->FromName)){
                $header .= 'From: "'.$this->FromName.'"';
            }else{
                $header .= 'From: ';
            }
            $header .= '<'.$this->From.">\n";
		}
		$header .= 'X-FID: FLAVOR00-NONE-0000-0000-000000000000'."\n";

		// Priorité accordée au mail (valeur allant de 1 pour Urgent à 3 pour normal et 6 pour bas)
        if(!empty($this->Priority)){
            $header .= 'X-Priority: '.$this->Priority."\n";
        }
		// To
        if(!empty($this->To)){// A
            $header .= 'To: '.$this->To."\n";
        }else{
            $No_body++;// Personne
        }
        // Cc
        if(!empty($this->Cc)){// Copie du mail
            $header .= 'Cc: '.$this->Cc."\n";
        }else{
            $No_body++;// Personne
        }
        // Bcc
        if(empty($this->Bcc)){// Blind Carbon Copy, c'est à dire que les adresses qui sont contenue ici seront invisibles pour tout le monde
            $No_body++;// Personne
        }
        // Sujet
        if(!empty($this->Sujet)){
            $header .= 'Subject: '.$this->Sujet."\n";
        }
        if(!empty($this->Confimation_reception)){// Adresse utilisée pour la réponse au mail
            $header .= 'Disposition-Notification-To: <'.$this->Confimation_reception.'>'."\n";
        }
		// ReplyTo
		if(!empty($this->ReplyTo) && $this->ReplyTo !== $this->From && $this->ReplyTo !== 'root@localhost'){// Adresse utilisée pour la réponse au mail
            $header .= 'Reply-to: '.$this->ReplyTo."\n"
            .'Return-Path: <'.$this->ReplyTo.">\n";
        }
		$header .= "\n\n\n"
		.'--'.$Boundary1."\n"
		.'Content-Type: Multipart/Alternative;'."\n"
		.'  boundary="'.$Boundary3.'"'."\n"
		."\n\n"
		.'--'.$Boundary3."\n";
		if($this->ContentType === 'txt' || $this->ContentType === 'txt/html'){
			$header .= 'Content-Type: Text/Plain;'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n";
			if($this->ContentType === 'txt'){
				$header .= $this->Body."\r\n";
			}else{
				$header .= $this->Body_txt."\r\n";
			}
		}elseif($this->ContentType === 'html' || $this->ContentType === 'txt/html'){
			if($this->ContentType === 'txt/html'){
				$header .= '--'.$Boundary3."\r\n";
			}
			$header .= 'Content-Type: Text/HTML;'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n"
			.'<html><head>'."\r\n"
			.'<meta http-equiv="Content-LANGUAGE" content="French" />'."\r\n"
			.'<meta http-equiv="Content-Type" content="text/html; charset='.$this->ISO.'" />'."\r\n"
			.'</head>'."\r\n"
			.'<body>'."\r\n"
			.$this->Body."\r\n"
			.'</body></html>'."\r\n"
			.'--'.$Boundary3.'--'."\r\n";
		}else{
			$header .= 'Content-Type: '.$this->ContentType.';'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n"
			.$this->Body."\r\n";
		}
		$header .= "\n";

		// On joint le ou les fichiers
		if($this->File_joint){
			foreach($this->File_joint as $file_name => $file){
		        $header .= '--'.$Boundary1."\n"
				.'Content-Type: '.$file['mime'].';'."\n"
				.'  name="'.$file_name.'"'."\n"
				.'Content-Disposition: attachment'."\n"
				.'Content-Transfer-Encoding: base64'."\n"
				."\n"
				.chunk_split(base64_encode(file_get_contents($file['url'])))."\n"
				."\n\n";
			}
		}
		$header .= '--'.$Boundary1.'--';

        if($No_body === 3){
            $this->erreur = 'Le mail n&#39; a pas de destinataire !!!';
            return false;
        }
        return $header;
    }


    //**************************************************************************
    // Envoie du mail avec le serveur SMTP
    //**************************************************************************
    function smtp_mail($to, $subject, $message, $header=''){
        // Pas de déconnection automatique
        $auto_disconnect = false;
        // On vérifi si la connection existe
        if(empty($this->smtp_connection)){
            if(!$this->Connect_SMTP()){// Connection
                $this->erreur .= 'Impossible d&#39; envoyer le mail !!!<br />'."\r\n";
                return false;
            }
            $auto_disconnect = true;// Déconnection automatique activée
        }

        // On vérifit Que c' est le premier tour sinon on éfface les anciens paramètres
        if($this->Tour){
            if($this->Commande('RSET', 250, 'Envoie du mail impossible !!!')){
                $this->Tour = 0;
            }
        }

        //**********************************************************************
        // Variables temporairement modifiées
        if(!empty($to)){
            $this->To = $to;
        }
        if(!empty($subject)){
            $this->Sujet = $subject;
        }

        if(is_array($message)){
			$this->Body = $message[0];
			$this->Body_txt = $message[1];
		}else{
        	$this->Body = $message;
        }

        //**********************************************************************
        // Y a t' il un destinataire
        if(empty($this->To) && empty($header) && empty($this->Bcc) && empty($this->Cc)){
            $this->erreur = 'Veuillez entrer une adresse de destination !!!<br />'."\r\n";
            return false;
        }

        //**********************************************************************
        // Envoie des informations
        //**********************************************************************

        //**********************************************************************
        // De Qui
        if(!empty($this->From) && !$this->Tour){
            if(!$this->Commande('MAIL FROM:<'.$this->From.'>', 250, 'Envoie du mail impossible car le serveur n&#39; accèpte pas la commande MAIL FROM !!!')){
                return false;
            }
            $this->Tour = 1;
        }

        //**********************************************************************
        // A Qui
        $A = array();
        if(!empty($this->To)){
            $A[0] = $this->To;
        }
        if(!empty($this->Bcc)){
            $A[1] = $this->Bcc;
        }
        if(!empty($this->Cc)){
            $A[2] = $this->Cc;
        }
        foreach($A as $cle => $tmp_to){
            if(substr_count($tmp_to, ',')){
                $tmp_to = explode(',', $tmp_to);
                foreach($tmp_to as $cle => $tmp_A){
                    if(!$this->Commande('RCPT TO:<'.$tmp_A.'>', array(250,251), 'Envoie du mail impossible car le serveur n&#39; accèpte pas la commande RCPT TO !!!')){
                        return false;
                    }
                }
            }else{
                if(!$this->Commande('RCPT TO:<'.$tmp_to.'>', array(250,251), 'Envoie du mail impossible car le serveur n&#39; accèpte pas la commande RCPT TO !!!')){
                    return false;
                }
            }
        }

        //**********************************************************************
        // On créer les entêtes ( headers ) si c' est pas fait
        if(empty($header)){
            if(!$header = $this->headers()){
                $this->erreur .= 'Impossible d&#39; envoyer le mail !!!<br />'."\r\n";
                return false;
            }
        }


        //**********************************************************************
        // On indique que l' on va envoyer des donnée
        if(!$this->Commande('DATA', 354, 'Envoie du mail impossible car le serveur n&#39; accèpte pas la commande DATA!!!')){
            return false;
        }


        //**********************************************************************
        // Envoie de l' entête et du message
        fputs($this->smtp_connection, $header);
        fputs($this->smtp_connection, "\r\n.\r\n");

        $reponce = $this->get_smtp_data();
        // Debug
        if($this->debug){
            echo '<div style="color:#993300;">Entête et message :<br />',"\r\n",'<div style="padding-left:25px;">',str_replace(array("\r\n","\n"), '<br />', $header),'<br />',"\r\n",$message,'</div>',"\r\n",'</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
        }
        if($reponce['code'] !== 250 && $reponce['code'] !== 354){
            $this->erreur = 'Envoie du mail impossible !!!<br />'."\r\n"
            .'Numéro de l&#39; erreur: '.$reponce['code'].'<br />'."\r\n"
            .'Message renvoyé: '.$reponce['msg'].'<br />'."\r\n";
            return false;
        }


        //**********************************************************************
        // Variables temporairement modifiées
        if($to === $this->To){
            $this->To = '';
        }
        if($subject === $this->Sujet){
            $this->Sujet = '';
        }

        //**********************************************************************
        // Déconnection automatique
        //**********************************************************************
        if($auto_disconnect){// Auto déconnection ?
            $this->Deconnection_SMTP();// Connection
        }

        //**********************************************************************
        // Mail envoyé
        //**********************************************************************
        return true;
    }


    //**************************************************************************
    // Lecture des données renvoyées par le serveur SMTP
    //**************************************************************************
    function get_smtp_data(){
        $data = '';
        while($donnees = fgets($this->smtp_connection, 515)){// On parcour les données renvoyées
            $data .= $donnees;

            if(substr($donnees,3,1) == ' ' && !empty($data)){break;}// On vérifi si on a toutes les données
        }
        // Renvoie des données : array(Code, message complet)
        return array('code'=>(int)substr($data, 0, 3), 'msg'=>$data);
    }


    //**************************************************************************
    // Lecture des données renvoyées par le serveur SMTP
    //**************************************************************************
    function Commande($commande, $bad_error, $msg_error='', $debug=1){
        if(!empty($this->smtp_connection)){
            fputs($this->smtp_connection, $commande."\n");
            $reponce = $this->get_smtp_data();
            // Debug
            if($this->debug && $debug){
                echo '<div style="color:#993300;">',htmlentities($commande),'</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
            }

            // Tableau de code valide
            if((is_array($bad_error) && !in_array($reponce['code'], $bad_error)) || (!is_array($bad_error) && $reponce['code'] !== $bad_error)){
                if($msg_error){
                    $this->erreur = $msg_error.'<br />'."\r\n"
                    .'Numéro de l&#39; erreur: '.$reponce['code'].'<br />'."\r\n"
                    .'Message renvoyé: '.$reponce['msg'].'<br />'."\r\n";
                }
                if(!$debug){
                    return array('no_error'=>false, 'msg'=>$reponce['msg']);
                }else{
                    return false;
                }
            }

            if(!$debug){
                return array('no_error'=>true, 'msg'=>$reponce['msg']);
            }else{
                return true;
            }
        }else{
            $this->erreur = 'Impossible d&#39; éxecuter la commande <span style="font-weight:bolder;">'.$commande.'</span> car il n&#39; y a pas de connection !!!<br />'."\r\n";
            if(!$debug){
                return array('no_error'=>false, 'msg'=>'');
            }else{
                return false;
            }
        }

    }


    //**************************************************************************
    // Fonction de déconnection SMTP
    //**************************************************************************
    function Deconnection_SMTP(){
        if(!empty($this->smtp_connection)){
            if(!$this->Commande('QUIT', 221, 'Impossible de se déconnecter !!!')){
                return false;
            }

            @sleep(5);// On laisse 5 seconde au serveur pour terminer toutes les instructions
            if(!fclose($this->smtp_connection)){
                $this->erreur = 'Impossible de se déconnecter !!!<br />'."\r\n";
                return false;
            }
            return true;
        }
        $this->erreur = 'Impossible de se déconnecter car il n&#39; y a pas de connection !!!<br />'."\r\n";
        return false;
    }
}
?>