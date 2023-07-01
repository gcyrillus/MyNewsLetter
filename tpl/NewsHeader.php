<?php
	$this->bodyNews .= PHP_EOL.'						<tr><!--newsHeader-->
							<td align="center" style="padding:40px 0 30px 0;text-align:center;">
								<img src="'.$plxMotor->urlRewrite('data/medias/corporate/logo.png').'" alt="'.$plxMotor->aConf['title'].'" width="200" style="height:auto;vertical-align:middle" />
								<span style="display:inline-block;text-align:left;vertical-align:middle;padding:1em;">
									<a href="'.$plxMotor->urlRewrite( '?&news='.date('m-Y') ).'" style="font-size:1.25em;">'.$plxMotor->aConf['title'].'</a><br>
									<b style="font-size:1.75em">NEWSLETTER</b><br>
									du '.plxDate::formatDate(date('Ymd') ,'#day #num_day #month #num_year(4)').'<br>
									N° FR-'.$this->newsDate.'								
								</span>
							</td>
						</tr>';