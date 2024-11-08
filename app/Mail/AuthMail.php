<?php

// AuthMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $nom;
    protected $prenom;
    protected $email;
    protected $password;
    protected $code_secret;
    protected $pdfFilePath;
    protected $qrCodePath;

    public function __construct($nom, $prenom, $email, $password, $code_secret, $pdfFilePath, $qrCodePath)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->code_secret = $code_secret;
        $this->pdfFilePath = $pdfFilePath;
        $this->qrCodePath = $qrCodePath;
    }

    public function build()
    {
        return $this->view('emails.auth')
            ->subject('Lien d\'authentification')
            ->attach($this->pdfFilePath, [
                'as' => 'auth_info.pdf',
                'mime' => 'application/pdf',
            ])
            ->with([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'password' => $this->password,
                'code_secret' => $this->code_secret,
                'qrCodePath' => $this->qrCodePath, // Ajoutez qrCodePath ici
            ]);
    }
}
