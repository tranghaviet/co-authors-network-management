<?php

use App\Models\Keyword;
use App\Models\KeywordPaper;

// NOTE: to use function after modified, you need to run "composer dump-autoload"
// and DON'T create new file in Helpers directory
// how to use Model to insert, read data: https://laravel.com/docs/5.5/eloquent
// Good library to manipulate CSV file: https://github.com/parsecsv/parsecsv-for-php (Consider us it)

if (! function_exists('handle_keywords')) {
    /**
     * Handle keywords.
     *
     * @param  int $paperId id of the paper
     * @param  array $keywords from a row of csv file
     * @return void
     */
    function handle_keywords($paperId, $keywords)
    {
        foreach ($keywords as $keyword) {
            // Check if keyword exists
            if (Keyword::where(['content' => $keyword])->exists()) {
                continue;
            }
            // If not exist, create one
            $keyword = Keyword::create(['content' => $keyword]);
            // Insert links paper-keyword
            KeywordPaper::created([
                'keyword_id' => $keyword->id,
                'paper_id' => $paperId,
            ]);
        }
    }
}
