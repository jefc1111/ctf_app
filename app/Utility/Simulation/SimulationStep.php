<?php

namespace App\Utility\Simulation;

use App\Models\Submission;
use Illuminate\Console\Command;
use App\Jobs\Simulator\SimulateSubmission;
use App\Jobs\Simulator\SimulateCoachDecision;
use App\Models\Event;
use App\Models\team;
use App\Models\CaseModel;
use App\Models\SubmissionCategory;
use App\Enums\SubmissionDecisionStatus;

class SimulationStep
{
    private const BASE_RATE_PER_HOUR = 1.8;

    public function __construct(
        private Event $event,
        private ?Command $command = null
    ) {
    }

    private function log(string $message): void
    {
        $this->command?->info($message);
    }

    public function run(): void
    {
        $this->simulateCoachDecisions();

        $this->createSimulatedSubmissions($this->qtySubmissionsThisStep());
    }

    private function qtySubmissionsThisStep(): int
    {
        $progress = $this->event->progressPercentage() / 100;

        $intensity = 4 * (pow($progress, 2) - $progress + 0.5);

        $expected = ($this->event->durationHours() * self::BASE_RATE_PER_HOUR) * $intensity;

        // Add randomness — Poisson-like by sampling around the expected value
        $result = (int) round($expected * (0.5 + (mt_rand() / mt_getrandmax())));

        $this->log("Going to create $result new Submissions (derived from expected: $expected, intensity: $intensity.");

        return $result;
    }

    private function createSimulatedSubmissions(int $count): void
    {
        $teams = $this->event->teams;

        $cases = $this->event->cases;

        if ($teams->isEmpty() || $cases->isEmpty())
            return;

        for ($i = 0; $i < $count; $i++) {
            $team = $this->pickWeighted($teams, fn($t) => $this->teamWeight($t));
            
            $case = $this->pickWeighted($cases, fn($c) => $this->caseWeight($c, $team));

            $category = $this->pickWeighted(SubmissionCategory::all(), fn($sc) => $this->categoryWeight($sc));

            SimulateSubmission::dispatch($case, $team, $category)->delay(rand(0, 59));

            $this->log("Queued submission in category '{$category->name}' for team {$team->name} on case {$case->name}");
        }
    }

    private function teamWeight(Team $team): float
    {
        srand($team->id);

        $weight = 0.5 + (rand(0, 100) / 100.0);

        srand();

        return $weight;
    }

    private function caseWeight(CaseModel $case, Team $team): float
    {
        $attempts = $team->submissions()->where('case_id', $case->id)->count();

        return max(0.1, 1.0 - ($attempts * 0.1));
    }

    private function categoryWeight(SubmissionCategory $category): float
    {
        // Multiplying by itself accentuates the distance between categories i.e. 10*10=100 and 5000*5000=25000000. 
        // Making it negative means higher point categories are lower weighted. 
        return $category->points * $category->points * -1;
    }

    private function pickWeighted($items, callable $weightFn): mixed
    {
        $weights = $items->map(fn($item) => max(0.0001, $weightFn($item)));

        $total = $weights->sum();

        $random = (mt_rand() / mt_getrandmax()) * $total;

        $cumulative = 0;

        foreach ($items as $index => $item) {
            $cumulative += $weights[$index];

            if ($random <= $cumulative)
                return $item;
        }

        return $items->last();
    }

    private function simulateCoachDecisions(): void
    {
        // Get all pending submissions for this event
        $submissions = $this->event->submissions
            ->where('decision_status', SubmissionDecisionStatus::Pending);

        $this->log("{$submissions->count()} submissions awaiting coach decision.");

        if ($submissions->isEmpty()) {
            return;
        }
        
        // Take a random selection of them (we'll leave some on the table for later) and submit decision update jobs for them
        $submissions
            ->random()
            ->each(fn(Submission $s) => SimulateCoachDecision::dispatch($s)->delay(rand(0, 59)));        
    }
}