<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed
{ 
    public function getDependencies()
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
            // Insert data into the 'articles' table
            $articlesData = [
                [
                    'user_id' => 1,
                    'title' => 'First Post',
                    'slug' => 'first-post',
                    'body' => 'This is the first post.',
                    'published' => 1,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->table('articles')->insert($articlesData)->save();
        } catch (\Exception $e) {
            echo "ArticlesSeed already executed";
            echo "Error: " . $e->getMessage();
        }
        
    }
}
