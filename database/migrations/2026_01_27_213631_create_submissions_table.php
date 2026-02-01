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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_category_id');
            $table->foreignId('team_id');
            $table->foreignId('case_id');
            $table->foreignId('owner_id');
            $table->string('name');
            $table->text('content');
            $table->text('explanation')->nullable();
            $table->boolean('draft');
            $table->string('decision_status');
            $table->text('decision_supporting_evidence')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
