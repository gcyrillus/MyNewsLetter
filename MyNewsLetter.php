<?php
	/**
		* Plugin 	MyNewsLetter
		* @author	Cyrille G.
		* 01/07/2023
  		* LICENCE Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)  http://creativecommons.org/licenses/by-sa/4.0/ 
    		* Ce(tte) œuvre est mise à disposition selon les termes de la http://creativecommons.org/licenses/by-sa/4.0/ Licence Creative Commons 
      		* Attribution -  Partage dans les Mêmes Conditions 4.0 International 
	**/
	class MyNewsLetter extends plxPlugin {
		
		private $url = ''; 
		public $lang = '';
		public 	$subscriptions ;
		public $revoque;
		public $from = 'no-reply';
		public $newsTpl;
		public $bodyNews;
		public $newsDate;
		public $cats;
		public $lots ='1';
		
		
		
		/**
			* Constructeur de la classe
			*
			* @param	default_lang	langue par défaut
			* @return	stdio
			* @author	Stephane F
		**/
		public function __construct($default_lang) {
			
			# gestion du multilingue plxMyMultiLingue natif
			$this->lang='';
			if(defined('PLX_MYMULTILINGUE')) {
				$lang = plxMyMultiLingue::_Lang();
				if(!empty($lang)) {
					if(isset($_SESSION['default_lang']) AND $_SESSION['default_lang']!=$lang) {
						$this->lang = $lang.'/';
					}
				}
			}	
			
			
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			$this->url = 'NewsLetter';
			if(file_exists(PLX_PLUGINS.basename(__DIR__).'/activated.php')) include(PLX_PLUGINS.basename(__DIR__).'/activated.php');
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN);
			
			# droits pour accèder à la page admin.php du plugin
			//if($this->getParam('saveNewsLetter'))
			$this->setAdminProfil(PROFIL_ADMIN);
			
			# déclaration des hooks
			$this->addHook('AdminTopEndHead', 'AdminTopEndHead');
			$this->addHook('AdminTopBottom', 'AdminTopBottom');
			$this->addHook('plxAdminHtaccess', 'plxAdminHtaccess');		
			$this->addHook('Index','Index');
			$this->addHook('plxShowConstruct', 'plxShowConstruct');
			$this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
			$this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
			$this->addHook('plxShowPageTitle', 'plxShowPageTitle');
			$this->addHook('plxMotorDemarrageNewCommentaire','plxMotorDemarrageNewCommentaire');
			$this->addHook('SitemapStatics', 'SitemapStatics');
			$this->addHook('MyNewsLetterForm', 'form');
			$this->addHook('IndexBegin','IndexBegin');	
			$this->addHook('IndexEnd','IndexEnd');	
			
		}
		
		/**
			* Méthode qui mets à jour le fichier .htaccess du site pour prendre en charge $_GET dans le traitement du formulaire
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxAdminHtaccess() {
			echo '<?php	$htaccess = str_replace("[L]", "[QSA,L]", $htaccess); ?>';
		}
		
		
		/**
			* Méthode qui charge le code css et le javascript  nécessaire à la gestion de onglet dans l'administration
			*
		**/
		public function AdminTopEndHead() {	
			echo '<script src="'.PLX_PLUGINS.$this->plug['name'].'/js/onglets.js"></script>'."\n";
		}
		
		/**
			* Méthode qui affiche un message si le plugin n'a pas la langue du site dans sa traduction
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function AdminTopBottom() {
			echo '<?php
			$file = PLX_PLUGINS."'.$this->plug['name'].'/lang/".$plxAdmin->aConf["default_lang"].".php";
			if(!file_exists($file)) {
			echo "<p class=\"warning\">Plugin MyNewsLetter<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
			}
			?>';
		}
		
		/**
			* Méthode de traitement du hook plxShowConstruct
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowConstruct() {
			# infos sur la page statique
			$string  = "if(\$this->plxMotor->mode=='".$this->url."') {";
			$string .= "	\$array = array();";
			$string .= "	\$array[\$this->plxMotor->cible] = array(
			'name'		=> '".$this->getParam('mnuName_'.$this->default_lang)."',
			'menu'		=> '',
			'url'		=> 'NewsLetter',
			'readable'	=> 1,
			'active'	=> 1,
			'group'		=> ''
			);";
			$string .= "	\$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
			$string .= "}";
			echo "<?php ".$string." ?>";
		}
		
		# code à exécuter à l’activation du plugin 
		# creer une variable aleatoire pour le dossier et fichier de stockage des abonnements.
		# @author G.Cyrille
		public function OnActivate() { 
			# verifie la presence du fichier d'initialisation
			$initFile=PLX_PLUGINS.basename(__DIR__).'/activated.php';
			if(!file_exists($initFile)) {
				#création d'un nom de repertoire aléatoire
				$subscriptionDir=bin2hex(random_bytes('16'));// php7 au minimum!!!!
				#création du contenu du fichier d'initialisation
				$file_content='<?php if(!defined(\'PLX_ROOT\')) exit;'.PHP_EOL.'$this->subscriptions =\''.$subscriptionDir.'\';';
				#ecriture du fichier
				if (!touch($initFile)) {
					echo "erreur";
					} else {
					file_put_contents($initFile, $file_content);
				}
				#création du repertoire dans le plugin
				mkdir(PLX_PLUGINS.basename(__DIR__)."/".$subscriptionDir, 0775);	
				touch(PLX_PLUGINS.basename(__DIR__)."/".$subscriptionDir."/index.html");
			}
			
			include(PLX_PLUGINS.basename(__DIR__).'/activated.php');
			# on verifie nos repertoires et on cré les manquants
			if (!file_exists(PLX_ROOT.'data/medias/news/')) { mkdir(PLX_ROOT.'data/medias/news/', 0777, true);}
			if (!file_exists(PLX_ROOT.'data/medias/corporate/')) { mkdir(PLX_ROOT.'data/medias/corporate/', 0777, true);}
			if(  file_exists(PLX_PLUGINS.basename(__DIR__).'/logo.png')) rename(PLX_PLUGINS.basename(__DIR__).'/logo.png', PLX_ROOT.'data/medias/corporate/logo.png');
			if (!file_exists(PLX_PLUGINS.basename(__DIR__).'/ListNews/')) { mkdir(PLX_PLUGINS.basename(__DIR__).'/ListNews/', 0777, true);}
			if (!file_exists(PLX_PLUGINS.basename(__DIR__).'/SentNews/')) { mkdir(PLX_PLUGINS.basename(__DIR__).'/SentNews/', 0777, true);}	
			if (!file_exists(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.json')) {
				touch(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.json');
				file_put_contents(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.json','[]');
			}
			# on déplace les png vers data/medias/... pour en faire profiter tous les contenus (resteront là même aprés la suppression d'un plugin depuis l'admin
			if(file_exists(PLX_PLUGINS.basename(__DIR__).'/socialPNG')) rename(PLX_PLUGINS.basename(__DIR__).'/socialPNG', PLX_ROOT.'data/medias/news/socialPNG');
			
			# filtrage ip 
			if (!file_exists(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.ip.json')) {
				touch(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.ip.json');
				file_put_contents(PLX_PLUGINS.basename(__DIR__).'/'.$this->subscriptions.'/'.$this->subscriptions.'.ip.json','[]');
			}
			
		}
		
		/**
			* methode de traitement abonnement depuis formulaire de commentaire
			* verifie la validité du mail avant enregistrement
			* @author G.Cyrille			
		*/
		public function Index() {
			# enregistrement abonnement depuis formulaire de commentaire
			if(isset($_POST['newsme']) AND $this->checkEmail($_POST['mail']) ) {$_POST['courriel'] = $_POST['mail']; $this->updateJson();} 	
		}
		
		/**
			* Méthode de traitement du hook plxMotorPreChauffageBegin
			*
			* @return	stdio
			* @author	Stephane F =
		**/
		public function plxMotorPreChauffageBegin() {
			
			$this->from = $this->getParam('from') == '' ? $this->from  : $this->getParam('from');
			$template = $this->getParam('template')==''?'static.php':$this->getParam('template');
			
			$string = "
			if(\$this->get && preg_match('/^".$this->url."\/?/',\$this->get)) {
			\$this->mode = '".$this->url."';
			\$prefix = str_repeat('../', substr_count(trim(PLX_ROOT.\$this->aConf['racine_statiques'], '/'), '/'));
			\$this->cible = \$prefix.\$this->aConf['racine_plugins'].'MyNewsLetter/form';
			\$this->template = '".$template."';
			return true;
			}
			";
			
			echo "<?php ".$string." ?>";
			
			# completion message si subscription depuis l'envoi d'un commentaire
			if(isset($_SESSION['msgcom']) AND isset($_SESSION['msgSubscription'])) $_SESSION['msgcom'] = $_SESSION['msgcom'].' '.$_SESSION['msgSubscription'];
			
			# faut-il envoyé les news ?
			$this->isItTime();			
		}
		
		/**
			* Méthode de traitement du hook plxShowStaticListEnd
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowStaticListEnd() {
			
			# ajout du menu pour accèder à la page de recherche
			if($this->getParam('mnuDisplay')) {
				echo "<?php \$status = \$this->plxMotor->mode=='".$this->url."'?'active':'noactive'; ?>";
				echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li class=\"static menu '.\$status.'\" id=\"static-NewsLetter\"><a href=\"'.\$this->plxMotor->urlRewrite('?".$this->lang.$this->url."').'\" title=\"".$this->getParam('mnuName')."\">".$this->getParam('mnuName')."</a></li>'); ?>";
			}
		}
		
		/**
			* Méthode qui renseigne le titre de la page dans la balise html <title>
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowPageTitle() {
			
			echo '<?php
			if($this->plxMotor->mode == "'.$this->url.'") {
			$this->plxMotor->plxPlugins->aPlugins["MyNewsLetter"]->lang("L_PAGE_TITLE");
			return true;
			}
			?>';
		}
		
		
		/**
			* Squatte le formulaire de commentaire
			* confirmation d'enregistrement d'abonnement dans le message
			*
			* @author G.Cyrille
		**/
		public function plxMotorDemarrageNewCommentaire(){
			$_SESSION['msgSubscription'] ='';
			if(isset($_POST['newsme']) AND $this->checkEmail(trim($_POST['mail']))) {
				$_SESSION['msgSubscription'] = $this->getLang('L_SUBSCRIPTION_REGISTERED');
				}else {
				unset($_POST['newsme']);unset($_SESSION['msgSubscription']);
			}
		}
		
		/**
			* Méthode qui référence la page de recherche dans le sitemap
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function SitemapStatics() {
			echo '<?php
			echo "\n";
			echo "\t<url>\n";
			echo "\t\t<loc>".$plxMotor->urlRewrite("?'.$this->lang.$this->url.'")."</loc>\n";
			echo "\t\t<changefreq>monthly</changefreq>\n";
			echo "\t\t<priority>0.8</priority>\n";
			echo "\t</url>\n";
			?>';
		}
		
		/**
			* Méthode statique qui affiche le formulaire d'abonnement
			*
			* @parm		title		affiche le titre du formulaire si vrai
			* @return	stdio
			* @author	Stephane F
		**/
		public static function form($title=false) {
			
			$placeholder = '';
			$courriel ='';
			
			# récupération d'une instance de plxMotor
			$plxMotor = plxMotor::getInstance();
			$plxPlugin = $plxMotor->plxPlugins->getInstance('MyNewsLetter');
			$method = $plxPlugin->getParam('method') == 'get' ? $_GET : $_POST;
			$frmMethod = $plxPlugin->getParam('method') == 'get' ? 'get' : 'post';
			
			if(!empty($method['courriel'])) {
				$courriel = plxUtils::strCheck(plxUtils::unSlash($method['courriel']));
			}
			if($plxPlugin->getParam('placeholder')!='') {
				$placeholder=' placeholder="'.$plxPlugin->getParam('placeholder').'"';
			}
		?>
		
		<div class="NewsLetterform">
			<form action="<?php echo $plxMotor->urlRewrite('?'.$plxPlugin->getParam('url')) ?>" method="<?php echo $frmMethod ?>" class="newsform">
				
				<h3 class="NewsLettertitle"><?php $plxPlugin->lang('L_FORM_TITLE');	?></h3>
				<div class="newsL">
					
					<p>
						<input <?php echo $placeholder ?> class="courriel" name="courriel" type="email" required value="<?php echo $courriel ?>" />
						<input type="submit" class="subscribe_button" value="<?php echo $plxPlugin->getParam('frmLibButton') ?>" />
					</p>
					<p class="subscribOption">
						<label for="valid"><?php $plxPlugin->lang('L_AUTHORIZE_MAIL') ?></label><input type="checkbox" name="valid" id="valid" value="1">
					</p>
				</div>
			</form>
		</div>
		
		<?php
		}
		/**
		
		
		**/
		public function IndexBegin() {
			# recup retour newsletter.
			if(isset($_GET['news'])) {$this->record('visit');}
		}	
		
		/**
			* Squatte le formulaire de commentaire
			* ajoute case à cocher pour s'abonner à la newsletter
			*
			* appele le fichier comment.js
			* affiche la case via javascript à la volée si mail valide
			*
			* @author G.Cyrille
		**/
		public function IndexEnd() {
			$subscribe='<input id="id_mail" name="mail" type="text" size="20" value="" />'.PHP_EOL.'<p id="subscribeME" style="padding:0;margin:0 ;background: #aed494;justify-content: center;gap: 2em;;display:none"><label for="newsme">'.$this->getLang('L_SUBSCRIBE_OPTION').'</label><input type="checkbox" name="newsme"></p>
			<script src="'.PLX_PLUGINS.$this->plug['name'].'/js/comment.js"></script>';
			echo '<?php		
			global $plxMotor;
			if(($plxMotor->mode === \'article\') AND $plxMotor->aConf[\'allow_com\'] == 1 AND $plxMotor->plxRecord_arts->f(\'allow_com\') ==1) {
			$output  = str_replace(\'<input id="id_mail" name="mail" type="text" size="20" value="" />\', \''.$subscribe.'\',$output);			
			}
			?>';
			
		}
		
		######################
		# fonctions internes #
		######################
		
		/**
			* Méthode qui ajoute un abonné au  fichier json existant
			*
			* @author	Cyrille G.
		**/				
		public function updateJson(){
			$method = $this->getParam('method') == 'get' ? $_GET : $_POST;
			$frequence=$this->getLang('L_NEWS_LETTER');
			if(isset($method['courriel']) and  !str_contains(trim($method['courriel']), 'data-backup-store.com')) { 
				$email= trim($method['courriel']);
				if($this->checkEmail($email)) {
					
					$plxMotor = plxMotor::getInstance();
					
					$mail=base64_encode($email.$this->subscriptions);
					
					$date=date("m-Y"); 					
					$lastSent=$date;
					# valeur lien de désabonnement
					$revoque= bin2hex(random_bytes('16'));
					# etat validation abonnement
					if(!isset($method['valid'])) {$valid='0';} else {$valid='1';}
					# datas
					$datasSubscriptions = json_decode(file_get_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json'), true);
					# des datas ?
					if(is_array($datasSubscriptions)) {
						$datasSubscriptions= array_values( array_column($datasSubscriptions, null, 'email') );
						foreach($datasSubscriptions as $element) {
							if($email == str_replace($this->subscriptions, '',base64_decode( $element['email']))) {
								echo sprintf($this->getLang('L_SUBSCRIPTION_FOUND'), $this->getParam('from'),$plxMotor->aUsers['001']['name'],$plxMotor->aConf['title'] );
								goto endIt;
								break;
							}
						}					
						end($datasSubscriptions) ;
						# stats abonnement
						$newSubscription= key($datasSubscriptions)+ 1;
						# ajout abonnement
						$datasSubscriptions[$newSubscription]= array('email'=> $mail , 'dateSub'=> $date, 'lastSent'=> $lastSent, 'revoque' => $revoque, 'valid'=> $valid );
					}
					else { // premier abonement
						$datasSubscriptions[]= array('email'=> $mail , 'dateSub'=> $date, 'lastSent'=> $lastSent, 'revoque' => $revoque, 'valid'=> $valid);	
					}
					
					# valeur desabonnement
					$this->revoque = $revoque;
					#sauvegarde du fichier
					file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json', json_encode($datasSubscriptions,true) );	
					
					# si validation requise
					if($valid =='0') {
						$this->validBack($email,$frequence,$revoque);
					}
					else {
						$subcs = $this->getLang('L_NEWS_LETTER');
						$validate = $plxMotor->urlRewrite('?'.$this->getParam('url').'&validNewsLetter='.$revoque);
						$cancel   = $plxMotor->urlRewrite('?'.$this->getParam('url').'&stopNewsLetter='.$revoque);
						$body	  = sprintf($this->getLang('L_SUBSCRIPTION_AUTO'), $email, $subcs, $cancel ,$plxMotor->aUsers['001']['name'], $plxMotor->aConf['title']);
						
						$this->envoiCourriel($plxMotor->aUsers['001']['name'], $this->from, $email, 'Confirmation de votre abonnement à la newsLetter' , $body, $contentType="html", $cc=false, $bcc=false);
						$recap = sprintf($this->getLang('L_SUBSCRIPTION_ACTIVE'), $subcs, $email ,  $cancel);
						if($recap) echo $recap;
					}
				}
				else {
					echo $this->getLang('L_INVALID_MAIL');
				}
				
			}
			endIt:  // si pas de datas
		}
		
		/* verifie le format d'une adresse mail
			* Verification via les fonctions de PHP
			*
			* @author	Cyrille G.
		*/
		public function checkEmail($email) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				return true;
				}else{
				if(!$this->getParam('frmDisplay'))	MyNewsLetter::form(true);
				return false;
			}
		}
		
		/**
			* methode qui envoi un mail de demande de confirmation d'abonnement 
			* 
			* Verifie que le possesseur de l'adresse mail est bien consentant
			*
			* @author Cyrille G.
		**/	
		public function validBack($email, $frequence,$revoque){
			$frequence =$this->getLang('L_NEWS_LETTER');
			$plxMotor = plxMotor::getInstance();
			$validate = $plxMotor->urlRewrite('?'.$this->getParam('url').'&validNewsLetter='.$revoque);
			$cancel   = $plxMotor->urlRewrite('?'.$this->getParam('url').'&stopNewsLetter='.$revoque);
			$body = sprintf($this->getLang('L_SUBSCRIPTION_VALIDATE'),  $email , $frequence ,  $validate , $cancel , $plxMotor->aUsers['001']['name'], $plxMotor->aConf['title']);
			# envoi d'un courriel  // note: voir pour test ou faire usage de phpMailer
			$this->envoiCourriel($plxMotor->aUsers['001']['name'], $this->from , $email, 'Validez votre abonnement à la newsLetter ou ignorez ce message' , $body, $contentType="html", $cc=false, $bcc=false);
			#affichage message 
			echo sprintf($this->getLang('L_SUBSCRIPTION_PENDING'), $frequence, $email , $cancel);
			
			
		}
		/**
			* Méthode qui valide un abonné au  fichier json
			*
			* @author	Cyrille G.
		**/	
		public function valid(){
			$method = 'get';
			$done=false;
			if(isset($_GET['validNewsLetter'])) {			
				$plxMotor = plxMotor::getInstance();
				$plxPlugin = $plxMotor->plxPlugins->getInstance('MyNewsLetter');
				$datasSubscriptions = json_decode(file_get_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json'), true);
				foreach($datasSubscriptions as $key=>$value){
					if( $value['revoque'] == $_GET['validNewsLetter']) {
						$email = str_replace($this->subscriptions, '',base64_decode( $datasSubscriptions[$key]['email']));
						if($value['valid'] == 1 ) {
							echo sprintf($this->getLang('L_SUBSCRIPTION_VALIDATED'), $email, $plxMotor->aUsers['001']['name']);
							$done=true;
							break;
						}
						
						$validate = $plxMotor->urlRewrite('?'.$this->getParam('url').'&validNewsLetter='.$datasSubscriptions[$key]['revoque']);
						$cancel   = $plxMotor->urlRewrite('?'.$this->getParam('url').'&stopNewsLetter='.$datasSubscriptions[$key]['revoque']);
						$body	  = sprintf($this->getLang('L_SUBSCRIPTION_AUTO'), $email, $cancel ,$plxMotor->aUsers['001']['name'], $plxMotor->aConf['title']);
						$datasSubscriptions[$key]['valid']='1';
						file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json', json_encode($datasSubscriptions,true) );
						echo '<blockquote cite="'.$plxMotor->aUsers['001']['name'].'">'.$body.'</blockquote>';
						$done=true;
						break;
					}
				}
				if(!$done) echo'<p class="msg warning">'.$plxPlugin->getLang('L_SUBSCRIPTION_NOT_FOUND').'</p>';					
			}			
		}
		
		/**
			* Méthode d'envoi de mail basée uniquement sur la fonction mail() 
			*
			* @param	name	string 			Nom de l'expéditeur
			* @param	from	string 			Email de l'expéditeur
			* @param	to		array/string	Adresse(s) du(des) destinataires(s)
			* @param	subject	string			Objet du mail
			* @param	body	string			contenu du mail
			* @return			boolean			renvoie FAUX en cas d'erreur d'envoi
		**/
		public static function envoiCourriel($name, $from, $to, $subject, $body, $contentType="html", $cc=false, $bcc=false) {
			if(is_array($to))
			$to = implode(', ', $to);
			if(is_array($cc))
			$cc = implode(', ', $cc);
			if(is_array($bcc))
			$bcc = implode(', ', $bcc);
			
			$headers  = "From: ".$name." <".$from.">\r\n";
			$headers .= "Reply-To: ".$from."\r\n";
			$headers .= 'MIME-Version: 1.0'."\r\n";
			// Content-Type
			if($contentType == 'html')
			$headers .= 'Content-type: text/html; charset="' .PLX_CHARSET. '"'."\r\n";
			else
			$headers .= 'Content-type: text/plain; charset="' .PLX_CHARSET. '"'."\r\n";
			
			$headers .= 'Content-transfer-encoding: 8bit'."\r\n";
			$headers .= 'Date: '.date("D, j M Y G:i:s O")."\r\n"; // Sat, 7 Jun 2001 12:35:58 -0700
			
			if($cc != "")
			$headers .= 'Cc: '.$cc."\r\n";
			if($bcc != "")
			$headers .= 'Bcc: '.$bcc."\r\n";
			
			return mail($to, $subject, $body, $headers);
		}			
		
		/**
			* Méthode qui retire un abonné au  fichier json
			*
			* @author	Cyrille G.
		**/	
		public function revoque(){
			$method = 'get';
			$done=false;
			if(isset($_GET['stopNewsLetter'])) {			
				$plxMotor = plxMotor::getInstance();
				$plxPlugin = $plxMotor->plxPlugins->getInstance('MyNewsLetter');
				$datasSubscriptions = json_decode(file_get_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json'), true);
				foreach($datasSubscriptions as $key=>$value){
					if( $value['revoque'] == $_GET['stopNewsLetter']) {
						unset($datasSubscriptions[$key]);
						file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json', json_encode($datasSubscriptions,true) );
						echo '<blocquote class="msg"><div>'.$plxPlugin->getLang('L_UNSUBSCRIB').'<br><cite>'.$plxMotor->aUsers['001']['name'].'</cite></div></blockquote>';
						$this->record('defec');
						$done=true;
					}
				}
				if(!$done) echo'<p class="msg warning">'.$plxPlugin->getLang('L_SUBSCRIPTION_NOT_FOUND').'</p>';
			}
		}
		
		/* verifie le nom de domain et retourne le domain principale
			* reconstruit une adresse mail à partir du nom de domaine principale
			* précedé de newsletter ou du sous domaine
			*
			* @author	Cyrille G.
		*/
		public function get_domain(){
			$myhost = strtolower(trim(preg_replace('#^www://#', '', $_SERVER['SERVER_NAME'])));
			$count = substr_count($myhost, '.');
			if($count === 1) {	
				# on prend le nom de domaine et l'adresse newsletter
				$domain = explode('.', $myhost, 2);
				$addr= $domain[0].'.'.$domain[1];
				$myhost = 'newsletter@'.$addr;	
				}else {
				# on prend le nom de domaine principal et le sous domaine comme adresse
				$myname= explode('.', $myhost);
				$myName = $myname[0];	
				$addr= $myname[1].'.'.$myname[2];				
				$myhost = $myName.'@'.$addr;
			}
			return $myhost;
		}
		
		/**
			* Tests en entonnoir avant de validé l'envoi des news:
			*
			* délai d'une minute entre chaque test
			* Y-a t-il des abonnés
			* Est ce une plage d'envoi
			* Y-a t-il de nouvelle publication ?
			* reste t-il des abonnés elligible à l'envoi d'une NewsLetter
			* configuration manuel ou automatique
			* As t-on prevenu le webmestre
			* envoi automatique ou envoi validé, on envoi et on compte
			* Mise à jour du fichier abonnés.
			*
			* @author Cyrille G.
			*
		**/
		public function isItTime() {
			
			
			if(strtotime('-1 minutes') > filemtime(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.ip.json')) {		
				# reinitialisation
				file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.ip.json', '[]' ); // pas de traitements ni stockage autre que la date de modif du fichier
				
				# recupe abonnements
				$datasSubscriptions = json_decode(file_get_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json'), true);
				
				# y a t-il des abonnements, 
				if(is_array($datasSubscriptions) AND count($datasSubscriptions) > 0) { //si oui on continue, il y a au moins un abonné
					# est ce le moment d'envoyé une newsletter ?	
					
					# init quel jour, mois, ... année?
					$d =  date('d');
					$m =  date('m');
					$y =  date('Y');				
					# n° du jour de la semaine ?
					$thisNbDay = date('N', strtotime($y.'-'.$m.'-'.$d));				
					
					# somme nous sur une plage d'envoi ?	
					# comparaison config plage jour/horaire et jour/heure actuelle
					if(in_array($thisNbDay, range($this->getParam('day1'), $this->getParam('day2'))) && in_array(date('H'), range($this->getParam('hour1'),$this->getParam('hour2')))) {
						# As t-on de nouvelle publications ?
						# ce mois 
						$dateCheck =new DateTime(date('01-m-Y')); // le mois en cours.		
						
						# frequence
						$frequence =$this->getParam('frequency')  =='' ? '3'	: $this->getParam('frequency');
						# date de reference à prendre
						$refDate = new DateTime('-'.$frequence.' month');
						# formatage date de reference au format fichier article
						$refDate=date_format($refDate, 'YmdHi');
						# appel a la class plxMotor
						$plxMotor = plxMotor::getInstance();
						# on se cale sur la date du dernier article								
						ksort($plxMotor->plxGlob_arts->aFiles);
						$dateArt =$plxMotor->artInfoFromFilename(end($plxMotor->plxGlob_arts->aFiles))['artDate'];
						#envois validés si manuel?
						$goSend =$this->getParam('sendValidated')  =='' ? '01-2000'	: $this->getParam('sendValidated');						
						# l'article est-il considéré comme nouveau? ou avons nous validé un envoi?
						if($dateArt > $refDate) {						
							# si prevenir webmestre?, initialisé variable derniere alerte
							$lastWarning='initMe';
							if($this->getParam('paramSend') =='1')  $lastWarning = date_create($this->getParam('warnWebmaster') =='' ? '01-01-2023'	: $this->getParam('warnWebmaster'));
							
							# as t-on des abonnés a qui envoyé une newsLetter
							$go=false;
							
							# combien d'envoi par lots ?
							$this->lots = $this->getParam('lots') =='' ? '1' 	: $this->getParam('lots');
							
							# initialisation du compteur
							$mailsSent=0;	
							

							
							
							# parcours des abonnements 
							foreach($datasSubscriptions as $records =>$v) {
								// verif si accord validé
								$lastSent=  new DateTime('01-'.$v['lastSent']);
								# comparaison ce mois ci et dernier envoi
								$dateGap = $lastSent->diff($dateCheck);
								# est-ce un abonnement non validé depuis de + 6 mois ?
								if(!isset($v['valid']) OR $v['valid'] == '0' AND $dateGap->m + $dateGap->y *12 > 6) {
									# nettoyage des abonnements non validés
									unset($datasSubscriptions[$records]);
									$this->record('unset');
									# MAJ du fichier
									file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json', json_encode($datasSubscriptions,true) );
								}
								# est-il temps d'envoyer une newsLetter à l'abonné?
								if(($dateGap->m) + ($dateGap->y) *12 > $frequence) {	
									# si bon moment, quelles config d'envois?
									if(is_a($lastWarning, 'DateTime') AND $goSend != date('m-Y') ) { // paramSend à 1 , on previent le webmestre
										if( $lastWarning->diff($dateCheck)->m != 0) { // déja prevenu ce mois-ci ?
											# - envoi manuel = notif au webmestre 
											$this->envoiCourriel('##- '.$plxMotor->aConf['title'].' -##', $plxMotor->aUsers['001']['email'], $plxMotor->aUsers['001']['email'], sprintf($this->getLang('L_THERE_IS_NEWS_TO_SEND'), $plxMotor->aConf['title']) , sprintf($this->getLang('L_THERE_IS_NEWS_TO_SEND'), $plxMotor->aConf['title']), $contentType="html", $cc=false, $bcc=false);
											# enregistrement de la date de notification
											$this->setParam('warnWebmaster',date('01-m-Y'), 'string');
											include(PLX_ROOT.'/core/lib/class.plx.msg.php');//include plxMsg 
											define('L_SAVE_SUCCESSFUL','');// init lang var and shut it up
											define('L_SAVE_ERR','');// init lang var and shut it up
											# envoyé, on se garde la date en mémoire
											$this->saveParams();									
										}
										#  on break , pas besoin d'aller plus loin dans la boucle 1 seul matche suffit
										
										break;
									}
									else {
										# - envoi automatique : on envoi et on continue selon la configuration des lots 
										if(isset($datasSubscriptions[$records])) {
											$this->sendNews($datasSubscriptions[$records]);
											# maj date dernier envoi
											$datasSubscriptions[$records]['lastSent']=$m.'-'.$y;								

											# traitement du lot
											# incrementation du nombre de news envoyées
											$mailsSent++;	
											if($mailsSent < $this->lots) {continue;} # lot incomplet
											else { break; } # lot traité, on s'arrete là
										}
									}
									
								}							
							}
							# maj du fichier abonnés si au moins un mail a été envoyé
							if($mailsSent>0) file_put_contents(PLX_ROOT.'plugins/'.__CLASS__.'/'.$this->subscriptions.'/'.$this->subscriptions.'.json', json_encode($datasSubscriptions,true) );		
							
						}
						
					}
					
				}
			}			
			
		}
		
		/**
			* methode qui envoi un courriel
			* 
			* @author Cyrille G.
		**/
		public function sendNews($subscribed) {
			
			# de qui ?
			$this->from=$this->getParam('from');
			
			# scan du dossier en gardant que les fichiers de la news générée
			$files = array_filter(scandir(PLX_PLUGINS.basename(__DIR__).'/ListNews/', SCANDIR_SORT_DESCENDING), function($item) {
				return !is_dir(PLX_PLUGINS.basename(__DIR__).'/ListNews/' . $item);
			});
			
			# filtre sur les fichier de news 00-00.html
			$files= preg_grep("/^[0-9]{2}-[0-9]{2}.html/", $files);
			
			# se caler sur le dernier article (1er enregistrement du tableau)								
			$newest_file = reset($files);
			# si pas encore de fichier , le creer
			if($newest_file =='' || filemtime(PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$newest_file) <= strtotime("-1 days")){  $this->buildMail($this->getParam('frequency')); }
			#construction du corps de la news
			$body  = file_get_contents(PLX_ROOT.'plugins/'.basename(__DIR__).'/tpl/bodyTopTpl.php');
			$body .= file_get_contents(PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$newest_file);
			$body .= file_get_contents(PLX_ROOT.'plugins/'.basename(__DIR__).'/tpl/bodyBottomTpl.php');
			
			#MAJ liens et date d'abonnement individuels
			# date 
			$body = str_replace('###DATE###', ' '.$this->getSubMonthDate($subscribed['dateSub']), $body);
			# stop 
			$plxMotor = plxMotor::getInstance();
			$body = str_replace('###STOP###', ' <a href="'.$plxMotor->urlRewrite( '?NewsLetter&stopNewsLetter='.$subscribed['revoque']).'">'.$this->getLang('L_STOP').'</a> ', $body);
			
			# insertion des liens catégories si il y a
			$body = str_replace('###CATEGORIE###', $this->catListLinks()  ?? '', $body);
			
			# envoi 
			$email = str_replace($this->subscriptions, '',base64_decode( $subscribed['email']));
			if($this->envoiCourriel($plxMotor->aUsers['001']['name'], $this->from , $email, $this->getParam('object') , $body, $contentType="html", $cc=false, $bcc=false) == true	) {
				#incrementation le nombre de news envoyées 
				//$mailsSent++;		
				# incremente les stats 
				$this->record('sent');
				# ajout aux archives
				if(!file_exists(PLX_PLUGINS.basename(__DIR__).'/SentNews/'.$newest_file)) { copy(PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$newest_file, PLX_PLUGINS.basename(__DIR__).'/SentNews/'.$newest_file);}
			}
			
			
		}
		
		# verifie le format d'une date
		public function isValidDate($date, $format = 'm-Y'){
			$dt = DateTime::createFromFormat($format, $date);
			return $dt && $dt->format($format) === $date;
		}
		
		/* traduit la date d'inscription en français 
			*
			* @author Cyrille G.
			*
		*/
		public function getSubMonthDate($dateString) {			
			if(class_exists('IntlDateFormatter')) {
				// Créer un objet DateTime à partir de la chaîne de date
				$date = DateTime::createFromFormat('m-Y', $dateString);			
				// Vérifier si la création de l'objet DateTime a réussi
				if ($date !== false) {
					// Formater la date en français à l'aide de la classe IntlDateFormatter
					$formatter = new IntlDateFormatter($this->lang, IntlDateFormatter::FULL, IntlDateFormatter::NONE);
					$formatter->setPattern('MMMM y');		
					$formattedDate = $formatter->format($date);		
					// Afficher la date traduite
					return $formattedDate;  
					} else {
					// Affiche la chaine passée par défaut
					return $dateString;
				}
			}
			else {
				if($this->default_lang !='en' && file_exists(PLX_PLUGINS.basename(__DIR__).'/lang/'.$this->default_lang.'.php')) {
					$MonthToTranslate = array('Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sept','Oct',' Nov','Dec') ;
					$index=0;					
					$newDate = date("M Y", strtotime('01-'.$dateString));
					foreach($this->getLang('L_DATE_MONTH_LONG') as $month){
						$newDate = str_ireplace($MonthToTranslate[$index], $this->getLang('L_DATE_MONTH_LONG')[$index], $newDate);  	
						$index++;				
					}
					return $newDate;
				}				
			}
		}
		
		/**
			* methode qui incremente les stats
			*
			* @author Cyrille G.
		**/	
		public function record($what){				
			$fileStat = PLX_ROOT.'plugins/'.basename(__DIR__).'/'.$this->subscriptions.'/infosStat.json';	
			
			if(!file_exists($fileStat)) {
				touch($fileStat);
				$sent= 0;
				$retours=0;
				$stats= array('sent'=> 0 , 'retour' => 0 , 'defec' => 0 , 'unset' => 0 , 'lastDate' => '01-2023', 'lastSent' => date('m-Y'));
			}
			
			$stats =  json_decode(file_get_contents($fileStat), true);
			$stats['sent'] = $stats['sent'];
			$stats['retour'] = $stats['retour'];
			$stats['defec'] = $stats['defec'];	
			$stats['unset'] = $stats['unset'];	
			$stats['lastDate'] = $stats['lastDate'];	
			$stats['lastSent'] = $stats['lastSent'];
			
			# enregistrement retour lien des mailing
			if($what=='visit') {
				$stats['lastDate'] = $stats['lastDate'];
				$stats['retour']++;		
			}
			# enregistrement envois mails
			if($what=='sent') {
				$stats['sent']++;
				$stats['lastSent'] = date('m-Y');
			}
			# enregistrement desabonements
			if($what=='defec') {
				$stats['defec']++;
			}
			# enregistrement nettoyages
			if($what=='unset') {
				$stats['unset']++;
			}
			# enregistrement date news envoyé
			if($what=='lastDate') {
				$stats['lastDate']= date('m-Y');
			}
			# sauvegarde
			file_put_contents($fileStat, json_encode($stats,true) );
		}
		
		/**
			* methode d'assemblage de la news letter
			*
			* @author Cyrille G.
		**/
		public function buildMail($do='') {			
			$plxMotor = plxMotor::getInstance();
			# pas d'URL rewriting pour les liens depuis la newsLetter !
			$plxMotor->aConf["urlrewriting"]=0;	
			
			$sort = $this->getParam('elements');
			$sort=explode(',',$sort);
			$dirTpl=PLX_ROOT.'plugins/'.basename(__DIR__).'/tpl/';
			$this->newsDate= date('m-y');
			if(!file_exists(PLX_PLUGINS.basename(__DIR__).'/ListNews/')) mkdir(PLX_PLUGINS.basename(__DIR__).'/ListNews/',0777,true);
			$newMail=PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$this->newsDate.'.html';
			$this->newsTpl =file_get_contents($dirTpl.'bodyTopTpl.php');
			$this->bodyNews =file_get_contents($dirTpl.'bodyNewsTpl.php');				
			
			# ajout contenu selon le sens de la config
			foreach($sort as $tpl => $frag ) {
				if($frag!='')  include($dirTpl.$frag);
			}			
			$this->bodyNews.=PHP_EOL.'				</table><!--close bodyFrags-->';			
			if(!file_exists($newMail)) {
				touch($newMail);
				file_put_contents($newMail, $this->bodyNews);
			}		
			if( $do !='') {
				file_put_contents($newMail, $this->bodyNews);
			}
			$this->newsTpl .=$this->bodyNews;
			# on créer la liste des catégories active (affichage optionnel dans la news)
			$this->catListLinks();
			# insertion des liens catégories optionnel
			$this->newsTpl = str_replace('###CATEGORIE###', $this->cats  ?? '', $this->newsTpl);   // $output ?
			$this->newsTpl .=file_get_contents($dirTpl.'bodyBottomTpl.php');			
		}
		
		/**
			* fait une copie de l'image au format png
			*
			* @author Cyrille G.
		**/
		public function saveToPng($filename) {
			$fileInfo = pathinfo($filename);
			$dirInfo = PLX_ROOT.'data/medias/news/'.$this->newsDate.'/';	
			if (!file_exists($dirInfo)) {mkdir($dirInfo, 0777, true);}				
			$imgNewsVersion= $dirInfo.'/'.$fileInfo['filename'].'.png';				
			imagepng(imagecreatefromstring(file_get_contents($filename)),$imgNewsVersion );
			return 'data/medias/news/'.$this->newsDate.'/'.$fileInfo['filename'].'.png';			
		}
		
		/** 
			* verifie si il y a au moins une News generée et la crée par défaut
			*
			* @author Cyrille G.
			
		**/
		public function scanListNews() {
			if( !file_exists(PLX_PLUGINS.basename(__DIR__).'/ListNews/')) mkdir(PLX_PLUGINS.basename(__DIR__).'/ListNews/');
			$files = scandir(PLX_PLUGINS.basename(__DIR__).'/ListNews/', SCANDIR_SORT_DESCENDING);				
			if(count(array_diff($files, array('..', '.'))) == 0) {
				$this->buildMail($this->getParam('frequency'));
			}
		}
		/** 
			* scan le repertoire des news envoyées
			* option=option : affiche des boite option
			* !option		: affiche un tableau javascript
			*
			* @author Cyrille G.
			
		**/
		public function scanNewsSent($option='') {
			if( !file_exists(PLX_PLUGINS.basename(__DIR__).'/SentNews/')) mkdir(PLX_PLUGINS.basename(__DIR__).'/SentNews/');
			$files = scandir(PLX_PLUGINS.basename(__DIR__).'/SentNews/', SCANDIR_SORT_DESCENDING);
			$listFiles='<script> let oldNewsFiles ={';
			if($files>0) {
				foreach($files as $found => $filename) {
					if( pathinfo($filename)['extension']== 'html') {
						$filesfound[]=$filename;
						if(!$option) {$content = file_get_contents(PLX_PLUGINS.basename(__DIR__).'/SentNews/'.$filename); 	$listFiles.= '\''.$filename.'\' : `'.$content.'`,';}
						if($option=='option') echo '<option value="'.$filename.'">'.$filename.'</option>';
					}			
				}
			}
			$listFiles .= ' }</script>';
			if(!$option) echo $listFiles;
		}			
		/**
			* Extraction des liens des catégories actives
			*
			* @author Cyrille G.
		**/
		public function catListLinks() {
			global $plxAdmin;
			$plxMotor = plxMotor::getInstance();	
			$this->cats='';
			if($plxMotor->aCats) {
				foreach($plxMotor->aCats as $k=>$v) { # Pour chaque catégorie
					if($v['menu'] == 'oui'  AND $v['active'] == 1 ) $this->cats .= '<a href="'.$plxMotor->urlRewrite( '?categorie'.intval($k).'/'. $v['url'].'&news='.date('m-Y')).'" target="_blank">'. $v['name'].'</a> ';					
				}
			}
			return $this->cats;
			
		}
		
		/**
			* verifie l'existence d'une News du mois en cours
			*
			* @author Cyrille G.
			*
		**/
		public function checkListNews() {
			if (!file_exists(PLX_PLUGINS.basename(__DIR__).'/ListNews/'.date('m-y').'.html')) $this->buildMail($this->getParam('frequency'));
		}		
		
	#end class du plugin
}
