<?php if(!defined('PLX_ROOT')) exit; 
	
	function getParam($p) {
		return ($p=='' OR $p=='1');
	}
	
	# récupération d'une instance de plxMotor
	$plxMotor = plxMotor::getInstance();
	$plxPlugin = $plxMotor->plxPlugins->getInstance('MyNewsLetter');
	$plugName =basename( __DIR__ );
	$plxPlugin->revoque();
	$plxPlugin->valid();
	# initialisation des variables locales à la page
	$format_date = '#num_day/#num_month/#num_year(4)';
	$method = $plxPlugin->getParam('method') == 'get' ? $_GET : $_POST;
	$blabla = sprintf($plxPlugin->getLang('L_SUBSCRIPTION_INFOS'), $plxMotor->aConf['title']);
	
	# Affichage texte infos abonnement 
	if(isset($_GET['stopNewsLetter' ])) $blabla='';
	if(isset($_GET['validNewsLetter'])) $blabla='';
	
	#traitement formulaire si envoi
	if(!empty($method['courriel'])) {
		$blabla='';
	# enregistrement des abonnements
		# Maj du fichier des abonnés
		$plxPlugin->updateJson();
			
	}
	
	# affichage du formulaire d'abonnement ? 
	if($plxPlugin->getParam('frmDisplay') AND !isset($_GET['stopNewsLetter']) ) {
		MyNewsLetter::form(true);
	}
	
	#Affichages infos générales
 	echo $blabla;
	
	
?>