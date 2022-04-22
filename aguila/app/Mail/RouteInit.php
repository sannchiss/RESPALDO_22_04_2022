<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RouteInit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $documents;
    protected $info;
    protected $type;
    public function __construct($documents, $info, $type)
    {
        $this->documents = $documents;
        $this->info = $info;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@prisa.cl', 'Equipo Prisa')
            ->subject('Notificación de despacho')
            ->view("mail.route_init.{$this->type}")
            ->with([
                    'documents' => $this->documents,
                    'info'  => $this->info
                ]);
    }
}
