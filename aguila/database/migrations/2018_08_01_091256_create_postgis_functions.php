<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostgisFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        /*
        DB::statement("CREATE SCHEMA  IF NOT EXISTS topology;");
        DB::statement("COMMENT ON SCHEMA topology IS 'PostGIS Topology schema';");
        DB::statement("CREATE EXTENSION IF NOT EXISTS postgis_topology WITH SCHEMA topology;");
        DB::statement("COMMENT ON EXTENSION postgis_topology IS 'PostGIS topology spatial types and functions';");
        */


        DB::statement("CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;");
        DB::statement("COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP EXTENSION IF EXISTS postgis");
    }
}
