<?php
	# traite les png du dossier courant et fait une copie avec le blanc changé en transparent.
	foreach (glob("*.png") as $filename) {	
		$img = imagecreatefrompng($filename); 
		$white = imagecolorallocate($img, 255,255,255);
		// le blanc devient transparent
		imagecolortransparent($img, $white); 
		# Creation d'une copie avec transparence de l'image
		imagepng($img, 'transparent-'.$filename);
	}
?>