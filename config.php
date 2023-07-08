<?php if(!defined('PLX_ROOT')) exit; ?>
<?php
	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);
	
	# Si le plugin plxMyMultiLingue est installé on filtre sur les langues utilisées
	# On garde par défaut le fr si aucune langue sélectionnée dans plxMyMultiLingue
	if(defined('PLX_MYMULTILINGUE')) {
		$langs = plxMyMultiLingue::_Langs();
		$multiLangs = empty($langs) ? array() : explode(',', $langs);
		$aLangs = $multiLangs;
	}
	$week = array(L_ALL,L_MONDAY,L_TUESDAY,L_WEDNESDAY,L_THURSDAY,L_FRIDAY,L_SATURDAY,L_SUNDAY);
	if(!empty($_POST)) {
		$plxPlugin->setParam('frmDisplay', $_POST['frmDisplay'], 'numeric');
		$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', $_POST['template'], 'string');
		$plxPlugin->setParam('frequency', $_POST['frequency'], 'numeric');
		$plxPlugin->setParam('url', 'NewsLetter', 'string');
		$plxPlugin->setParam('method', 'post', 'string');	
		$plxPlugin->setParam('mnuName', $_POST['mnuName'], 'string');
		$plxPlugin->setParam('placeholder', $_POST['placeholder'], 'string');
		$plxPlugin->setParam('frmLibButton', $_POST['frmLibButton'], 'string');
		$plxPlugin->setParam('day1', $_POST['day1'], 'numeric');
		$plxPlugin->setParam('day2', $_POST['day2'], 'numeric');
		$plxPlugin->setParam('hour1', $_POST['hour1'], 'numeric');
		$plxPlugin->setParam('hour2', $_POST['hour2'], 'numeric');
		$plxPlugin->setParam('paramSend', $_POST['paramSend'], 'numeric');
		$plxPlugin->setParam('from', $_POST['from'], 'string');
		$plxPlugin->setParam('who', $_POST['who'], 'string');
		$plxPlugin->setParam('object', $_POST['object'], 'string');
		$plxPlugin->setParam('lots', $_POST['lots'], 'numeric');
		if(isset($_POST['mention'])) {
			$plxPlugin->setParam('mention', 1 , 'numeric');
			$showMention=1;
		}
		else {
			$plxPlugin->setParam('mention', 0 , 'numeric');
			$showMention=0;
		}
		if(isset($_POST['footer'])) {
			$plxPlugin->setParam('footer', 1 , 'numeric');
		}
		else {
			$plxPlugin->setParam('footer', 0 , 'numeric');
		}
		
		$plxPlugin->setParam('intro', $_POST['intro'], 'numeric');
		$plxPlugin->setParam('posMention', $_POST['posMention'], 'numeric');
		if(isset($_POST['lastart'])) {
			$plxPlugin->setParam('lastart', 1 , 'numeric');
		}
		else {
			$plxPlugin->setParam('lastart', 0 , 'numeric');
		}
		if(isset($_POST['site_logo'])) {
			
			
		}
		$plxPlugin->setParam('content_intro'  , $_POST['content_intro']  , 'cdata');
		$plxPlugin->setParam('content_mention', $_POST['content_mention'], 'cdata');
		$plxPlugin->setParam('content_footer', $_POST['content_footer'] , 'cdata');
		
		
		
		
		
		
		$plxPlugin->saveParams();
		if(is_file(PLX_ROOT.'.htaccess')) {
			$f = file_get_contents(PLX_ROOT.'.htaccess');
			$f = str_replace('[L]', '[QSA,L]', $f);
			plxUtils::write($f, PLX_ROOT.'.htaccess');
		}
		
		# ordre et structure de la news :
		$templateArray= array('','NewsHeader.php','','','','','');
		if($plxPlugin->getParam('mention') == 1 ) {
			$templateArray[$plxPlugin->getParam('posMention')] = 'mention.php';		
		}
		if($plxPlugin->getParam('intro') == 1) {
			$templateArray[3] = 'newsIntro.php';
		}
		if($plxPlugin->getParam('lastart')	==1) {
			$templateArray[4] = 'newsArts.php';		
		}
		if($plxPlugin->getParam('footer')	==1) {
			$templateArray[5] = 'newsfooter.php';		
		}
		$templateArray = implode(',',$templateArray);
		$plxPlugin->setParam('elements', $templateArray , 'cdata');
		
		$plxPlugin->saveParams();		
		

		
		
		header('Location: parametres_plugin.php?p=MyNewsLetter');
		exit;
	}
	
	#as t-on une news à afficher ?
	$plxPlugin->checkListNews();	
	
	
	
	
	# initialisation des variables 
	$var = array();
	
	
	if(!$plxPlugin->subscriptions) $plxPlugin->subscriptions=' Plugin jamais activé - aucun numéro attribué';

	
	$var['mnuName'] =  $plxPlugin->getParam('mnuName'	)	=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') 				: $plxPlugin->getParam('mnuName');
	$var['placeholder'] = $plxPlugin->getParam('placeholder')	=='' ? ''									: $plxPlugin->getParam('placeholder');
	$var['frmLibButton'] =  $plxPlugin->getParam('frmLibButton')	=='' ? $plxPlugin->getLang('L_FORM_BUTTON') 					: $plxPlugin->getParam('frmLibButton');
	$var['frmDisplay'] =  $plxPlugin->getParam('frmDisplay')	=='' ? 1 									: $plxPlugin->getParam('frmDisplay');
	$var['mnuDisplay'] =  $plxPlugin->getParam('mnuDisplay')	=='' ? 1 									: $plxPlugin->getParam('mnuDisplay');
	$var['mnuPos'] =  $plxPlugin->getParam('mnuPos')		=='' ? 2 									: $plxPlugin->getParam('mnuPos');
	$var['template'] = $plxPlugin->getParam('template')		=='' ? 'static.php' 								: $plxPlugin->getParam('template');
	$var['frequency'] = $plxPlugin->getParam('frequency')		=='' ? '3' 									: $plxPlugin->getParam('frequency');
	$var['url'] = $plxPlugin->getParam('url')			=='' ? 'NewsLetter' 								: $plxPlugin->getParam('url');
	$var['method'] =  $plxPlugin->getParam('method')		=='' ? 'post' 									: $plxPlugin->getParam('method');
	$var['day1'] = $plxPlugin->getParam('day1')			=='' ? '1' 									: $plxPlugin->getParam('day1');
	$var['day2'] = $plxPlugin->getParam('day2')			=='' ? '7' 									: $plxPlugin->getParam('day2');
	$var['hour1'] = $plxPlugin->getParam('hour1')			=='' ? '08' 									: $plxPlugin->getParam('hour1');
	$var['hour2'] = $plxPlugin->getParam('hour1')			=='' ? '20'									: $plxPlugin->getParam('hour2');
	$var['paramSend'] = $plxPlugin->getParam('paramSend')		=='' ? '2'									: $plxPlugin->getParam('paramSend');
	$var['lots'] = $plxPlugin->getParam('lots')			=='' ? '5'									: $plxPlugin->getParam('lots');
	$var['from'] = $plxPlugin->getParam('from')			=='' ? $plxPlugin->get_domain() 						: $plxPlugin->getParam('from');
	$var['who'] = $plxPlugin->getParam('who')			=='' ? $plxAdmin->aUsers['001']['name'] 					: $plxPlugin->getParam('who');
	$var['object'] = $plxPlugin->getParam('object')			=='' ? '[La NewsLetter de '.$plxAdmin->aConf['title'].' du '. date('m-Y').']' 	: $plxPlugin->getParam('object');
	$var['mention'] = $plxPlugin->getParam('mention')		=='' ? 1 									: $plxPlugin->getParam('mention');
	$var['footer'] = $plxPlugin->getParam('footer')			=='' ? 1 									: $plxPlugin->getParam('footer');
	$var['logo'] = $plxPlugin->getParam('logo')			=='' ? 0 									: $plxPlugin->getParam('logo');
	$var['intro'] = $plxPlugin->getParam('intro')			=='' ? 0 									: $plxPlugin->getParam('intro');
	$var['posMention'] = $plxPlugin->getParam('posMention')		=='' ? 0 									: $plxPlugin->getParam('posMention');
	$var['lastart'] = $plxPlugin->getParam('lastart')		=='' ? 1 									: $plxPlugin->getParam('lastart');
	$var['content_intro']= $plxPlugin->getParam('content_intro')	=='' ? $plxPlugin->getLang('L_CONTENT_INTRO')					: $plxPlugin->getParam('content_intro');
	$var['content_mention']= $plxPlugin->getParam('content_mention')=='' ? $plxPlugin->getLang('L_MENTIONS_HTML')					: $plxPlugin->getParam('content_mention');
	$var['content_footer'] = $plxPlugin->getParam('content_footer')	=='' ? $plxPlugin->getLang('L_FOOTER_HTML') 					: $plxPlugin->getParam('content_footer');
	
	
	
	
	
	
	
	# On récupère les templates des pages statiques
	$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style']);
	if ($array = $files->query('/^static(-[a-z0-9-_]+)?.php$/')) {
		foreach($array as $k=>$v)
		$aTemplates[$v] = $v;
	}
	
	
