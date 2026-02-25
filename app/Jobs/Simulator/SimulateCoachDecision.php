<?php

namespace App\Jobs\Simulator;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Submission;
use App\Enums\SubmissionDecisionStatus;

class SimulateCoachDecision implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Submission $submission
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $approvalProbability = 0.75;

        $newStatus = (rand(1, 100) <= $approvalProbability * 100) 
            ? SubmissionDecisionStatus::Approved 
            : SubmissionDecisionStatus::Declined;
            
        $this->submission->update([
            'decision_status' => $newStatus
        ]);
    }
}
