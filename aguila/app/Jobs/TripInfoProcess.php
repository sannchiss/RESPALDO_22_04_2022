<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\RouteInit;
use DB;

class TripInfoProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     **/
    protected $type;
    protected $vehicle_id;
    protected $rut;
    protected $date;
    protected $office_id;
    public $tries = 1;
    public function __construct($type, $vehicle_id, $office_id, $rut, $date)
    {
        $this->type = $type;
        $this->vehicle_id = $vehicle_id;
        $this->rut  = $rut;
        $this->date = $date;
        $this->office_id = $office_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $select = DB::select("SELECT
            string_agg(distinct documents.code::varchar, ', ') AS document_code,
            string_agg(distinct documents.order_number::varchar, ', ') AS document_order_number,
            customer_branches.label AS customer_name,
            offices.code AS office_code,
            customer_branches.address ||', '|| communes.label AS customer_address,
            users.email AS sales_email,
            customer_branches.email AS customer_email
            FROM routes 
                JOIN offices ON offices.id  = routes.office_id
                JOIN documents ON routes.id = documents.route_id
                JOIN customer_branches ON customer_branches.id = documents.customer_branch_id 
                    AND customer_branches.rut = '{$this->rut}'
                JOIN communes ON communes.id = customer_branches.commune_id
                JOIN employees ON employees.id = customer_branches.seller_id
                LEFT JOIN users ON users.employee_id = employees.id
            WHERE routes.vehicle_id = {$this->vehicle_id} 
                AND routes.office_id = {$this->office_id}
                AND departure_date = '{$this->date}'
            GROUP BY 3,4,5,6,7;");

        if(!isset($select[0])){
            return;
        }

        $customer = $select[0];

        //validacion para pruebas
        if(env('TEST_EMAIL_STATUS', true) == true){
            $customer_email = env('TEST_EMAIL_CUSTOMER', null);
            $sales_email    = env('TEST_EMAIL_SALES', null);
        } else {
            $customer_email = $customer->customer_email;
            $sales_email    = $customer->sales_email;
        }

        $validCustomer = $this->validEmail($customer_email);
        $validSales    = $this->validEmail($sales_email);

        //envio correo a cliente
        if($validCustomer){
            $messageCustomer = (new TravelInit($customer,'customer'))
                                ->onQueue('emails');

            Mail::to($customer_email)
                ->queue($messageCustomer);
        }
        
        //envio correo a vendedores
        if($validSales){
            $messageSales = (new TravelInit($customer,'sales'))
                            ->onQueue('emails');

            Mail::to($sales_email)
                ->queue($messageSales);
        }
    }

    protected function validEmail($email){
        $validator = \Validator::make(['email' => $email] , [
            'email' => 'required|email',
        ]);

         if ($validator->fails()) {
            return false;
        }

        return true;
    }
}
