<div class="container">
        <div class="row"    >
            <div class="panel panel-primary">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    {!! Form::open(['route'=> 'upload.file', 'method' => 'POST', 'files'=>'true', 'id' => 'my-dropzone' , 'class' => 'dropzone', 'enctype' => 'multiform/form-data']) !!}
                    <div class="dz-message" style="height:100px;">
                        Soltar archivo o examinar
                        <div class="w-50 p-3" style="background-color: #eee;">
                        <input type="file" name="file" class="form-control-file" id="exampleFormControlFile1">
                        </div>
                    </div>
                    <div class="dropzone-previews"></div>
                    <button type="submit" class="btn btn-success" id="submit">Procesar</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>