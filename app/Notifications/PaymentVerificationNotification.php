<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order; // Import Order model

class PaymentVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $verificationStatus; // 'approved' or 'rejected'

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $verificationStatus)
    {
        $this->order = $order;
        $this->verificationStatus = $verificationStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Pembayaran Pesanan #' . $this->order->order_number . ' - ' . ($this->verificationStatus === 'approved' ? 'Disetujui' : 'Ditolak');
        $greeting = 'Halo Owner,';
        $statusText = $this->verificationStatus === 'approved' ? 'telah DISETUJUI' : 'telah DITOLAK';
        $actionUrl = route('admin.dashboard'); // Assuming owner can also access admin dashboard or there's an owner dashboard.

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line("Bukti pembayaran untuk pesanan **#{$this->order->order_number}** dari **{$this->order->customer_name}** ({$this->order->customer_phone}) {$statusText} oleh Admin.")
            ->line("Detail Pesanan:")
            ->line("- Metode Pembayaran: Tunai")
            ->line("- Total Belanja: Rp " . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Dashboard Admin', $actionUrl)
            ->line('Mohon periksa dashboard Anda untuk detail lebih lanjut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->customer_name,
            'payment_method' => $this->order->payment_method,
            'total_amount' => $this->order->total_amount,
            'verification_status' => $this->verificationStatus,
        ];
    }
}
