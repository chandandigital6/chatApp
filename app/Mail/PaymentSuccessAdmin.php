<?php

namespace App\Mail;

use App\Models\PaymentOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     public PaymentOrder $po;
    public string $razorpayPaymentId;
    public string $razorpayOrderId;

    public function __construct(PaymentOrder $po, string $rzpPaymentId, string $rzpOrderId)
    {
        $this->po = $po->loadMissing('package');
        $this->razorpayPaymentId = $rzpPaymentId;
        $this->razorpayOrderId   = $rzpOrderId;
    }

    /**
     * Get the message envelope.
     */
   public function envelope(): Envelope
{
    $amt = number_format($this->po->amount_payable ?? $this->po->amount, 2);
    $pkg = $this->po->package->name ?? 'Package';
    return new Envelope(subject: "Payment Received • {$pkg} • ₹{$amt}");
}


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_success_admin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
