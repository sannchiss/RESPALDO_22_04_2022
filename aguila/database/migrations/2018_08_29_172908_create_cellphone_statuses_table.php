<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellphoneStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cellphone_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cellphone_id')->index();
            $table->integer('employee_id')->nullable()->index();
            $table->integer('auxiliary_id')->nullable()->index();
            $table->integer('vehicle_id')->nullable()->index();
            $table->double('lat');
            $table->double('lon');
            $table->double('battery')->nullable(); //porcentage
            $table->double('precision')->nullable();
            $table->double('speed')->nullable();
            $table->double('signal')->nullable(); //cellphone
            $table->double('wifi_signal')->nullable();
            $table->double('wifi_strength')->nullable();
            $table->string('wifi_mac')->nullable();
            $table->double('capacity_storage_mb')->nullable();
            $table->double('available_storage_mb')->nullable();
            $table->datetime('date_time');

            $table->timestamps();

            $table->foreign('cellphone_id')->references('id')->on('cellphones')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('auxiliary_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });

        \DB::statement('CREATE SCHEMA IF NOT EXISTS partitions;');

        \DB::statement("CREATE FUNCTION insert_cellphone_statuses_trigger()
            RETURNS TRIGGER AS $$

            DECLARE
                new_table text;
                start_date timestamp;
                end_date timestamp;
                idxname text;
            BEGIN
                start_date := to_char(NEW.date_time, 'YYYY-MM-DD 00:00:00')::timestamp;
                new_table  := 'cellphone_statuses_'||to_char(NEW.date_time, 'YYYYMMDD');
                idxname    := 'cs_'||to_char(NEW.date_time, 'YYYYMMDD');

                IF NOT EXISTS ( SELECT relname FROM pg_class WHERE relname=new_table) THEN
                    end_date := start_date + INTERVAL '1 day'; 

                    EXECUTE 'CREATE TABLE partitions.'||quote_ident(new_table)||' (
                        CHECK ( date_time >= (' || quote_literal(start_date) || ')
                            AND date_time <  (' || quote_literal(end_date) || ')
                        ),
                        CONSTRAINT ' || quote_ident(idxname||'_pk') || ' PRIMARY KEY (id)
                    ) INHERITS (cellphone_statuses)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_cellphone_id_idx') || ' ON partitions.'||quote_ident(new_table)|| ' (cellphone_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_employee_id_idx')  || ' ON partitions.'||quote_ident(new_table)|| ' (employee_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_auxiliary_id_idx') || ' ON partitions.'||quote_ident(new_table)|| ' (auxiliary_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_vehicle_id_idx')   || ' ON partitions.'||quote_ident(new_table)|| ' (vehicle_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_date_time_idx')    || ' ON partitions.'||quote_ident(new_table)|| ' (date_time)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_created_at_idx')   || ' ON partitions.'||quote_ident(new_table)|| ' (created_at)';
                END IF;

                EXECUTE 'INSERT INTO partitions.'||quote_ident(new_table)||' VALUES 
                    (DEFAULT, 
                    $1.cellphone_id,
                    $1.employee_id,
                    $1.auxiliary_id,
                    $1.vehicle_id,
                    $1.lat,
                    $1.lon,
                    $1.battery,
                    $1.precision,
                    $1.speed,
                    $1.signal,
                    $1.wifi_signal,
                    $1.wifi_strength,
                    $1.wifi_mac,
                    $1.capacity_storage_mb,
                    $1.available_storage_mb,
                    $1.date_time
                    )' USING NEW;
                    
                RETURN null;
            
            END;
            $$
            LANGUAGE plpgsql;");

        \DB::statement(
            'CREATE TRIGGER insert_cellphone_statuses_trigger
             BEFORE INSERT ON cellphone_statuses
             FOR EACH ROW EXECUTE PROCEDURE insert_cellphone_statuses_trigger();'
        );

        \DB::statement(
            'CREATE TRIGGER insert_cellphone_statuses_current_trigger
             AFTER INSERT OR UPDATE ON current_cellphone_statuses
             FOR EACH ROW EXECUTE PROCEDURE insert_cellphone_statuses_trigger();'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        \DB::statement("DROP TRIGGER IF EXISTS insert_cellphone_statuses_trigger ON cellphone_statuses CASCADE");
        \DB::statement("DROP TRIGGER IF EXISTS insert_cellphone_statuses_current_trigger ON current_cellphone_statuses CASCADE");
        \DB::statement("DROP FUNCTION IF EXISTS insert_cellphone_statuses_trigger()");
        
        \DB::statement("DROP TABLE IF EXISTS cellphone_statuses CASCADE");
        \DB::statement('DROP SCHEMA partitions;');
    }
}
