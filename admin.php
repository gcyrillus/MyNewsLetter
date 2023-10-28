<?php if(!defined('PLX_ROOT')) exit; 
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);
	
	plxToken::validateFormToken($_POST);
	
	$plxMotor = plxMotor::getInstance();
	$plxPlugin = $plxMotor->plxPlugins->getInstance('MyNewsLetter');
	$plxPlugin->scanListNews();
	$filename = PLX_ROOT.'plugins/'.basename(__DIR__).'/'.$plxPlugin->subscriptions.'/'.$plxPlugin->subscriptions.'.json';
	$fileStat = PLX_ROOT.'plugins/'.basename(__DIR__).'/'.$plxPlugin->subscriptions.'/infosStat.json';
	$dataSubs=array();	
	$nbSub = 0;
	$valid = 0;
	# y a t il des abonnés et combien d'abonnements validés?
	if($fileexists=file_exists($filename) && filesize($filename) != 0) {
		$data =  json_decode(file_get_contents($filename), true);
		# extractions infos 
		if(sizeof($data)>0) {
			$nbSub =  count($data);
			forEach($data as $k=>$v) {
				if ($v['valid']==1) $valid++;
			} 	
		}		
	}
	# a t-on des stats sur les envois et retour de NewsLetter?
	if($fileexists=file_exists($fileStat)) {
		$stats =  json_decode(file_get_contents($fileStat), true);
		$sent = $stats['sent'];
		$retours = $stats['retour'];
		$defec = $stats['defec'];
		$cleaned = $stats['unset'];
		$date = $stats['lastDate'];
		$lastsent = $stats['lastSent'];
	}
	else {
		touch($fileStat);
		$sent= 0;
		$retours=0;
		$defec=0;;
		$cleaned=0;
		$date= '01-2023';
		$lastsent = '01-2000';
		$stats= array('sent'=> $sent , 'retour' => $retours, 'defec' => $defec , 'unset' => $cleaned, 'lastDate' => $date, 'lastSent' => $lastsent);
		file_put_contents($fileStat, json_encode($stats,true) );
	}
	
	
	# variables de configuration
	$var['from'] = $plxPlugin->getParam('from')						==''  ? $plxPlugin->get_domain() 												: $plxPlugin->getParam('from');
	$var['object'] = $plxPlugin->getParam('object')					=='' ? '[La NewsLetter de '.$plxAdmin->aConf['title'].' du '. date('m-Y').']' 	: $plxPlugin->getParam('object');
	
	# Si l'utilisateur vient de valider une saisie, on la traite
	
	if(!empty($_POST)) {	
		if(isset($_POST['regenerate'])){
			$plxPlugin->buildMail(trim($_POST['duration']));
		}
		
		if(isset($_POST['update'])) {
			$dirTpl=PLX_ROOT.'plugins/'.basename(__DIR__).'/tpl/';
			$plxPlugin->newsDate= date('m-y');
			$mailFile=PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$plxPlugin->newsDate.'.html';
			$updateBodyMail = $_POST['newsletterBody'];
			file_put_contents($mailFile, $updateBodyMail);
		}
		if(isset($_POST['sendValidated'])) {
			$plxPlugin->setParam('sendValidated',date('m-Y'), 'string');
		}
		if(isset($_POST['object'])) {			
			$plxPlugin->setParam('object',trim($_POST['object']), 'cdata');
		}
		
		$plxPlugin->saveParams();
		header('Location: plugin.php?p='.$plugin);
		exit;
	}
	
	# effacer un abonné
	if(isset($_GET['stopNewsLetter'])) {
		$plxPlugin->revoque();
		exit;
	}
	
	# ajout d'un abonné
	if(isset($_GET['addSubscriber'])) {
		$plxPlugin->setParam('method','get','string');
		$plxPlugin->addSubscriber();	
	}
	
	$plxPlugin->checkListNews();	
	$plxPlugin->isItTime();	
	$plxPlugin->catListLinks();
	
	
    # Affichages	
	
	# as t-on validé l'adresse mail d'envoi?
	$warn='';
	if($plxPlugin->getParam('from')=='') $warn=  ' <span class="warning">'.$plxPlugin->getLang('L_CHECK_MAIL_ADDRESS').'</span>';
	echo '<p class="in-action-bar">'.$plxPlugin->getLang('L_PAGE_ADMIN_TITLE'). $warn.'</p>';
	
	
	# formulaire
	