?>
<p><a href="<?php echo PLX_ROOT ?>core/admin/plugin.php?p=MyNewsLetter" style="float:inline-end" title="<?php $plxPlugin->lang('L_GOTO_ADMIN') ?>"><?php $plxPlugin->lang('L_ADMIN') ?></a></p>
<h3><?php $plxPlugin->lang('L_CONFIG') ?></h3>
<div id="onglets">
	<form  id="form_MyNewsLetter" action="parametres_plugin.php?p=MyNewsLetter" method="post" class="config">
		
		
		<div class="onglet" data-title="Configurations">
			<h4>Mail</h4>
			<fieldset>
				<legend>identification expediteur</legend>
				<p>
					<label for="who"><?php $plxPlugin->lang('L_NAME_SENDER') ?>&nbsp;:</label>
					<?php plxUtils::printInput('who',$var['who'],'text','25-25') ?>
				</p>
				<p>
					<label for="from"><?php $plxPlugin->lang('L_CONFIG_MAIL_ADDRESS') ?>&nbsp;:</label>
					<?php plxUtils::printInput('from',$var['from'],'text','30-30') ?>
				</p>
				<p>
					<label for="from"><?php $plxPlugin->lang('L_MAIL_OBJECT') ?>&nbsp;:</label>
					<?php plxUtils::printInput('object',$var['object'],'text','60-60') ?>
				</p>
			</fieldset>
			<h4><?php $plxPlugin->lang('L_CONFIG_STATIC') ?></h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_CONFIG_MENU') ?></legend>
				<p>
					<label for="id_mnuDisplay"><?php echo $plxPlugin->lang('L_MENU_DISPLAY') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('mnuDisplay',array('1'=>L_YES,'0'=>L_NO),$var['mnuDisplay']); ?>
				</p>
				<p>
					<label for="id_mnuName_<?php echo $lang ?>"><?php $plxPlugin->lang('L_MENU_TITLE') ?>&nbsp;:</label>
					<?php plxUtils::printInput('mnuName',$var['mnuName'],'text','20-20') ?>
				</p>
				<p>
					<label for="id_mnuPos"><?php $plxPlugin->lang('L_MENU_POS') ?>&nbsp;:</label>
					<?php plxUtils::printInput('mnuPos',$var['mnuPos'],'text','2-5') ?>
				</p>
				<p>
					<label for="id_url"><?php $plxPlugin->lang('L_PARAM_URL') ?>&nbsp;:</label>
					<?php plxUtils::printInput('url',$var['url'],'text','20-20',$readonly=true) ?>
				</p>
			</fieldset>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_CONFIG_SHOW') ?></legend>
				<p>
					<label for="id_frmDisplay"><?php echo $plxPlugin->lang('L_FORM_DISPLAY') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('frmDisplay',array('1'=>L_YES,'0'=>L_NO),$var['frmDisplay']); ?>
				</p>
				<p>
					<label for="id_template"><?php $plxPlugin->lang('L_TEMPLATE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('template', $aTemplates, $var['template']) ?>
				</p>
				<p>
					<label for="id_placeholder_<?php echo $lang ?>"><?php $plxPlugin->lang('L_PLACEHOLDER') ?>&nbsp;:</label>
					<?php plxUtils::printInput('placeholder',$var['placeholder'],'text','20-20') ?>
				</p>
				<p>
					<label for="id_frmLibButton_<?php echo $lang ?>"><?php $plxPlugin->lang('L_MENU_LIB_BUTTON') ?>&nbsp;:</label>
					<?php plxUtils::printInput('frmLibButton',$var['frmLibButton'],'text','20-20') ?>
				</p>
			</fieldset>
		</div>		
		
		
		<div class="onglet" data-title="<?php $plxPlugin->lang('L_COMPOSITION') ?>">
			<fieldset class="flex gapH2">
				<legend><?php $plxPlugin->lang('L_ELEMENTS') ?></legend>
				<div class="border1">
					<p>
						<label for="mention"><?php $plxPlugin->lang('L_SHOW_MENTION') ?>&nbsp;:</label>
						<input type="checkbox" <?php if($var['mention'] ==1 ) echo 'checked="checked"';?> id="mention" name="mention">
					</p>
					<p>
						<label for="posMention"><?php echo $plxPlugin->lang('L_POS_MENTION') ?>&nbsp;:</label>
						<?php plxUtils::printSelect('posMention',array('0'=> $plxPlugin->getLang('L_TOP'), '2'=> $plxPlugin->getLang('L_BOT_TITLE'),'6'=> $plxPlugin->getLang('L_BOTTOM')), $var['posMention']); ?>
					</p>
				</div>
				<!--<div>
					<p>
					<label for="site_logo"><?php echo $plxPlugin->lang('L_SITE_LOGO') ?>&nbsp;:</label>
					<input id="site_logo" type="file" multiple="multiple" name="site_logo" accept="image/*" />
					</p>
					<p>
					<label for="logo"><?php echo $plxPlugin->lang('L_INSERT_LOGO') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('logo',array('1'=>L_YES,'0'=>L_NO),$var['logo']); ?>
					</p>
					
				</div>-->
				<div>
					<p>
						<label for="intro"><?php echo $plxPlugin->lang('L_INTRO_DISPLAY') ?>&nbsp;:</label>
						<?php plxUtils::printSelect('intro',array('1'=>L_YES,'0'=>L_NO),$var['intro']); ?>
					</p>
					<p>
						<label for="footer"><?php $plxPlugin->lang('L_ADD_LASTART') ?>&nbsp;:</label>
						<input type="checkbox" <?php if($var['lastart'] ==1 ) echo 'checked="checked"';?> id="lastart" name="lastart">
					</p>
					<p>
						<label for="footer"><?php $plxPlugin->lang('L_SHOW_FOOTER') ?>&nbsp;:</label>
						<input type="checkbox" <?php if($var['footer'] ==1 ) echo 'checked="checked"';?> id="footer" name="footer">
					</p>
					
					
				</div>
			</fieldset>
			<fieldset class="editor">
				<legend><?php $plxPlugin->lang('L_PERMANENT_ELEMENT') ?></legend>	
				<p class="warning"><b class="help">?</b> - <?php $plxPlugin->lang('L_HELP_DATE_STOP') ?></p>
				<label for="content_intro"><?php $plxPlugin->lang('L_INTRO') ?>&nbsp;:</label>
				<textarea class="tinyeditor" id="content_intro" name="content_intro"><?php echo $var['content_intro'] ?></textarea>
				
				<label for="content_mention"><?php $plxPlugin->lang('L_MENTION') ?>&nbsp;:</label>
				<textarea class="tinyeditor" id="content_mention" name="content_mention"><?php echo $var['content_mention'] ?></textarea>
				
				<label for="content_footer"><?php $plxPlugin->lang('L_FOOTER') ?>&nbsp;:</label>
				<textarea class="tinyeditor" id="content_footer" name="content_footer"><?php echo $var['content_footer'] ?></textarea>
			</fieldset>
			
		</div> 		
		
		<div class="onglet" data-title="<?php $plxPlugin->lang('L_CONFIG_NEWS') ?>">
			<h4><?php $plxPlugin->lang('L_CONFIG_NEWS') ?></h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_PARAM_AUTO') ?></legend>
				<p>
					<label for="lots"><?php $plxPlugin->lang('L_BATCH_SENDINGS') ?>&nbsp;:</label>
					<input type="number" id="lots" name="lots" value="<?php echo $var['lots'] ?>">
				</p>
				
				
				<p>
					<label for="frequency"><?php echo $plxPlugin->lang('L_FREQUENCE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('frequency',array('1'=> $plxPlugin->getLang('L_MONTHLY'), '3'=> $plxPlugin->getLang('L_QUATERLY'),'6'=> $plxPlugin->getLang('L_BI_ANNUAL')), $var['frequency']); ?>
				</p>
				<p></p>
				<p class="warn"><label for="idBlind"><?php $plxPlugin->lang('L_AUTO_BLIND') ?></label><input type="radio" name="paramSend" id="idBlind" value="0" <?php if($var['paramSend']==0) echo 'checked=checked' ?>> </p>
				<p class="fine"><label for="idNotify"><?php $plxPlugin->lang('L_AUTO_NOTIFY') ?></label><input type="radio" name="paramSend" id="idNotify" value="1" <?php if($var['paramSend']==1) echo 'checked=checked' ?>></p>			
			</fieldset>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_PARAM_WHEN') ?></legend>
				<p> 
					<label for="id_day1"><?php $plxPlugin->lang('L_PARAM_DAY_1_2') ?> &nbsp;:</label>
					<?php plxUtils::printSelect('day1',$week,$var['day1']); ?>
					<?php $plxPlugin->lang('L_AND') ?>					
					<?php plxUtils::printSelect('day2',$week,$var['day2']); ?>
				</p>
				<p> 
					<label for="id_day1"><?php $plxPlugin->lang('L_PARAM_HOUR_1_2') ?>&nbsp;:</label>
					<?php plxUtils::printInput('hour1',$var['hour1'],'text','2-3') ?>
					<?php $plxPlugin->lang('L_AND') ?>
					<?php plxUtils::printInput('hour2',$var['hour2'],'text','2-3') ?>H
				</p>
			</fieldset>
		</div>
		<p class="in-action-bar">
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
			<i>N°<?php echo $plxPlugin->subscriptions; ?></i>
			<?php if($plxPlugin->getParam('from')=='') echo '<span class="warning">'.$plxPlugin->getLang('L_CHECK_MAIL_ADDRESS').'</span>'; ?>
		</p>
	</form>
</div>
<!-- tinymce -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js" integrity="sha512-sWydClczl0KPyMWlARx1JaxJo2upoMYb9oh5IHwudGfICJ/8qaCyqhNTP5aa9Xx0aCRBwh71eZchgz0a4unoyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/icons/default/icons.min.js" integrity="sha512-iZEjj5ZEdiNAMLCFKlXVZkE0rKZ9xRGFtr0aMi8gxbEl1RbMCbpPomRiKurc93QVFdaxcnduQq6562xxqbC6wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php 			echo '<script src="'.PLX_ROOT.'plugins/'.basename(__DIR__).'/js/script.js"></script>'."\n"; ?>
<script>
	/*if(typeof(tinyMCE) != "undefined") alert('okay');
	else alert('nop');*/
	initTiny('.tinyeditor');
	
	
	
	
</script>
<style>.tox-promotion{display:none}/* do not confuse and give funny idea to the user that would bring up an issue to the plugin using it*/</style>

