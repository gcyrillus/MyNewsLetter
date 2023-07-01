<?php
$LANG = array(
	'L_PAGE_TITLE'				=> 'NewsLetter',
	'L_PAGE_ADMIN_TITLE'		=> 'Plugin Administration',
	'L_BACK_TO_ADMINISTRATION'	=> 'Back to administration',
    'L_THERE_IS_NEWS_TO_SEND'   => 'there is news to send from %s',
    'L_NEVER'                   => 'Never',
    
    # config.php
	'L_CONFIG'					=> 'Setup Page',
	'L_GOTO_ADMIN'				=> 'Go to the administration page',
	'L_ASK_CONFIRM'				=> 'Validation Subscriptions',
	'L_REQUIRE_CONFIRM'			=> 'Send an email with a validation link.',
	'L_CONFIG_STATIC'			=> 'Visitors display',
	'L_CONFIG_MENU'				=> 'Menu display',
	'L_CONFIG_NEWS'				=> 'Send Settings',
	'L_CONFIG_SHOW'				=> 'Display Options',
	'L_PARAM_WHEN'				=> 'Shipping times and days',
	'L_PARAM_AUTO'				=> 'Sending configuration',
	'L_BATCH_SENDINGS'			=> 'NewsLetter sent in batches of',
	'L_FREQUENCE'				=> 'Periodicity',
	'L_AUTO_SENDING'			=> 'Automatic sends.',
	'L_AUTO_BLIND'				=> 'Automatic blind submissions if new publications.',
	'L_AUTO_PRE_CONF'			=> 'Automatic sends with the default introduction.',
	'L_AUTO_NOTIFY'				=> 'Notify the Webmaster of potential news.',
	'L_PARAM_DAY_1_2'			=> 'Send between',
	'L_PARAM_DAY'				=> 'day of week n°',
	'L_PARAM_HOUR_1_2'			=> 'Send between',
	'L_AND'						=> 'et',
	'L_START'					=> 'start',
	'L_END'						=> 'end',
	'L_LANG_UNAVAILABLE'		=> 'Language not available: %s',
	'L_MAIN'					=> 'General',
	'L_NAME_SENDER'				=> 'Sender name',
	'L_OBJECT'					=> 'Object',
	'L_MAIL_OBJECT'				=> 'Email Subject',
	'L_OBJECT_TO_PRINT'			=> 'Subject to display in the mailbox',
	'L_SEND'					=> 'Send',
	'L_COMMIT_SEND'				=> 'Validate sending',
    'L_SEND_VALID'              => 'Sending validated',
	'L_DEFAULT_MENU_NAME'		=> 'NewsLetter',
	'L_MENU_DISPLAY'			=> 'Display page in menu',
	'L_FORM_DISPLAY'			=> 'Show registration form on page',
	'L_MENU_TITLE'				=> 'Menu title',
	'L_MENU_POS'				=> 'Menu position',
	'L_COMPOSITION'				=> 'Composition',
    'L_SITE_LOGO'               => 'Site\'s Logo',
    'L_INSERT_LOGO'             => 'Insert the logo (editable in administration)',
	'L_PERMANENT_ELEMENT'		=> 'Edit permanent elements',
	'L_INTRO'					=> 'Custom intro texts',
    'L_CONTENT_INTRO'           => '<h1 style="font-size:24px;margin:0 20px 1em;font-family:Arial,sans-serif;">INTRO TITLLE</h1>
												<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Edit this paragraph.</p>
												<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="http://www.example.com" style="color:#ee4c50;text-decoration:underline;">link example</a></p>',
	'L_TEMPLATE'				=> 'Template',
	'L_PARAM_URL'				=> 'Parameter name in url',
	'L_ELEMENTS'				=> 'Elements',
	'L_MENTION'					=> 'Mention',
	'L_MENTIONS'				=> 'Mentions',
	'L_SHOW_MENTION'			=> 'Show Mentions',
    'L_MENTIONS_HTML'           => '<p style="text-align: center; margin: 0;">Yu receive this email because you subscribed in &nbsp; ###DATE###. - ###STOP###</p>',
	'L_POS_MENTION'				=> 'Position of mentions',
	'L_TOP'						=> 'High',
	'L_BOT_TITLE'				=> 'Under the title',
	'L_BOTTOM'					=> 'Down',
	'L_SHOW_FOOTER'				=> 'Show Foot',
	'L_FOOTER'					=> 'Foot',
    'L_FOOTER_HTML'             => '<table style="width: 100%; border-collapse: collapse; border: 0px none; border-spacing: 0px; "role="presentation">
	<tbody>
		<tr>
			<td style="padding: 0px; width: 50%; text-align: center;" align="left">
				<p style="margin: 0; font-size: 14px; line-height: 16px; font-family: Arial,sans-serif;">&reg; 2023<br> ###STOP###</p>
			</td>
			<td style="padding: 0px; width: 50%; text-align: center; height: 72.25px;">
				<p><a href="https:pluxpolis.net" target="_blank" rel="noopener"><img style="border-radius: 50%; background: yellow;" src="https://gcyrillus.alwaysdata.net/devPlugin/data/medias/news/socialPNG/bb.png" alt="Ressources for PluXml" width="40"></a></p>
			</td>
		</tr>
		<tr>
			<td style="padding: 0px; text-align: center; " colspan="2">
				<p style="margin: 0;">&nbsp;</p>
				<nav>###CATEGORIE###</nav>
			</td>
		</tr>
	</tbody>
</table',
	'L_INTRO_DISPLAY'			=> 'Show custom intro',
	'L_LASTART'					=> 'Last articles',
	'L_ADD_LASTART'				=> 'Add latest articles',
	'L_SAVE'					=> 'Save',
	'L_HELP'					=> 'Code to add in the theme to display the registration form',
	'L_MENU_LIB_BUTTON'			=> 'Label of subscription button',
	'L_NO_DATA'					=> 'No subscribers',
	'L_SUBSCRIPTION'			=> 'Registrations',
	'L_SUBSCRIPTION_COUNT'		=> 'Number',
	'L_PLACEHOLDER'				=> 'Text displayed in placeholder',
	'L_METHOD'					=> 'Form processing method',
    'L_INVALID_MAIL'            =>' <div class="msg"><p>This mail adress not valid.</p><p>Amail adress cannot contain empty space and must match a domain name.</p><p>Please fix it or use another one.</p></div>',
    
    # Admin
	'L_ADMIN'					=> 'Administration',
	'L_GOTO_CONFIG'				=> 'Go to the configuration page',
	'L_NEWS_TITLE'				=> 'Newsletter title',
	'L_STATISTICS'				=> 'Statistics',
	'L_SUBSCRIBERS'				=> 'Subscribers',
    'L_LAST_SEND'               => 'Date latest newsLetter',
	'L_NUMBER_OF_SUBSCRIBERS'	=> 'Number of subscriptions',
    'L_NUMBER_OF_UNSUBSCRIBERS' => 'Number of unsubscribers',
	'L_VALIDATED_SUBSCRIBERS'	=> 'Subscriptions validated',
    'L_CLEANED_SUBSCRIBERS'     => 'Subscriptions cancelled',
	'L_NEWS_SENT'				=> 'News-letters being sent',
	'L_FEEDBACK'				=> 'Visitors from News sent',
	'L_INFOS'					=> 'Infos',
	'L_CONFIG_INFOS'			=> 'Configuration Info',
	'L_MAIL_SENDER'				=> 'Sending email',
	'L_REGENERATE'				=> 'Regenerate',
	'L_REGENERATE_FROM'			=> 'Regenerate with articles from:',
	'L_CHOOSE_DURATION'			=> 'Choose a duration',
	'L_LOAD_ELSE_AND_REPLACE'	=> 'Edit and replace with previous NewsLetter',
	'L_LOAD_AND_REPLACE'		=> 'Load and replace',
	'L_VIEW_EDITING'			=> 'Preview and edit',
	'L_EDITING'					=> 'Edit',
	'L_EDIT'					=> 'Edit',
    'L_HELP_DATE_STOP'          => '<small>###DATE### inserts subscription date. <br> ###STOP### generates unssubscribe link.<br> ###CATEGORIE### inserts categories\'s links of the website.</small> ',
	'L_HELP_DATE_STOP'			=> '###DATE### inserts the recipient&#39;s subscription date.  ###STOP### his personal unsubscribe link. ###CATEGORY### inserts the links to the categories of the site.',
	'L_COMMIT_CHANGE'			=> 'Commit changes',
	'L_CANCEL_ALL'				=> 'Cancel all',
    
    # form.plxMyNewsLetter.php
	'L_CONFIG_MAIL_NEWS'		=> 'News Email',
	'L_NEWS_LETTER'				=> 'to the newsletter',
	'L_CHECK_MAIL_ADDRESS'		=> 'Check and save email to use.',
	'L_CONFIG_MAIL_ADDRESS'		=> 'Email address to use in emails',
	'L_FORM_TITLE'				=> 'Subscription',
	'L_SUBSCRIBE_OPTION'		=> 'I subscribe to the',
	'L_FORM_BUTTON'				=> 'Okay',
	'L_AUTHORIZE_MAIL'			=> 'I agree to receive the NewsLetter.',
	'L_UNSUBSCRIB'				=> 'See you soon,<br><br>Thank you for subscribing to our newsletter.We hope you found our content useful.<br>Regards,',
	'L_SUBSCRIPTION_REGISTERED'	=> 'Your subscription to the NewsLetter has been saved.',
	'L_SUBSCRIPTION_NOT_FOUND'	=> 'Hello, There is no or no longer a subscription associated with this unsubscribe link!',
    'L_SUBSCRIPTION_FOUND'      => '<div class="msg">
    <p>You already have a subscription!</p>
    <p>If you do not receive our emails, check that the previous newsLetter has not fallen into your spam, in this case mark this % s email address as legitimate and add it to your contacts to be able to receive the Newsletter. </p>
	<p>Sincerely,<br> %s, Webmaster of %s.
	</p>
    </div>',
    'L_SUBSCRIPTION_INFOS'      => '<div class="msg">
	<p>Sign up for the %s newsletter to be notified of new publications.<br> A notification or validation link will be sent to the email address you give.</p>
	<p><strong>An unsubscribe link is present in all emails so that you can revoke your subscription with a single click.</strong></p>
	<p>The stored data corresponding to your subscription are:</p>
	<ol><li>Your encrypted email address.</li><li> The date of registration </li><li>The date of the last shipment</li></ol>
	<p><small>Encrypted email addresses can only be used by the site that encrypted them and will only be used for the NewsLetter. They will not be passed on to a third party or used for other purposes.</small></p> 
    </div>',
    'L_SUBSCRIPTION_PENDING'	=> '<div class="msg"><p>Welcome,</p>
    <p>Your <b>%s</b> Subscription is almost activated...</p>
    <p> An email with a confirmation link is sent to you to finalize your registration and authorize us to send you the newsletter of the site.</p>
    <p>If you do not see this first email, Check that</p> 
    <ol>
    <li>The address used: <b>%s</b> is yours </li>
    <li>That the mail has not fallen into your spam, in this case marked it as legitimate to be able to receive the Newsletter</li>
    </ol>  
    <p>The stored data corresponding to your subscription are:</p>
    <ul>
    <li>Your encrypted email address.</li>
    <li>The date of registration</li>
    <li>The date of the last shipment</li>
    </ul>
    <p><a href="%s">Cancel this request</a>
    </div>',

    'L_SUBSCRIPTION_VALIDATE'   => '<div class="msg">
    <p>Welcome <b>%s</b>,</p>
    <p>Your <b>%s</b> Subscription is almost activated...</p>
    <p><a href="%s">I activate my subscription.( *)</a> - <a href="%s">I cancel my request.</a>.</p>
    <p>Sincerely, <br> %s</p>
    <p><small>(*)  Your email address is encrypted. It is only compatible and readable by the %s NewsLetter sending script. It is confidential and will not be shared or used for any other purpose.</small></p>
    </div>',

    'L_SUBSCRIPTION_VALIDATED'=>'<div class="msg">
    <p>Hello %s,</p>
    <p> Your Subscription is already active.</p>
	<p>Please check that our emails have not fallen into your spam/spam, in which case mark them as legitimate to be able to receive the Newsletter.</p>
    <cite>%s</cite>
    </div>',
    'L_SUBSCRIPTION_AUTO'     => '<div class="msg">
    <p>Welcome %s,</p>
    <p>Your Subscription is active(*)... </p>
    <p> You can cancel it at any time with this link: <a href="%s">I cancel my request</a>.</p>
    <p>Sincerely, <br> %s</p>
    <p><small>(*)  Your email address is encrypted. It is only compatible and readable by the %s NewsLetter sending script. It is confidential and will not be shared or used for any other purpose.</small></p>
    </div>',
    'L_SUBSCRIPTION_ACTIVE'		=> '<div class="msg">
    <p> Welcome,</p>
    <p>Your <b>%s</b> Subscription is activated...</p>
    <p>A confirmation email has been sent to you.</p>
    <p>If you do not see this first email, Check that</p> 
    <ol>
    <li>The address used: %s is yours</li>
    <li>That the mail has not fallen into your spam , in this case marked it as legitimate to be able to receive the Newsletter</li>
    </ol> 
    <p>The stored data corresponding to your subscription are:</p>
    <ul>
    <li>Your encrypted email address.</li>
    <li>The date of registration</li>
    <li>The date of the last shipment</li>
    </ul> 
    <p>An unsubscribe link is present in each NewsLeter.</p>
    <p><a href="%s">Cancel this request</a>
    </div>',

	'L_STOP'					=> 'Unsubscribe.',
	'L_CANCEL'					=> 'CANCEL.',
    
    #dates
	'L_MONTHLY'					=> 'Monthly',
	'L_QUATERLY'				=> 'Quarterly',
	'L_BI_ANNUAL'				=> 'Bi-annually',
	'L_MONTH'					=> 'month',
	'L_MONTH_OF'				=> 'month of',
	'L_DATE_MONTH_SHORT'		=> array('jan','feb','mar','apr','may','june','july','aug','sept','oct',' nov','dec'), // no use for english language, but others do
	'L_DATE_MONTH_LONG'			=> array('january','february','march','april','may','june','july','august','september','october',' november','december'), // no use for english language, but others do
);

























