<?php
namespace Mail;

use Phalcon\Mvc\User\Component;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;
use Phalcon\Mvc\View;

/**
 * Mail\Mail
 * Sends e-mails based on pre-defined templates
 *
 * To use the the gmail smtp server with your gmail account you need to:
 *
 * 	1) In Google "Account settings" enable "Access for less secured apps" by setting it to "Allow".
 *
 *  2) In gmail settings under the "Forwarding and POP/IMAP" tab, check the IMAP status, it needs to
 *     be enabled. (This will allow the emails sent to be saved in the sent folder.)
 *
 *  3) If after steps 1 and 2 you still throw the Exception Google's smtp server is not accepting the
 *     user name and password you need to open a browser, go to gmail and log in, then open another
 *     tab in the same browser andgo to this address:
 *
 *     https://accounts.google.com/DisplayUnlockCaptcha
 *
 *  Configuracion GMAIL
 *  465 is the port for ssl, 587 is used for tls encryption (look at http://swiftmailer.org/docs/sending.html#encrypted-smtp)
 *
 *
 *  PHP Warning:
 *  stream_socket_enable_crypto(): SSL operation failed with code 1. OpenSSL Error messages: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed
 *  PHP 5.6 introduce la verificación del certificado SSL
 *  Swiftmailer has now been updated to include an option for this. It can now be solved using the setStreamOptions method from your Swift_SmtpTransport instance rather than editing the swift class.
 *  ->setStreamOptions( ['ssl' => ['allow_self_signed' => true, 'verify_peer' => false]]
 */
class Mail extends Component
{
    protected $transport;

    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge([
            'publicUrl' => $this->config->application->publicUrl
        ], $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    /**
     * Sends e-mails via AmazonSES based on predefined templates
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     * @return bool|int
     * @throws Exception
     */
    public function send($to, $subject, $name, $params)
    {
        // Settings
        $mailSettings = $this->config->mail;

        $template = $this->getTemplate($name, $params);

        // Create the message
        $message = Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom([
                $mailSettings->fromEmail => $mailSettings->fromName
            ])
            ->setBody($template, 'text/html');

        if (!$this->transport) {

                $this->transport = Smtp::newInstance(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                )
                ->setUsername($mailSettings->smtp->username)
                ->setPassword($mailSettings->smtp->password)
                ->setStreamOptions( ['ssl' => ['allow_self_signed' => true, 'verify_peer' => false]] );
        }

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($this->transport);

        if (! empty($to)) {
        	$user_email = array_keys($to);
        	$this->logger->info('Email '.$name.' a: ' .$user_email[0]. ' enviado. Asunto: '. $subject);
        }

		return $mailer->send($message);
    }
}
