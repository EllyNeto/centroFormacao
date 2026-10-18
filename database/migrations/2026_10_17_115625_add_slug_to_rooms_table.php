<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddSlugToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
            $table->index('slug');
        });

        // Populate slugs for existing rooms
        DB::table('rooms')->orderBy('id')->chunk(100, function ($rooms) {
            foreach ($rooms as $room) {
                $slug = Str::slug($room->name);
                // Ensure uniqueness
                $originalSlug = $slug;
                $counter = 1;
                while (DB::table('rooms')->where('slug', $slug)->where('id', '!=', $room->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                DB::table('rooms')->where('id', $room->id)->update(['slug' => $slug]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
