<?php
	#init quel jour, mois, ... année?
	$d =  date('d');
	$m =  date('m');
	$y =  date('Y');
	
	#tri depuis Xmois
	$newdate=date_create($y.$m);
	$newdates= $y.$m;

	// inclure traitement fréquence ou durée en moins
	date_sub($newdate,date_interval_create_from_date_string("0 month"));
	$i=1;
	$grabing='';
	while($i < $do+1) {
		$newdates = date("Ym", strtotime("-$i months", strtotime($newdates)));
		$grabing .= '|'.$newdates;
		$i++;
	}
//	echo  date_format($newdate,"Ym") .$grabing ;
//	exit;
	$fromDate = '('.date_format($newdate,"Ym") .$grabing.')'; 			
	/* test recup art */			
	$plxMotor = plxMotor::getInstance();
	$artList=array();
	# On recupere les derniers articles 
	$grab = '#^\d{4}.(?:\d|home|,)*(?:'.$plxMotor->activeCats.'|home)(?:\d|home|,)*.\d{3}.'.$fromDate.'\d{2}\d{4}.[\w-]+.xml$#';
	
	# On calcule la valeur start
	$start = 0;
	# On recupere nos fichiers (tries) selon le motif, la pagination, la date de publication
	if($aFiles = $plxMotor->plxGlob_arts->query($grab,'art','desc','0','9999','before')) {

		# On analyse tous les fichiers
		$artsList = array();
		foreach($aFiles as $v) {
			$art = $plxMotor->parseArticle(PLX_ROOT . $plxMotor->aConf['racine_articles'] . $v);
			if(!empty($art)) {
				$artsList[] = $art;
			}
		}
		
		if(count($artsList)>0) {
			foreach($artsList as $posted) {
				$this->bodyNews .= PHP_EOL.'
				<tr><!--newsArt-->
					<td style="padding:0;">
						<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
							<tbody>
								<tr>';
					if($posted['thumbnail'] !='') { 

						$this->bodyNews .= PHP_EOL.'								<td style="width:180px;padding:1em 10px;vertical-align:top;color:#153643;">
						<p style="margin: 0;text-indent:1.2em; margin-bottom: 5px; font-size: 16px;">'.plxDate::formatDate($posted['date_creation'],'#day #num_day #month #num_year(4)').'</p>
						<p style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
						<img src="'.$plxMotor->urlRewrite($this->saveToPng(PLX_ROOT.$posted['thumbnail'])).'" alt="" width="180" style="height:auto;display:block;" />
						</p>
						</td>';
					} 
					$this->bodyNews .= PHP_EOL.'								<td style="padding:1em 2em 0 ;vertical-align:top;color:#153643;">'.PHP_EOL;
					if($posted['thumbnail'] =='') { $this->bodyNews .='<p style="margin: 0; margin-bottom: 5px; font-size: 16px;">'.plxDate::formatDate($posted['date'],'#day #num_day #month #num_year(4)').'</p>';}
					$this->bodyNews .= PHP_EOL.'					<h2 style="text-indent:-.35em;color :#2e58ff;">'.$posted['title'].'</h2>
						<div>'. $posted['chapo'].'</div>
						<p style="margin:0.25em;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:right;"><a href="'.$plxMotor->urlRewrite( '?article'.intval($posted['numero']).'/'.$posted['url'] ) .'&news='.date('m-Y').'" style="display: inline-block; background: #60B4CC; color: white; font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: normal; line-height: 24px; margin: 0; text-decoration: none; text-transform: none; padding: 10px 25px; mso-padding-alt: 0px; border-radius: 3px;" target="_blank" rel="nofollow">Lire : '.$posted['title'].'</a></p>
									</td>
								</tr>
							</tbody>
						</table>								
					</td>
				</tr>';	
			}	
		}				
	}				