<h2>Fecha Desde: {{$dateStart}} Hasta: {{$dateEnd}} </h2>
<div class="card">
   <div class="card-header bg-secondary text-white"><h3>Documentos</h3></div>
   <div class="card-body">
       <table id="index-table-3" class="table table-striped">
           <thead class="">
               <tr>

                   <th align="center" style="text-align: center;" >Ruta</th>
                   <th align="center" style="text-align: center;" >Orden</th>
                   <th align="center" style="text-align: center;" >Documento</th>
                   <th align="center" style="text-align: center;" >Estado</th>
                   <th align="center" style="text-align: center;" >Cliente</th>
                   <th align="center" style="text-align: center;" >Rut Cliente</th>
                   <th align="center" style="text-align: center;" >Vendedor</th>
                   <th align="center" style="text-align: center;" >Rut Vendedor</th>
                   <th align="center" style="text-align: center;" >Conductor</th>
                   <th align="center" style="text-align: center;" >Rut Conductor</th>
                   <th align="center" style="text-align: center;" >Patente</th>
                   <th align="center" style="text-align: center;" >Cant. Bultos</th>
                   <th align="center" style="text-align: center;" >Fecha Creación</th>
                   <th align="center" style="text-align: center;" >Fecha Gestión</th>
               </tr>
           </thead>
           <tbody>
               @foreach($datas as $data)
                <tr>
                   <td align="center" style="text-align: center;">{{ $data->route }}</td>
                   <td align="center" style="text-align: center;">{{ $data->order }}</td>
                   <td align="center" style="text-align: center;">{{ $data->document }}</td>
                   <td align="center" style="text-align: center;">{{ $data->status }}</td>
                   <td align="center" style="text-align: center;">{{ $data->customer }}</td>
                   <td align="center" style="text-align: center;">{{ $data->rut }}</td>
                   <td align="center" style="text-align: center;">{{ $data->name_seller }}</td>
                   <td align="center" style="text-align: center;">{{ $data->rut_seller }}</td>
                   <td align="center" style="text-align: center;">{{ $data->name_driver }}</td>
                   <td align="center" style="text-align: center;">{{ $data->rut_driver }}</td>
                   <td align="center" style="text-align: center;">{{ $data->patent }}</td>
                   <td align="center" style="text-align: center;">{{ $data->packages }}</td>
                   <td align="center" style="text-align: center;">{{ $data->created_at }}</td>
                   <td align="center" style="text-align: center;">{{ $data->processed_date }}</td>
                </tr>
               @endforeach
           </tbody>
       </table>
   </div>
</div>