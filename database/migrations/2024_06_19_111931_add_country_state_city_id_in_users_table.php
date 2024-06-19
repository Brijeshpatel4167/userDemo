<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('country_id')->unsigned()->index()->nullable()->after('gender');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->integer('state_id')->unsigned()->index()->nullable()->after('country_id');
            $table->foreign('state_id')->references('id')->on('states');

            $table->integer('city_id')->unsigned()->index()->nullable()->after('state_id');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_foreign');
            $table->dropIndex('users_country_id_index');
            $table->dropColumn('country_id');

            $table->dropForeign('users_state_id_foreign');
            $table->dropIndex('users_state_id_index');
            $table->dropColumn('state_id');

            $table->dropForeign('users_city_id_foreign');
            $table->dropIndex('users_city_id_index');
            $table->dropColumn('city_id');
        });
    }
};
