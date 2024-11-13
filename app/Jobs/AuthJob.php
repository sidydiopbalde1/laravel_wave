<?php

namespace App\Jobs;

use App\Mail\AuthMail;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class AuthJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $password;
    protected $code_secret;
    protected $nom;
    protected $prenom;

    public function __construct($email, $nom, $prenom, $code_secret)
    {
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->code_secret = $code_secret;
    }

    public function handle()
    {
        $qrCodeService = new QrCodeService();
        $pdfService = new PdfService();

        // Générer le QR code avec le chemin du fichier et la version base64
        $qrCodeData = json_encode([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'code_secret' => $this->code_secret,
        ]);
        
        $qrCodePath = $qrCodeService->generateQrCode($qrCodeData, uniqid() . '_qrcode.png');

        // Générer le PDF
        $pdfFilePath = storage_path('app/public/auth_emails/' . uniqid() . '_auth_info.pdf');
        $pdfData = [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'qrCodePath' => $qrCodePath['base64Image'],
            'code_secret' => $this->code_secret,
        ];
        $pdfService->generatePdf('emails.auth', $pdfData, $pdfFilePath);

        // Envoyer l'email avec le PDF et QR code
        Mail::to('newsdb191@gmail.com')->send(new AuthMail($this->nom, $this->prenom, $this->email, $this->code_secret, $pdfFilePath, $qrCodePath['base64Image']));
    }
}
