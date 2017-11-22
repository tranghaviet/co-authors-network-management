<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\CoAuthor;
use Illuminate\Console\Command;

class SynchronizeCoAuthorNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'co-author:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize co-author network by refresh co-author
    table in database';

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
     * @return void
     */
    public function handle()
    {
        Author::chunk(200, function ($authors) {
            foreach ($authors as $author) {
                $coAuthors = $author->collaborators(['id']);

                foreach ($coAuthors as $coAuthor) {
                    if (CoAuthor::where(['first_author_id' => $coAuthors->id,
                        'second_author_id' => $author->id])->exists()) {
                        continue;
                    }

                    CoAuthor::create([
                        'first_author_id' => $author->id,
                        'second_author_id' => $coAuthor->id,
                    ]);
                }
            }
        });
    }
}
