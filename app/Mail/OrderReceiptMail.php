<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // هنمرر الأوردر هنا

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد طلبك رقم: ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        // ده ملف الـ HTML اللي هيكون فيه شكل الإيميل
        return new Content(
            view: 'emails.order_receipt',
        );
    }
}