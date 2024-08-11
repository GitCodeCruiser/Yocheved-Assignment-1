<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Report::updateOrCreate(
            [
                'title' => 'Test Report',
            ],
            [
                'title' => 'Test Report',
                'body' =>  "<p>Student name: @student_full_name&nbsp;</p><p>Session date: @session_date&nbsp;</p><p>Report card for period: @target_start_date to @target_end_date&nbsp;</p><p>Target: @target</p><p>Minutes: @session_minutes</p><p>Achieved: @session_rating Lorem Ipsum is simply&nbsp;</p><p>@student_full_name&nbsp;</p><p>text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since @session_date, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing the achievement level @session_rating out of @target, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&nbsp;</p><p>=============================================</p>"
            ]
        );
    }
}
