<?php
// no direct access
defined('JPATH_BASE') or die;

jimport('joomla.utilities.date');

class plgContentSendnotes extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onContentAfterSave($context, $article, $isNew)
	{

		if ( $isNew != true ):
			$mailer = JFactory::getMailer();

			$config = JFactory::getConfig();
			$sender = array( 
			    $config->get( 'mailfrom' ),
			    $config->get( 'fromname' ) 
			);
			 
			$mailer->setSender($sender);

			$user = JFactory::getUser();
			$recipient = $user->email;
			 
			$mailer->addRecipient($recipient);

			$body   = '<h2>Our mail</h2>'
			    . '<div>A message to our dear readers'
			    . '<img src="cid:logo_id" alt="logo"/></div>';
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setBody($body);

			$send = $mailer->Send();
			if ( $send !== true ) {
			    echo 'Error sending email: ' . $send->__toString();
			} else {
			    echo 'Mail sent';
			}

		endif;
	}
}


