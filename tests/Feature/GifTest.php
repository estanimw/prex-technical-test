<?php

namespace Tests\Feature;

use App\Http\Clients\GiphyHttpClient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GifTest extends TestCase
{
    use DatabaseTransactions;

    const GIF_ID = 'qPCln5TSOsdRS';
    const NON_EXISTANT_USER_ID = 0;
    const NON_EXISTANT_ID = 'XXXXXXXXXXXXXXXXXXXXXX';

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

        Passport::actingAs($this->user);
    }

    public function testShouldGetAGifByItsId(): void
    {
        Http::fake([
            'http://api.giphy.com/v1/*' => Http::response([
                'data' => ['type' => 'gif', 'id' => self::GIF_ID],
                'meta' => ['status' => Response::HTTP_OK]],
                Response::HTTP_OK
            ),
        ]);

        $response = $this->get(route('gif.find', [
            'id' => self::GIF_ID,
        ]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['gif' => ['type' => 'gif', 'id' => self::GIF_ID]]);
    }

    public function testShouldFailTryingToGetAGifWithoutTheRequiredParams(): void
    {
        $response = $this->get(route('gif.find'));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'id' => [
                    'The id field is required.',
                ],
            ],
        ]);
    }

    public function testShouldFailTryingToGetAGifByANonExistantId(): void
    {
        Http::fake([
            'http://api.giphy.com/v1/*' => Http::response([
                'data' => [],
                'meta' => ['status' => Response::HTTP_NOT_FOUND]],
                Response::HTTP_OK
            ),
        ]);

        $response = $this->get(route('gif.find', [
            'id' => self::NON_EXISTANT_ID
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['gif' => []]);
    }

    public function testShouldGetGifsByASearchCriteria(): void
    {
        Http::fake([
            'http://api.giphy.com/v1/*' => Http::response([
                'data' => [
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID]
                ],
                'meta' => ['status' => Response::HTTP_OK]],
                Response::HTTP_OK
            ),
        ]);

        $response = $this->get(route('gif.search', [
            'query' => 'harry potter'
        ]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['gifs' =>
            [
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID]
            ]
        ]);
    }

    public function testShouldGetGifsByASearchCriteriaWithPagination(): void
    {
        Http::fake([
            'http://api.giphy.com/v1/*' => Http::response([
                'data' => [
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID],
                    ['type' => 'gif', 'id' => self::GIF_ID]
                ],
                'meta' => ['status' => Response::HTTP_OK]],
                Response::HTTP_OK
            ),
        ]);

        $response = $this->get(route('gif.search', [
            'query' => 'harry potter',
            'offset' => 0,
            'limit' => 3
        ]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['gifs' =>
            [
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID],
                ['type' => 'gif', 'id' => self::GIF_ID]
            ]
        ]);
    }

    public function testShouldFailToGetGifsByASearchCriteriaWithoutRequiredParams(): void
    {
        $response = $this->get(route('gif.search', [
            'offset' => 0,
            'limit' => 3
        ]));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'query' => [
                    'The query field is required.',
                ],
            ],
        ]);
    }

    public function testShouldSaveAGifAsFavorite(): void
    {
        $favorite = [
            'user_id' => $this->user->id,
            'gif_id' => self::GIF_ID,
            'alias' => fake()->name()
        ];

        $response = $this->postRoute('gif.favorite', $favorite);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('favorite_gifs', [
            'user_id' => $favorite['user_id'],
            'gif_id' => $favorite['gif_id'],
            'alias' => $favorite['alias']
        ]);
    }

    public function testShouldFailSavingAFavoriteGifOfANonExistantUser(): void
    {
        $favorite = [
            'user_id' => self::NON_EXISTANT_USER_ID,
            'gif_id' => self::GIF_ID,
            'alias' => fake()->name()
        ];

        $response = $this->postRoute('gif.favorite', $favorite);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'error' => [
                'user_id' => [
                    'The selected user id is invalid.',
                ],
            ],
        ]);
    }
}
