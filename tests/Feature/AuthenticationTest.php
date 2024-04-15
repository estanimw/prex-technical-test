<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $password;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test user and save it in the $user variable
        $this->user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
        ]);
        $this->password = 'password';

        // Set the OAuth token expiration time to 1 seconds for testing
        Passport::tokensExpireIn(Carbon::now()->addSeconds(1));
    }

    public function testShouldLoginWithTheRightCredentials(): void
    {
        $response = $this->postRoute('login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['token']);
    }

    public function testShouldFailToLoginWithWrongCredentials(): void
    {
        $response = $this->postRoute('login', [
            'email' => $this->user->email,
            'password' => fake()->password(),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([ 'error' => 'Invalid credentials' ]);
    }

    public function testShouldFailToLoginWithMissingParameters(): void
    {
        $response = $this->postRoute('login', [
            'email' => $this->user->email
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'password' => [
                    'The password field is required.',
                ],
            ],
        ]);

        $response = $this->postRoute('login', [
            'password' => fake()->password()
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'email' => [
                    'The email field is required.',
                ],
            ],
        ]);
    }

    public function testShouldNotLoginWithNonExistantEmail(): void
    {
        $response = $this->postRoute('login', [
            'email' => fake()->email(),
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([ 'error' => 'Invalid credentials' ]);
    }

    public function testShouldRegister(): void
    {
        $userToRegister = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $this->password . fake()->password()
        ];

        $response = $this->postRoute('register', $userToRegister);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => $userToRegister['name'],
            'email' => $userToRegister['email']
        ]);
    }

    public function testShouldFailToRegisterWithAnExistingEmail(): void
    {
        $response = $this->postRoute('register', [
            'name' => fake()->name(),
            'email' => $this->user->email,
            'password' => fake()->password(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'email' => [
                    'The email has already been taken.',
                ],
            ],
        ]);
    }

    public function testShouldFailToRegisterWithMissingParameters(): void
    {
        $response = $this->postRoute('register', [
            'name' => fake()->name(),
            'email' => fake()->email()
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'password' => [
                    'The password field is required.',
                ],
            ],
        ]);

        $response = $this->postRoute('register', [
            'name' => fake()->name(),
            'password' => fake()->password()
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'email' => [
                    'The email field is required.',
                ],
            ],
        ]);

        $response = $this->postRoute('register', [
            'email' => fake()->email(),
            'password' => fake()->password()
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'name' => [
                    'The name field is required.',
                ],
            ],
        ]);
    }
}
