<?php
// no direct access
defined('JPATH_BASE') or die;

class plgContentSendnotes extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onContentAfterSave($context, $article, $isNew)
	{

		// Check we are handling the frontend edit form.
		if ($context != 'com_users.note')
		{
			return true;
		}

		if (!$isNew):

			$sendEmail = $this->params->get('enviar_email', 1);
			$sendBcc   = $this->params->get('send_bcc', 1);
			if ( $sendEmail == 1 ):

				$mailer = JFactory::getMailer();

				$config = JFactory::getConfig();
				$sender = array( 
				    $config->get( 'mailfrom' ),
				    $config->get( 'fromname' )
				);
				 
				$mailer->setSender($sender);

				$user = JFactory::getUser( $article->user_id );
				$recipient = $user->email;
				 
				$mailer->addRecipient($recipient);

				if ( $sendBcc == 1 ) :
					$mailer->addCc($config->get( 'mailfrom' ));
				endif;

				$body   = $article->body;
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSubject( $article->subject );
				$mailer->setBody($body);

				$send = $mailer->Send();
				if ( $send !== true ) {
				    echo 'Error sending email: ' . $send->__toString();
				}

			endif;

		endif;
	}
}


