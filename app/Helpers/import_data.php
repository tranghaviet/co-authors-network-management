<?php

use App\Models\Keyword;
use App\Models\KeywordPaper;

if (! function_exists('handle_keywords')) {
    /**
     * Handle keywords
     *
     * @param  int $paper_id id of the paper
     * @param  array $keywords from a row of csv file
     * @return void
     */
    function handle_keywords($paper_id, $keywords)
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
                'paper_id' => $paper_id,
            ]);
        }
    }
}
