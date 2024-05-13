<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThongBaoNapTien extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $soTienDaNap;
    public $userName;
    public $thongtinthem;
    public function __construct($userName, $soTienDaNap, $thongtinthem)
    {

        $this->userName = $userName;
        $this->soTienDaNap = $soTienDaNap;
        $this->thongtinthem = $thongtinthem;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     $now = now()->format('d-m-Y');
    //     return new Envelope(
    //         subject: 'Khách Hàng Nạp Tiền:'.$now
    //     );
    // }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'Mail.ThongBaoNapTien',
    //         with: [
    //             'userName'    => $this->userName,
    //             'soTienDaNap' => $this->soTienDaNap,
    //         ],
    //     );
    // }

    public function build()
    {

        return $this->subject($this->thongtinthem)->view('Mail.ThongBaoNapTien')
            ->with([
                'userName'    => $this->userName,
                'soTienDaNap' => $this->soTienDaNap,
                'thongtinthem' => $this->thongtinthem
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
