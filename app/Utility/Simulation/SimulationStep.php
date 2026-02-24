<?php

namespace App\Utility\Simulation;

use Illuminate\Console\Command;
use App\Models\Event;

class SimulationStep
{
    private const BASE_RATE_PER_HOUR = 0.5;

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
        $count = $this->submissionsThisStep();

        $this->log("Creating {$count} submissions for event: {$this->event->name}");

        $this->distributeSubmissions($count);
    }

    private function submissionsThisStep(): int
    {
        $progress = $this->event->progressPercentage() / 100;

        $intensity = 4 * (pow($progress, 2) - $progress + 0.5);

        $expected = ($this->event->durationHours() * self::BASE_RATE_PER_HOUR) * $intensity;

        // Add randomness — Poisson-like by sampling around the expected value
        $result = (int) round($expected * (0.5 + (mt_rand() / mt_getrandmax())));

        $this->log("Going to create $result new Submissions (derived from expected: $expected, intensity: $intensity.");


        return (int) round($expected * (0.5 + (mt_rand() / mt_getrandmax())));
    }

    private function distributeSubmissions(int $count): void
    {
        $teams = $this->event->teams;

        $cases = $this->event->cases;

        if ($teams->isEmpty() || $cases->isEmpty())
            return;

        for ($i = 0; $i < $count; $i++) {
            $team = $this->pickWeighted($teams, fn($t) => $this->teamWeight($t));
            $case = $this->pickWeighted($cases, fn($c) => $this->caseWeight($c, $team));

            $user = $this->pickTeamMember($team);

            // SimulateSubmission::dispatch($user, $team, $case)->delay(rand(0, 59));

            $this->log("Queued submission by {$user->name} for team {$team->name} on case {$case->name}");
        }
    }

    private function teamWeight($team): float
    {
        srand($team->id);

        $weight = 0.5 + (rand(0, 100) / 100.0);

        srand();

        return $weight;
    }

    private function caseWeight($case, $team): float
    {
        $attempts = $team->submissions()->where('case_id', $case->id)->count();

        return max(0.1, 1.0 - ($attempts * 0.1));
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

    private function pickTeamMember($team)
    {
        return $team->members->random();
    }
}