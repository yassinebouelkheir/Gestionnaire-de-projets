<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date_assignation')->nullable();
            $table->integer('avancement')->default(0);
            $table->integer('priority')->default(1);
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->after('priority');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
