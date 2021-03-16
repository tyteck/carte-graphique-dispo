<?php

namespace App\Console\Commands;

use App\Jobs\ExtractDataFromUrl;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class AddCardFromUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:card {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will allow you to scan for a new card from its url.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $style = new OutputFormatterStyle('white', 'blue', ['bold']);
        $this->output->getFormatter()->setStyle('bigBlue', $style);
        $this->line('A <fg=red;bg=yellow>simple</> line.');
        // Notice that we use the name of our new style inside the tags.
        $this->line('<bigBlue>Hello, there</bigBlue>');

        try {
            $extractor = ExtractDataFromUrl::from($this->argument('url'));
        } catch (Exception $exception) {
            $this->error("{$exception->getMessage()}");

            return 1;
        }
        $this->comment("{$extractor->card()->name} has been added successfully");
        return 0;
    }
}
