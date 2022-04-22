<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpsStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gps_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gps_device_id')->index();
            $table->integer('employee_id')->nullable()->index();
            $table->integer('vehicle_id')->index();
            $table->double('lat');
            $table->double('lon');
            $table->double('speed');
            $table->smallInteger('heading');
            $table->integer('miliage');
            $table->smallInteger('gps_signal');
            $table->smallInteger('phone_signal');
            $table->smallInteger('ignition_status');

            $table->datetime('date_time');
            $table->timestamps();

            $table->foreign('gps_device_id')->references('id')->on('gps_devices')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });

        \DB::statement('CREATE SCHEMA IF NOT EXISTS partitions;');

        \DB::statement("CREATE FUNCTION insert_gps_statuses_trigger()
            RETURNS TRIGGER AS $$

            DECLARE
                new_table text;
                start_date timestamp;
                end_date timestamp;
                idxname text;
            BEGIN
                start_date := to_char(NEW.date_time, 'YYYY-MM-DD 00:00:00')::timestamp;
                new_table  := 'gps_statuses_'||to_char(NEW.date_time, 'YYYYMMDD');
                idxname    := 'gs_'||to_char(NEW.date_time, 'YYYYMMDD');

                IF NOT EXISTS ( SELECT relname FROM pg_class WHERE relname=new_table) THEN
                    end_date := start_date + INTERVAL '1 day'; 

                    EXECUTE 'CREATE TABLE partitions.'||quote_ident(new_table)||' (
                        CHECK ( date_time >= (' || quote_literal(start_date) || ')
                            AND date_time <  (' || quote_literal(end_date) || ')
                        ),
                        CONSTRAINT ' || quote_ident(idxname||'_pk') || ' PRIMARY KEY (id)
                    ) INHERITS (gps_statuses)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_gps_device_id_idx') || ' ON partitions.'||quote_ident(new_table)|| ' (gps_device_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_employee_id_idx')   || ' ON partitions.'||quote_ident(new_table)|| ' (employee_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_vehicle_id_idx')    || ' ON partitions.'||quote_ident(new_table)|| ' (vehicle_id)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_date_time_idx')     || ' ON partitions.'||quote_ident(new_table)|| ' (date_time)';
                    EXECUTE 'CREATE INDEX ' || quote_ident(idxname||'_created_at_idx')    || ' ON partitions.'||quote_ident(new_table)|| ' (created_at)';
                END IF;

                EXECUTE 'INSERT INTO partitions.'||quote_ident(new_table)||' VALUES 
                    (DEFAULT,
                    $1.gps_device_id,
                    $1.employee_id,
                    $1.vehicle_id,
                    $1.lat,
                    $1.lon,
                    $1.speed,
                    $1.heading,
                    $1.miliage,
                    $1.gps_signal,
                    $1.phone_signal,
                    $1.ignition_status,
                    $1.date_time
                    )' USING NEW;
                    
                RETURN null;
            
            END;
            $$
            LANGUAGE plpgsql;");

        \DB::statement(
            'CREATE TRIGGER insert_gps_statuses_trigger
             BEFORE INSERT ON gps_statuses
             FOR EACH ROW EXECUTE PROCEDURE insert_gps_statuses_trigger();'
        );
        \DB::statement(
            'CREATE TRIGGER insert_gps_statuses_current_trigger
             AFTER INSERT OR UPDATE ON current_gps_statuses
             FOR EACH ROW EXECUTE PROCEDURE insert_gps_statuses_trigger();'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        \DB::statement("DROP TRIGGER IF EXISTS insert_gps_statuses_trigger ON gps_statuses CASCADE");
        \DB::statement("DROP TRIGGER IF EXISTS insert_gps_statuses_current_trigger ON current_gps_statuses CASCADE");
        \DB::statement("DROP FUNCTION IF EXISTS insert_gps_statuses_trigger()");
        //\DB::statement('DROP SCHEMA partitions;');
        \DB::statement("DROP TABLE IF EXISTS gps_statuses CASCADE");
    }
}
