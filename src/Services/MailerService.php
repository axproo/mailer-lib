<?php 

namespace Axproo\Mailer\Services;

use Config\Services;

class MailerService
{
    protected $email;
    protected $parser;

    public function __construct() {
        $this->email = Services::email();
        $this->parser = service('parser');
    }

    /**
     * Envoi d'un email
     * 
     * @param string $to
     * @param string $subject
     * @param mixed $view
     * @param array $data
     * @param mixed $headerImage
     * @return bool
     */
    public function send(string $to, string $subject, ?string $view = null, array $data = [], ?string $headerImage = null) {
        $this->validateEmail($to);
        $this->email->clear();
        $this->prepareEmail($to, $subject);
        $this->attachHeaderImage($headerImage, $data);

        $message = $this->generateMessage($view, $data);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        return $this->sendEmail();
    }

    /**
     * Préparation de base email
     * @param string $to
     * @param string $subject
     * @return void
     */
    protected function prepareEmail(string $to, string $subject) {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
    }

    /**
     * Génération du message HTML ou texte
     * @param mixed $view
     * @param array $data
     * @return string
     */
    protected function generateMessage(?string $view, array $data) : string {
        if ($view) {
            $data['year'] = date('Y');
            $message = $this->parser->setData($data)->render($view);
        } else {
            $message = $data['message'] ?? '';
        }
        return $message;
    }

    /**
     * Gestion des attachments (header image)
     * @param mixed $headerImage
     * @param array $data
     * @throws \Exception
     * @return void
     */
    protected function attachHeaderImage(?string $headerImage, array &$data) {
        $path = $headerImage ?? FCPATH . "email_header.png";
        if (!is_file($path)) {
            throw new \Exception(lang('Mailer.images.invalid_path'));
        }
        $this->email->attach($path);
        $cid = $this->email->setAttachmentCID($path);
        $data['image'] = "cid:$cid";
    }

    /**
     * Envoi du mail avec gestion d'erreur
     * @throws \Exception
     * @return bool
     */
    protected function sendEmail() {
        if (!$this->email->send()) {
            $debug = $this->email->printDebugger(['header','subject','body']);
            $error = $this->extractSmtpError($debug) ?? lang('Email.smtp.unknow');
            log_message("error", lang('Mailer.email.error') . " : " . $error);
            throw new \Exception(lang('Mailer.smtp.failure', [$error]));
        }
        return true;
    }

    /**
     * Extraction simple des erreurs SMTP
     * @param string $debug
     * @return string|null
     */
    protected function extractSmtpError(string $debug) : ?string {
        if (preg_match('/Unable to send email.*$/mi', $debug, $matches)) {
            return trim($matches[0]);
        }
        return null;
    }

    /**
     * Validation de l'email
     * @param string $email
     * @throws \Exception
     * @return void
     */
    protected function validateEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(lang('Mailer.email.invalid'));
        }
    }
}