?>
<div id="topAdmin">
	<!-- lien retour vers l'admin -->
	<a href="<?php echo PLX_ROOT ?>core/admin/"   title="<?php $plxPlugin->lang('L_BACK_TO_ADMINISTRATION') ?>"> <?php $plxPlugin->lang('L_BACK_TO_ADMINISTRATION') ?> </a>
	<!-- lien direct vers la page de configuration -->
	<p style="text-align:right;text-align:inline-end;margin-inline-end:1em;"><!-- --> <a href="<?php echo PLX_ROOT ?>core/admin/parametres_plugin.php?p=MyNewsLetter"  title="<?php $plxPlugin->lang('L_GOTO_CONFIG') ?>"><?php $plxPlugin->lang('L_CONFIG') ?></a></p>
	<div id="onglets">
		<form action="plugin.php?p=MyNewsLetter" method="post" id="form_MyNewsLetter">
			
			<div class="onglet split-1-2" data-title="<?php $plxPlugin->lang('L_STATISTICS') ?>">
				<fieldset class="grid-rows">
					<legend><?php $plxPlugin->lang('L_STATISTICS') ?></legend>
					<p><?php $plxPlugin->lang('L_LAST_SEND') ?>: <?php if($lastsent == '01-2000') {$lastsent=$plxPlugin->getLang('L_NEVER');}echo $lastsent ?></p>
					<p><?php $plxPlugin->lang('L_NUMBER_OF_SUBSCRIBERS') ?>: <?php echo $nbSub ?></p>
					<p><?php $plxPlugin->lang('L_NUMBER_OF_UNSUBSCRIBERS') ?>: <?php echo $defec  ?></p>
					<p><?php $plxPlugin->lang('L_VALIDATED_SUBSCRIBERS') ?>: <?php echo $valid ?></p>
					<p><?php $plxPlugin->lang('L_CLEANED_SUBSCRIBERS') ?>: <?php echo $cleaned ?></p>
					<p><?php $plxPlugin->lang('L_NEWS_SENT') ?>: <?php echo $sent ?></p>
					<p><?php $plxPlugin->lang('L_FEEDBACK') ?>: <?php echo $retours ?></p>
				</fieldset>
				<fieldset>
					<legend><?php $plxPlugin->lang('L_INFOS') ?></legend>
					<dl>
						<dt><?php $plxPlugin->lang('L_MAIL_SENDER') ?></dt>
						<dd><?php echo $var['from'] ?></dd>
						<dt><?php $plxPlugin->lang('L_MENTIONS') ?></dt>
						<dd><input type="checkbox" <?php if($plxPlugin->getParam('mention') ==1 ) echo 'checked="checked"';?> disabled></dd>
						<dt><?php $plxPlugin->lang('L_INTRO') ?></dt>
						<dd><input type="checkbox" <?php if($plxPlugin->getParam('intro') ==1 ) echo 'checked="checked"';?> disabled></dd>
						<dt><?php $plxPlugin->lang('L_ADD_LASTART') ?></dt>
						<dd><input type="checkbox" <?php if($plxPlugin->getParam('lastart') ==1 ) echo 'checked="checked"';?> disabled ></dd>
						<dt><?php $plxPlugin->lang('L_FOOTER') ?></dt>
						<dd><input type="checkbox" <?php if($plxPlugin->getParam('footer') ==1 ) echo 'checked="checked"';?> disabled></dd>
						<dt><?php $plxPlugin->lang('L_AUTO_SENDING') ?></dt>
						<dd><input type="checkbox" <?php if($plxPlugin->getParam('paramSend') !=1) echo 'checked="checked"';?>  disabled></dd>
					</dl>
				</fieldset>
			</div>
			
			<div class="onglet" data-title="<?php $plxPlugin->lang('L_VIEW_EDITING') ?>">
				<h4><?php $plxPlugin->lang('L_EDITING') ?></h4>
				<fieldset class="editor">
					<legend><?php $plxPlugin->lang('L_EDITING') ?></legend>
					<div>
						<p><label for="duration"><?php $plxPlugin->lang('L_REGENERATE_FROM') ?></label>
							<select id="duration" name="duration">
								<option value="1">1 <?php $plxPlugin->lang('L_MONTH') ?></option>
								<option value="2">2 <?php $plxPlugin->lang('L_MONTH') ?></option>
								<option value="3">3 <?php $plxPlugin->lang('L_MONTH') ?></option>
								<option value="6">6 <?php $plxPlugin->lang('L_MONTH') ?></option>
							</select>
							<button type="submit" id="regenerate" name="regenerate" class="btn btn-danger" ><?php $plxPlugin->lang('L_REGENERATE') ?></button>
						</p>
						<p>
							<label for="loadOtherNewsFile"><?php $plxPlugin->lang('L_LOAD_ELSE_AND_REPLACE') ?></label>
							<select id="loadOtherNewsFile">
								<?php $plxPlugin->scanNewsSent('option');?>
							</select>
							<?php $plxPlugin->scanNewsSent();?>
							<button type="button" onclick="updateTXT();" class="btn btn-warning d-block m-auto"><?php $plxPlugin->lang('L_LOAD_AND_REPLACE') ?></button>
						</p>
					</div>
					<!-- + la même avec des template ? bah on verra , plutôt choix de thémes cote config peut-être ?et rien de plus ici !-->
					<p><b class="help">?</b> - <?php $plxPlugin->lang('L_HELP_DATE_STOP') ?></p> 
					<div>
						<p>
							<button id="edit" class="btn btn-primary" onclick="editn();" type="button"><?php $plxPlugin->lang('L_EDIT') ?></button>
							<button id="save" class="btn btn-success" onclick="saven()" type="button"><?php $plxPlugin->lang('L_COMMIT_CHANGE') ?></button>
							<button id="cancel" class="btn btn-warning" onclick="canceln()" type="button"><?php $plxPlugin->lang('L_CANCEL') ?></button>
							<button id="saveNews" class="btn btn-danger mx-auto" name="update" type="submit"><?php $plxPlugin->lang('L_SAVE') ?></button>
							<?php echo plxToken::getTokenPostMethod(); ?>
						</p>
						<div id="view" class="click2edit" style="border:solid;width:100%;height:300px;overflow:auto;max-width:610px;margin:auto;"><?php $newsDate= date('m-y'); echo file_get_contents(PLX_PLUGINS.basename(__DIR__).'/ListNews/'.$newsDate.'.html') ; ?></div>
						<textarea id="editNews"  name="newsletterBody" row="10" style="position:absolute;right:100vw"></textarea>
					</div>				
				</fieldset>
			</div>		
			
			<div class="onglet" data-title="Envois">
				<h4><?php $plxPlugin->lang('L_OBJECT') ?></h4>
				<fieldset><legend><?php $plxPlugin->lang('L_MAIL_OBJECT') ?></legend> 
					<p>
						<label><?php $plxPlugin->lang('L_OBJECT_TO_PRINT') ?></label>
						<input type="texte" style="flex:1;font-weight:bold;" name="object" size="60" value="<?php echo $var['object'] ?>">
					</p>
					<p>
						<label></label>
						<input type="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>"> 
					</p>
				</fieldset>
				<?php 
					if($plxPlugin->getParam('paramSend') =='1') { ?>
					<fieldset>
						<legend><?php $plxPlugin->lang('L_SEND') ?></legend>
						<p>
							<label><?php $plxPlugin->lang('L_COMMIT_SEND') ?></label>
							<?php 
								if($plxPlugin->getParam('sendValidated') == date('m-Y')) {
									echo '<input type=submit disabled value="'.$plxPlugin->getLang('L_SEND_VALID').' '.$plxPlugin->getParam('sendValidated').'">';
								}
								else {
									echo '<input type="submit" id="sendValidated" name="sendValidated" value="Lancer les envois">';
								}
							?>
						</p>			
					</fieldset>	
				<?php } ?>
				<fieldset class="editor"><legend>Aperçu</legend><button  class="btn btn-primary" onclick="previewMail()" type="button">rafraichir l'aperçu</button>
					<script>function previewMail(){document.querySelector('#myPreview').innerHTML = document.querySelector('#view').innerHTML;}</script>
					<div class="notouch" id="myPreview"></div>
				</fieldset>
			</div>
			
			<div class="onglet" data-title="Abonnés">
				<?php $plxPlugin->getsubscribers(); ?>
				
			</div>
		</form>
	</div>
	<style>/* page admin news letter */
		#topAdmin {
		position: fixed;
		inset: 0 0 0 0;
		background: #ddd;
		overflow: auto;
		}
		.inline-form.action-bar {
		display: none;
		}
		p.in-action-bar {
		margin: 0;
		}
		fieldset, table#suscribers, legend {
		background: #fff;
		margin: 0.25em 0;
		border-radius: 0.25em;
		box-shadow: 1px 1px 3px #3334;
		} 
		
	</style>
	<!-- tinymce -->
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js" integrity="sha512-sWydClczl0KPyMWlARx1JaxJo2upoMYb9oh5IHwudGfICJ/8qaCyqhNTP5aa9Xx0aCRBwh71eZchgz0a4unoyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/icons/default/icons.min.js" integrity="sha512-iZEjj5ZEdiNAMLCFKlXVZkE0rKZ9xRGFtr0aMi8gxbEl1RbMCbpPomRiKurc93QVFdaxcnduQq6562xxqbC6wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		/*if(typeof(tinyMCE) != "undefined") alert('okay'); else alert('nop');*/
		initTiny('.tinyeditor');
		function initTiny(target) {
			tinymce.init({
				selector: target,  // note the comma at the end of the line!
				browser_spellcheck: true,
				contextmenu: false,
				plugins: [
				'advlist', 'lists', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
				'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
				'insertdatetime', 'media', 'table', 'help', 'wordcount','codemirror'
				],  // note the comma at the end of the line!
				
				toolbar: 'undo redo | blocks | ' +
				'bold italic backcolor forecolor | alignleft aligncenter ' +
				'alignright alignjustify | bullist numlist outdent indent | table ' +
				'removeformat | help code',
				advlist_bullet_styles: 'square',
				advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman',  
			});			
		}
		
		function removeTiny(target) {
			tinymce.remove(target);
		}	
		
	</script>
	<style>.tox-promotion{display:none}/* do not confuse and give funny idea to the user that would bring up an issue to the plugin using it*/</style>
	<?php 			
		echo '<script src="'.PLX_ROOT.'plugins/'.basename(__DIR__).'/js/script.js"></script>'.PHP_EOL;		
		