<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\RouteInit;
use App\Models\Employee;
use App\Models\CustomerBranch;
use DB;

class RouteInfoProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $type;
    protected $office_id;
    protected $routes;
    public $tries = 1;
    public function __construct($type, $office_id, $routes, $date)
    {
        $this->type = $type;
        $this->office_id = $office_id;
        $this->routes = $routes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //separo segun tipo
        $routesString = implode(",", $this->routes);
        if($type = 'INI'){
            $this->InitRoute($routesString);
        }
    }

    protected function InitRoute($routesString){
        $select = "SELECT
                string_agg(distinct documents.id::varchar, ', ') AS document_id,
                customer_branch_id,
                customer_branches.email AS customer_email,
                customer_branches.label AS customer_name,
                customer_branches.address ||', '|| communes.label AS customer_address,
                users.email AS sales_email,
                employees.name ||' '|| employees.lastname AS sales_name,
                employees.id AS employee_id,
                offices.code AS office_code
            FROM routes 
                JOIN offices ON offices.id  = routes.office_id
                JOIN documents ON routes.id = documents.route_id
                JOIN customer_branches ON customer_branches.id = documents.customer_branch_id
                JOIN communes ON communes.id = customer_branches.commune_id
                JOIN employees ON employees.id = customer_branches.seller_id
                LEFT JOIN users ON users.employee_id = employees.id
            WHERE routes.code IN ({$routesString}) 
                AND routes.office_id = {$this->office_id}
            GROUP BY 2,3,4,5,6,7,8,9;";

        $customers = DB::select($select);
        foreach ($customers as $customer) {
            $this->sendEmail($customer);
        }
    }

    protected function sendEmail($customer){

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
        //Si ninguno es valido no se genera nada
        if(!$validCustomer && !$validSales){
            return;
        }
        $documents_details = DB::select("
            SELECT 
                documents.code as document_code,
                document_details.quantity,
                products.code,
                products.label
            FROM documents
                JOIN document_details ON documents.id = document_details.document_id
                JOIN products ON products.id = document_details.product_id
            WHERE documents.id IN ({$customer->document_id})
            ");

        $documents = [];
        foreach ($documents_details as $documents_detail) {
            $documents[$documents_detail->document_code][] = $documents_detail;
        }

        //envio correo a cliente
        if($validCustomer){
            $messageCustomer = (new RouteInit($documents, $customer,'customer'))
                                ->onQueue('emails');

            Mail::to($customer_email)
                ->queue($messageCustomer);
        }
        
        //envio correo a vendedores
        if($validSales){
            $messageSales = (new RouteInit($documents, $customer,'sales'))
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
