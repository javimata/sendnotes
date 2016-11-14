<?php
// no direct access
defined('JPATH_BASE') or die;
date_default_timezone_set("America/Mexico_City");

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

		// Check if is new
		if ($isNew):

			// Get params
			$sendEmail = $this->params->get('enviar_email', 1);
			$sendBcc   = $this->params->get('send_bcc', 1);
			$titulo    = $this->params->get('titulo', "");
			if ( $titulo ) { $titulo = " " . $titulo; }
			$titulo = $article->subject . $titulo;
			$texto_footer    = $this->params->get('texto_footer', "");

			// Check params if need ssend email
			if ( $sendEmail == 1 ):

				// Config email data
				$mailer = JFactory::getMailer();
				$config = JFactory::getConfig();
				$sender = array( 
				    $config->get( 'mailfrom' ),
				    $config->get( 'fromname' )
				);				 
				$mailer->setSender($sender);

				// Create the body format
				$body_pre  = "";
				$body_pre .= "<h3>" . $titulo;
				$body_pre .= "<br><small>" . date('d-m-Y h:i:s') . "</small></h3>";
				$body_pre .= $article->body;
				if ( $texto_footer ) { $body_pre .= "<hr><small>" . $texto_footer . "</small>"; }

				// Get user to send the note & email
				$user = JFactory::getUser( $article->user_id );
				$recipient = $user->email;
				$mailer->addRecipient($recipient);

				// Check params if need add a Bcc
				if ( $sendBcc == 1 ) :
					$mailer->addCc($config->get( 'mailfrom' ));
				endif;

				$body   = $body_pre;
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSubject( $titulo );
				$mailer->setBody($body);

				// Send email
				$send = $mailer->Send();
				if ( $send !== true ) {
				    echo 'Error sending email: ' . $send->__toString();
				}

			endif;

		else:

			return true;

		endif;
	}
}


