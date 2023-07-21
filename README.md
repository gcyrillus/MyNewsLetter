<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />Ce(tte) œuvre est mise à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Licence Creative Commons Attribution -  Partage dans les Mêmes Conditions 4.0 International</a>.
# MyNewsLetter
systeme automatisé de newsletter pour pluxml - sans pistage - adresse mails cryptées

Un sujet sur le forum de PluXml est ouvert pour les discussions : https://forum.pluxml.org/discussion/7475/plugin-mynewsletter-systeme-de-newsletter-automatise-ou-non-adresse-mails-cryptees

<h1>Aide et description du Plugin</h1>
<div id="plxmnl">
	<h2>Pr&eacute;ambule</h2>
	<p>Ce plugin gratuit , construit sur la fonction <code>mail()</code> de PHP, ne requiert aucun service tiers pour fonctionner.</p>
	<p>Les adresses mails r&eacute;colt&eacute;es pour les abonnements sont <strong>sous votre seul responsabilit&eacute;</strong>, crypt&eacute;es et stock&eacute;es dans le r&eacute;pertoire du plugin. (supprimer le plugin supprime &eacute;galement tous les abonnement de fa&ccedil;on irr&eacute;versible).</p>
	<p>Les adresses mails crypt&eacute;es ne sont compatibles qu'avec votre installation du plugin. elles sont illisibles et inutilisables sur une autre installation du plugin ou tout autre outil.</p>
	<p><strong>Il n'y a pas de decrypteur fourni avec le plugin pour les rendre lisibles</strong>, <u>il n'est pas pr&eacute;vu de pouvoir les partager</u> avec un tiers ou un autre plugin.</p>
	<p>Ce plugin d&eacute;pend de la fonction <code>mail()</code> de votre h&eacute;bergement, si celle-ci est inop&eacute;rante ou n&eacute;cessite une configuration particuli&egrave;re, cela ne d&eacute;pend pas du plugin. Dans ce cas, il faut s'adresser &agrave; votre h&eacute;bergeur pour l'activer ou suivre les consignes de configurations de celui-ci. Si ni l'un ni l'autre ne fonctionnent, il faudra vous tourner vers un service ext&eacute;rieur si une newsletter est un besoin imp&eacute;ratif pour votre site, et ce, quelque soit le CMS utilis&eacute;.</p>
	<p>Le plugin n'est pas destiné à faire de la prospection ni de develloppé un plan de marketing. Son usage est d'informé vos abonnés des nouvelles publications sur la période que vous choissisez.</p>
	<h3>RGPD</h3>
	<p>Ce plugin gratuit reduit au minimum les informations personnelles et crypte celle-ci pour eviter toute divulgation malencontreuses.</p>
	<p><strong>Seule, l'adresse mail est <em>r&eacute;colt&eacute;e</em> et crypt&eacute;e</strong> Elle est n&eacute;cessaire pour envoyer un courriel!, aucune autre information personnelle pouvant identifier d'une maniere quelconque un abonn&eacute; est enregistr&eacute;e.</p>
	<p><strong>Un abonnement requiert une action de l'abonn&eacute; et son accord</strong> , soit en cochant la case <em>autorisant l'envoi de la Newsletter sur le mail indiqu&eacute; dans le formulaire</em>, soit en cliquant sur le mail de confirmation de son abonnement qu'il re&ccedil;oit. Sans accord actionn&eacute; par l'abonn&eacute;, aucune Newsletter ne sera envoy&eacute;e.</p>
	<p>Un abonnement non valid&eacute; est automatiquement effacer de fa&ccedil;on irr&eacute;versible. Le lien de d&eacute;sabonnement, fourni dans chaque mail, &agrave; le m&ecirc;me effet. L'effacement d'un abonnement ne g&eacute;n&egrave;re aucun mail, seul un message vous indique dans la page que vous n'&ecirc;tes plus abonn&eacute;.</p>
	<p>Les donn&eacute;es correspondant &agrave; un abonn&eacute; sont:</p>
	<ol>
		<li>L'adresse mail crypt&eacute;e. Elle est illisible et uniquement utilisable sur le site ou le plugin a &eacute;t&eacute; activ&eacute;, elle ne peut pas &ecirc;tre partager entre plusieurs installation.</li>
		<li>La date d'abonnement (mois-ann&eacute;e)</li>
		<li>La date du dernier envoi d'une news (mois-ann&eacute;e)</li>
		<li>L&rsquo;&eacute;tat de validation par l'abonn&eacute; (0-1)Si la valeur est &agrave; 0, il n'y a pas d'envoi et l'abonnement est effac&eacute; si il &agrave; plus de 6 mois).</li>
	</ol>
	<h3>des statistiques</h3>
	<p>Malgr&eacute; cela, le plugin vous fournis quelques informations , celles ci sont anonymes et se bornent &agrave; un comptage</p>
	<ol>
		<li>Nombre d'abonn&eacute;s</li>
		<li>Nombre d'abonnement valid&eacute;</li>
		<li>Nombre de d&eacute;sabonnement</li>
		<li>Nombre d'abonnement obsol&eacute;tes effac&eacute;s par le script</li>
		<li>Nombre de nouvelle envoy&eacute;e</li>
		<li>Nombre de lien retour depuis une nouvelle</li>
	</ol>
	<p>Ces quelques informations restent &agrave; votre libre interpr&eacute;tation.</p>
	<h2>Fonctionnement et caract&eacute;ristiques</h2>
	<h3>Mailing</h3>
	<p><b>Une nouvelle publication dans la périodicité choisi doit exister pour rendre possible l'envoi d'une newsletter.</b></p>
	<p>Une seule newsLetter peut-être envoyé à un abonné pour la période configurée.</p>
	<h3>Caract&eacute;ristiques</h3>
	<ul style="list-style-type: square;">
		<li style="font-weight: bold;"><strong>compatible &agrave; partir de php7</strong> <small>une autre branche avec version patché est dispo pour php5x/free https://github.com/gcyrillus/MyNewsLetter/tree/patch-include-PHP5-compatibility </small></li>
		<li>Vos visiteurs peuvent s'abonner &agrave; la newsletter de votre site via un petit formulaire, depuis l'endroit de votre choix sur le site.</li>
		<li>Si votre th&egrave;me utilise le formulaire de commentaires du th&egrave;me par d&eacute;faut, une case &agrave; cocher s'affiche sous le champ de l'email.</li>
		<li>Les adresses mails sont crypt&eacute;es, la date d'inscription et d'envoi de la derni&egrave;re news sont associ&eacute;s &agrave; chaque abonnement.</li>
		<li><strong><em>Votre base de donn&eacute;es d'abonn&eacute;s n'est compatible qu'avec votre installation</em></strong>. Crypt&eacute;e elle n'est pas con&ccedil;ue pour &ecirc;tre copi&eacute;e et partag&eacute;e.</li>
		<li>Le plugin compte les abonn&eacute;s, les abonnement valid&eacute;s, le nombre de courriels envoy&eacute;s ainsi que le nombre de d&eacute;sistements.</li>
		<li>Les news peuvent &ecirc;tre envoy&eacute; de mani&egrave;re automatique tous les X mois si il y a de nouvelles publications depuis le derniers envoi.</li>
		<li>Il est possible de valider les envois en manuel
			<ul>
				<li>Cela permet de v&eacute;rifier, et d'&eacute;diter les contenus</li>
				<li>sans forc&eacute;ment partager les derni&egrave;res publications, vous pouvez r&eacute;diger votre newsletter &agrave; votre convenance.</li>
			</ul>
		</li>
		<li>Les envois de mail se font par lots(configurable) et sont d&eacute;clench&eacute; par vos visiteurs ... (selon configuration).</li>
		<li>Les jours et heures d'envois des news est aussi configurable.</li>
		<li>Le contenu&nbsp; est configurable et il n'est pas possible d'envoyer une news sans qu'il y ai de nouvelles publications.</li>
		<li>Il n'est pas possible d'envoyer &agrave; un abonn&eacute; plusieurs news sur un m&ecirc;me mois/période.</li>
		<li>La newsletter est construite avec des tableaux HTML a partir de plusieurs fichiers <em>template</em>, dans l'esprit PluXml. Il n'y a actuellement pas de th&egrave;mes propos&eacute;s autre que le template de base. L&rsquo;&eacute;diteur vous permet cependant de r&eacute;&eacute;crire et modifier la Newsletter g&eacute;n&eacute;r&eacute;e.
			<p>&nbsp;</p>
		</li>
	</ul>
	<h3>Fonctionnement</h3>
	<p>Les mails de confirmation ou de demande de confirmation des abonnements sont automatiques.</p>
	<p>Un mail non valid&eacute; de plus de 6 mois est effac&eacute; automatiquement au moment de l'envoi des news.Le fichier d&rsquo;abonnement se nettoie seul.</p>
	<p>Ce sont vos visiteurs qui d&eacute;clenchent les envois de mail par lots. L'envoi des lots est limit&eacute; &agrave; une minute d'intervalle. La configuration par d&eacute;faut est de "un" envoi par lot.</p>
	<p>&nbsp;</p>
	<h2>Description</h2>
	<p>Le plugin MyNewsLetter, comme son nom l'indique permet d'envoyer les actualit&eacute;s de votre site &agrave; vos abonn&eacute;s. De fa&ccedil;on automatique ou manuelle.</p>
	<p>Quelques informations anonymes sont enregistr&eacute;s, comme le nombre d'abonnements ou retours sur envois.</p>
	<p><strong>Une version de PHP7 au minimum est requise pour son fonctionnement.</strong><span style="font-size: 8pt;"> (Pour les h&eacute;bergements Free voir la branche patchée https://github.com/gcyrillus/MyNewsLetter/tree/patch-include-PHP5-compatibility )</span><strong><br></strong></p>
	<p>Les abonnements sont enregistr&eacute;s dans un r&eacute;pertoire et fichier au nom al&eacute;atoire cr&eacute;&eacute; &agrave; la premi&egrave;re activation, Les mails des abonn&eacute;s sont crypt&eacute;s.</p>
	<p><strong>Chaque installation du plugin est unique et le fichier des abonn&eacute;s <u>ne sera pas compatible avec une autre installation</u>.</strong></p>
	<h3>Cot&eacute; visiteurs</h3>
	<p>Une page newsletter peut-&ecirc;tre affich&eacute;e avec son formulaire. La soumission du formulaire vous renvoi sur cette page, ainsi que la validation ou l'annulation des abonnements des visiteurs</p>
	<p>Vous pouvez ajout&eacute; un formulaire pour proposer &agrave; vos visiteurs d'&ecirc;tre inform&eacute; des nouveaut&eacute;s de votre site sur toutes les pages &agrave; partir de votre th&egrave;me.</p>
	<h3>cot&eacute; administration</h3>
	<p>Une page Administration et une page Configuration sont disponibles</p>
	<p>Chacune de ces deux pages ont un lien vers l'autre.</p>
	<p>Plusieurs &eacute;l&eacute;ments permanents de la newsletter sont &eacute;ditables et peuvent &ecirc;tre omis ou positionn&eacute;s &agrave; diff&eacute;rents endroits.</p>
	<p>La newsletter g&eacute;n&eacute;r&eacute;e est aussi &eacute;ditable dans son int&eacute;gralit&eacute;, dans ce cas optez d'abord pour un envoi manuel afin qu'elle ne parte qu&rsquo;apr&egrave;s avoir &eacute;t&eacute; &eacute;dit&eacute;e et valid&eacute;e par vos soins.</p>
	<p><b>L'&eacute;diteur embarqu&eacute; depuis le <i>cloud</i > est <u>tinyMce 6</u> avec son pack de langue en fran&ccedil;ais(pack stocké en local").</b>. </p>
	<h4>La page configuration</h4>
	<p>Page accessible &agrave; partir de la liste des plugins</p>
	<p>Vous pouvez configurez</p>
	<ol>
		<li>L'adresse mail d'envoi, le nom de l&rsquo;exp&eacute;diteur et l'objet du courriel</li>
		<li>L'affichage d'une page Newsletter comme page statique.</li>
		<li>Personnalis&eacute; au minimum les inputs de votre formulaire</li>
		<li>Choisir les &eacute;l&eacute;ments &agrave; incorporer &agrave; votre Newsletter</li>
		<li>&eacute;diter les &eacute;l&eacute;ments permanents</li>
		<li>Choisir les jours et horaires d'envoi des newsletters</li>
		<li>Automatis&eacute; l'envoi des newsletters</li>
		<li>etc.</li>
	</ol>
	<h4>La page Administration</h4>
	<p>Cette page est accessible directement depuis le menu dans l'administration, par d&eacute;faut , c'est l'onglet MyNewsLetter.</p>
	<p>Vous y retrouverez :</p>
	<ol>
		<li>le nombre d'abonnement, d&eacute;sistement,news envoy&eacute;es, etc</li>
		<li>Le r&eacute;capitulatif de la configuration</li>
		<li>L'aper&ccedil;u de votre newsletter et la possibilit&eacute; de l'&eacute;diter</li>
		<li>L'objet du courriel (modifiable)</li>
		<li>un bouton de validation d'envoi si la configuration d'envoi est en "manuelle".</li>
		<li>un lien vers la page de configuration</li>
	</ol>
	<h2>Aide</h2>
	<h3>Afficher le formulaire d'abonnement</h3>
	<p>Le plugin dispose d'un hook que vous pouvez ins&eacute;rer dans votre th&egrave;me &agrave; l'endroit de votre choix.</p>
	<p>Le hook &agrave; inserer est : <code>&lt;php eval($plxShow-&gt;callHook('MyNewsLetterForm','Abonnement news letter')) ?&gt;</code>.</p>
	<h3>Modifier le formulaire</h3>
	<p>Il y a quelques options vous permettant d&rsquo;am&eacute;liorer le formulaire &agrave; partir de la page 'Configuration'.</p>
	<h4>Donner un titre au formulaire</h4>
	<p>Dans le hook, Le texte 'Abonnement news letter' peut-&ecirc;tre omis ou modifier. Ce texte s'affichera comme un titre dans votre formulaire pour l'identifier.</p>
	<h4>Afficher un texte par d&eacute;faut.</h4>
	<p>Dans le champs d'inscription, il est possible de mettre un texte en exemple (placeholder). Par exemple: <em>MonAdresse@mail.com</em></p>
	<h4>modifier le libelle du bouton</h4>
	<p>Le libell&eacute; du bouton de l'inscription peut-&ecirc;tre modifi&eacute;, par d&eacute;faut il affiche OK.</p>
	<h4>Option d'affichage</h4>
	<p>Ce formulaire peut-&ecirc;tre afficher ou cacher dans la page newsletter.</p>
	<h4>Case &agrave; cocher et formulaire de commentaire.</h4>
	<p>Dans le formulaire de commentaires, <strong>si vous utilis&eacute; le template par d&eacute;faut</strong> il y aura ne case &agrave; cocher pr&eacute;c&eacute;d&eacute;e du texte <em>Abonnez moi &agrave; la Newsletter</em> sous le champ du mail si celui-ci est rempli avec une adresse mail valide.</p>
	<p>Le plugin recherche cette portion de code : <code>&lt;input id="id_mail" name="mail" type="text" size="20" value="" /&gt;</code> et s'y accroche pour ajouter dessous:</p>
	<p style="display: flex; justify-content: center; gap: 2em; max-width: 450px; margin: auto; background: #aed494;">Abonnez-moi à la newsletter <input type="checkbox"></p>
	<h3>Afficher la page Newsletter sur le site</h3>
	<h4>Quand s'affiche t-elle?</h4>
	<p>Le formulaire d'inscription vous renvoi sur celle-ci avec un message correspondant &agrave; l'action transmise.</p>
	<p>En cliquant sur le lien de validation depuis un mail</p>
	<p>En cliquant sur le lien de d&eacute;sabonnement.</p>
	<p>Pour chaque action, un message appropri&eacute; est affich&eacute;</p>
	<h4>Aller directement vers la page.</h4>
	<p>Dans la page de configuration, vous avez l'option d'ajouter le lien de cette page au menu des pages statiques, ainsi que le choix de sa position dans le menu.</p>
	<h2>Migrer et sauvegarder les donn&eacute;es "abonn&eacute;s" du plugins</h2>
	<p>Votre plugin g&eacute;n&egrave;re &agrave; sa premi&egrave;re activation un <strong>r&eacute;pertoire al&eacute;atoire</strong> de 32 lettres et chiffres.</p>
	<p>Le nom de ce r&eacute;pertoire est <strong>votre cl&eacute; de cryptage</strong>, elle s'affiche dans la page de configuration du plugin.</p>
	<p>Exemple de  clé: <b>  7692014e1c7fa0927b32deecf654e9df</b></p>
	<p>Pour sauvegarder les donn&eacute;es d'abonnements de votre plugin, il faudra copier ce <strong>r&eacute;pertoire</strong> et le fichier <strong>activated.php</strong> &agrave; la racine du plugin.</p>
	<p>Les statistiques sont dans le fichier <strong>infosStat.json</strong> si vous souhaitez les r&eacute;cup&eacute;rer aussi.</p>
	<p>En r&eacute;installant ce plugin sur une autre instance de PluXml , ou sur le m&ecirc;me h&eacute;bergement, il vous suffira d'y remettre le fichier&nbsp;<strong>activated.php</strong> et le <strong>r&eacute;pertoire</strong> associ&eacute;. Vos abonn&eacute;s seront &agrave; nouveau l&agrave;.</p>
</div>
