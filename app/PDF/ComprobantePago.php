<?php

namespace App\PDF;

use FPDF;

class ComprobantePago extends FPDF
{
    protected $user;
    protected $payment;
    protected $extraData;
    protected $type;

    public function __construct($user, $payment, $type, $extraData)
    {
        parent::__construct();
        $this->user = $user;
        $this->payment = $payment;
        $this->extraData = $extraData;
        $this->type = $type;
    }

    public function generar()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 16);

        // Colores personalizados
        $primary = [3, 74, 89];    // #034A59
        $secondary = [195, 240, 92]; // #C3F05C

        // Header
        $this->SetFillColor($primary[0], $primary[1], $primary[2]);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 20, utf8_decode('Comprobante de Pago'), 0, 1, 'C', true);
        $this->Ln(5);

        // Datos de usuario
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, utf8_decode("Cliente: {$this->user->name}"), 0, 1);
        $this->Cell(0, 10, utf8_decode("Correo: {$this->user->email}"), 0, 1);
        $this->Ln(5);

        // Detalles del pago
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor($primary[0], $primary[1], $primary[2]);
        $this->Cell(0, 10, utf8_decode('Detalles del pago'), 0, 1);
        $this->Ln(3);

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 8, utf8_decode("Monto: $".number_format($this->payment->amount, 2)." ".strtoupper($this->payment->currency)), 0, 1);
        $this->Cell(0, 8, utf8_decode("Método: ".strtoupper($this->payment->method)), 0, 1);
        $this->Cell(0, 8, utf8_decode("Estado: ".ucfirst($this->payment->status)), 0, 1);
        $this->Cell(0, 8, utf8_decode("Transacción: {$this->payment->id_transaction}"), 0, 1);
        $this->Ln(8);

        // Información adicional
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor($primary[0], $primary[1], $primary[2]);
        $this->Cell(0, 10, utf8_decode('Información de la compra'), 0, 1);

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        if ($this->type === 'tournament') {
            $this->Cell(0, 8, utf8_decode("Tipo: Reserva de torneo"), 0, 1);
            $this->Cell(0, 8, utf8_decode("Lugar ID: {$this->extraData->id}"), 0, 1);
            $this->Cell(0, 8, utf8_decode("Estado: {$this->extraData->status}"), 0, 1);
        } else {
            $this->Cell(0, 8, utf8_decode("Tipo: Membresía"), 0, 1);
            $this->Cell(0, 8, utf8_decode("Membresía: {$this->extraData->name}"), 0, 1);
            $this->Cell(0, 8, utf8_decode("Expira: {$this->extraData->expired_at}"), 0, 1);
        }

        // Footer
        $this->SetY(-30);
        $this->SetFillColor($secondary[0], $secondary[1], $secondary[2]);
        $this->SetTextColor($primary[0], $primary[1], $primary[2]);
        $this->SetFont('Arial', 'I', 11);
        $this->Cell(0, 10, utf8_decode(config('app.name')." • Gracias por tu compra"), 0, 1, 'C', true);

        return $this->Output('S'); // Devuelve el contenido del PDF en memoria
    }
}
