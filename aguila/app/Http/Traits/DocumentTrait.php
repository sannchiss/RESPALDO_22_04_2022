<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use DataTables;
use App\Models\DocumentAttachment;
use App\Models\DocumentDetail;
use DB;

trait DocumentTrait{
 	public function geDocumentDetail($document_id, Request $request){
        $type = $request->input('type','aguila');

        $data = DocumentDetail::query()
                ->join('products', 'products.id', '=', 'document_details.product_id')
                ->join('statuses','statuses.id', '=','document_details.status_id')
                ->leftJoin('status_reasons','status_reasons.id', '=','document_details.status_reason_id')
                ->where('document_details.document_id', '=', $document_id)
                ->orderBy('document_details.row_order')
                ->select([
                    'products.code AS code_product',
                    'products.label as name_product',
                    'document_details.quantity',
                    'document_details.quantity_accepted',
                    'document_details.quantity_rejected',
                    'status_reasons.label AS status_reason',
                    'statuses.label AS status',
                    'statuses.color AS color'
                ]);
        if($type == 'cobra'){
            $data = $this->detailFromWms($document_id);
        }

        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                $color = $data->color ?? 'secondary';
                return "<h5><span class='badge badge-{$color}'>
                            {$data->status}
                        </span></h5>";
            })
             ->rawColumns(['status'])
            ->make(true);
    }

    protected function detailFromWms($id){
        return DB::connection('cobra')->table('outbound_order_details')
            ->join('products', 'products.id', '=', 'outbound_order_details.product_id')
            ->join('statuses','statuses.id', '=','outbound_order_details.status_id')
            ->where('outbound_order_details.outbound_order_id', '=', $id)
            ->select([
                'products.code AS code_product',
                'products.label as name_product',
                'outbound_order_details.quantity',
                DB::raw('0 AS quantity_accepted'),
                DB::raw('0 AS quantity_rejected'),
                DB::raw('null AS status_reason'),
                'statuses.label AS status',
                'statuses.color AS color'
            ]);
    }

    public function getDocumentImages($document_id){
        $data = DocumentAttachment::where('document_id', '=', $document_id)->get();
        $signs  = [];
        $others = [];
        foreach ($data as $value) {
            if($value->class == 'DOCUMENT_SIGN'){
                $signs[] = $value;
                continue;
            }
            $others[] = $value;
        }
        return view('helpers.document.images',['signs' => $signs, 'others' => $others]);
    }


    public function getVehiclePosition($vehicle_id){
        $position = DB::select("
            WITH data AS (
                SELECT 
                    lat, lon, date_time, employee_id, vehicle_id
                FROM current_gps_statuses
                WHERE vehicle_id = {$vehicle_id}

                UNION 
                
                SELECT 
                    lat, lon, date_time, employee_id, vehicle_id
                FROM current_cellphone_statuses
                WHERE vehicle_id = {$vehicle_id}
            )
            SELECT 
                data.lat,
                data.lon, 
                data.date_time,
                employees.name ||' '|| employees.lastname AS employee,
                vehicles.label,
                (CASE WHEN DATE_PART('minute', now() - data.date_time)  <= 10 THEN 'success' 
                                 WHEN DATE_PART('minute', now() - data.date_time)  > 10 AND DATE_PART('minute', now() - data.date_time) <= 20 THEN 'warning' 
                            ELSE 'danger' END) AS condition 
            FROM data 
                LEFT JOIN employees on employees.id = data.employee_id
                LEFT JOIN vehicles  on vehicles.id  = data.vehicle_id
            ORDER BY date_time DESC LIMIT 1;
            ");

         return response()->json([
            'position' =>  $position[0] ?? [],
        ]);
    }


    public function getOrderFromWms($orderNumber){

        $querySql = "
        SELECT 
            outbound_orders.id as id,
            'S/I' AS vehicle,
            0 AS vehicle_id,
            'S/I' AS route,
            outbound_orders.order_number AS order,
            'S/I' AS document,
            CASE WHEN statuses.id >= 1100 AND statuses.id <= 1102 THEN 
                    'ESPERA DE PREPARACIÓN'
                WHEN statuses.id >= 1103 AND statuses.id <= 1104 THEN
                    'EN PREPARACIÓN (picking)'
                WHEN statuses.id = 1105 THEN 
                    'EN PREPARACIÓN (auditado)'
                WHEN statuses.id >= 1106 AND statuses.id <= 1107 THEN
                    'ESPERA POR DESPACHO (anclado)'
                WHEN statuses.id = 1108 THEN 
                    'ESPERA POR DESPACHO (facturado)'
                ELSE 
                    statuses.label
            END AS status,
            
            'PENDING_DEPARTURE' AS status_code,

            CASE WHEN statuses.id >= 1100 AND statuses.id <= 1102 THEN 
                    'danger'
                WHEN statuses.id >= 1103 AND statuses.id <= 1105 THEN
                    'warning'
                WHEN statuses.id >= 1105 AND statuses.id <= 1108 THEN
                    'success'
                ELSE 
                    'info'
            END AS color,
            'S/I' As processed_date,
            customer_branches.label AS customer,
            customer_branches.address,
            0 AS lat,
            0 AS lon,
            customer_branches.commune,
            'S/I' AS received_by,
            outbound_orders.observation,
            'cobra' AS data_type
        FROM outbound_orders
        JOIN statuses ON statuses.id = outbound_orders.status_id
        JOIN customer_branches ON customer_branches.id = outbound_orders.customer_branch_id
        WHERE outbound_orders.order_number = '{$orderNumber}'
        ";

        return DB::connection('cobra')->table(DB::raw("({$querySql}) as x"));
    }
}
