<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TripInit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $customer;
    protected $type;
    public function __construct($customer, $type)
    {
        $this->customer = $customer;
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
            ->view("mail.trip_init.{$this->type}")
            ->with([
                    'customer' => $this->customer,
                ]);
    }
}
