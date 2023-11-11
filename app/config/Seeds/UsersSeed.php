<?php
declare(strict_types=1);

use Cake\Log\Log;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Phinx\Seed\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
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
            $userEmail = 'cakephp@example.com';
            $usersData = [
                [
                    'email' => $userEmail,
                    'password' => (new DefaultPasswordHasher())->hash('secret'),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ],
            ];

            // Check if data with the same email already exists
            $existingData = $this->fetchRow("SELECT * FROM users WHERE email = '".$userEmail."'");

            if (!$existingData) {
                // Data doesn't exist, so insert it
                $this->table('users')->insert($usersData)->save();
            } else {
                Log::write('warning', 'User with email `{email}` already exists.', ['email' => $userEmail]);
            }
        } catch (\Exception $e) {
            // Catch the error if so
            Log::write('error', 'Error: {message}', ['message' => $e->getMessage()]);
        }

    }
}
