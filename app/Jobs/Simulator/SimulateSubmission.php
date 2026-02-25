<?php

namespace App\Jobs\Simulator;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\CaseModel;
use App\Models\Team;
use App\Models\SubmissionCategory;
use App\Models\Submission;
use App\Enums\SubmissionDecisionStatus;

class SimulateSubmission implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private CaseModel $case,
        private Team $team,
        private SubmissionCategory $submissionCategory
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Submission::factory()->create([
            'team_id' => $this->team->id,
            'owner_id' => $this->team->members->random()->id,
            'submission_category_id' => $this->submissionCategory->id,
            'case_id' => $this->case->id,
            'decision_status' => SubmissionDecisionStatus::Pending
        ]);
    }
}
