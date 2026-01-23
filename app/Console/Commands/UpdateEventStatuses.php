<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update event statuses based on their dates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Update completed events (past events that are still marked as upcoming)
        $completedEvents = Event::where('event_date', '<', now())
            ->where('status', 'upcoming')
            ->update(['status' => 'completed']);
            
        $this->info("Updated {$completedEvents} events to completed status.");

        // Update ongoing events (events happening today)
        $ongoingEvents = Event::where('event_date', '<=', now())
            ->where('event_date', '>=', now()->startOfDay())
            ->where('status', 'upcoming')
            ->update(['status' => 'ongoing']);
            
        $this->info("Updated {$ongoingEvents} events to ongoing status.");

        return Command::SUCCESS;
    }
}