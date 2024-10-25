<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->boolean('is_accepted')->nullable()->after('is_winner'); // Track if the bid is accepted
            $table->timestamp('accepted_at')->nullable()->after('is_accepted'); // Timestamp for acceptance
            $table->timestamp('deadline')->nullable()->after('accepted_at'); // Deadline for accepting the bid
        });
    }

    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropColumn(['is_accepted', 'accepted_at', 'deadline']);
        });
    }

};
