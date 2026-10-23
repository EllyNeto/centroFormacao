<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddSlugToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
            $table->index('slug');
        });

        // Populate slugs for existing courses
        DB::table('courses')->orderBy('id')->chunk(100, function ($courses) {
            foreach ($courses as $course) {
                $slug = Str::slug($course->name);
                // Ensure uniqueness
                $originalSlug = $slug;
                $counter = 1;
                while (DB::table('courses')->where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                DB::table('courses')->where('id', $course->id)->update(['slug' => $slug]);
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
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
