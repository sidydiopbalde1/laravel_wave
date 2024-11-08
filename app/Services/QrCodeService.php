<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class QrCodeService
{
    public function generateQrCode(string $data): string
    {
        // Créer le QR code
        $qrCode = new QrCode($data);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Couleur du QR code
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Couleur de fond

        $writer = new PngWriter();
        $qrCodeResult = $writer->write($qrCode);

        // Encoder l'image en base64
        $base64Image = base64_encode($qrCodeResult->getString());

        // Retourner l'image en base64 sous un format compatible avec l'HTML
        return 'data:image/png;base64,' . $base64Image;
    }
}
