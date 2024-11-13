<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class QrCodeService
{
    public function generateQrCode(string $data, string $fileName): array
    {
        // Créer le QR code
        $qrCode = new QrCode($data);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Couleur du QR code
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Couleur de fond

        $writer = new PngWriter();
        $qrCodeResult = $writer->write($qrCode);

        // Définir le chemin de sauvegarde
        $filePath = storage_path('app/public/qrcodes/' . $fileName);
        file_put_contents($filePath, $qrCodeResult->getString());

        // Générer l'image en base64 pour l'email
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeResult->getString());

        // Retourner le chemin du fichier et l'image en base64
        return [
            'filePath' => $filePath,
            'base64Image' => $base64Image,
        ];
    }
}
