<?php
declare(strict_types=1);

use Cake\Log\Log;
use Phinx\Seed\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed
{ 
    public function getDependencies(): array
    {
        return [
            'UsersSeed',
        ];
    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        try {
            // Define the data to insert
            $title = 'First Post';
            $articlesData = [
                [
                    'user_id' => 1,
                    'title' => $title,
                    'slug' => 'first-post',
                    'body' => 'This is the first post.',
                    'published' => 1,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ],
            ];

            // Check if data with the same title already exists
            $existingData = $this->fetchRow("SELECT * FROM articles WHERE title = '".$title."'");

            if (!$existingData) {
                // Data doesn't exist, so insert it
                $this->table('articles')->insert($articlesData)->save();
            } else {
                Log::write('warning', 'Article with title `{title}` already exists.', ['title' => $title]);
            }
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            Log::write('error', 'Error: {message}', ['message' => $e->getMessage()]);
        }
        
    }
}
