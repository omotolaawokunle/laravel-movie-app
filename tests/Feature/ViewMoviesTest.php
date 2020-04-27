<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;


class ViewMoviesTest extends TestCase
{
    /** @test */
    public function the_main_page_shows_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/popular' => $this->fakePopularMovies(),
            'https://api.themoviedb.org/3/movie/now_playing' => $this->fakePopularMovies(),
            'https://api.themoviedb.org/3/genre/movie/list' => $this->fakePopularMovies(),

        ]);
        $response = $this->get(route('movies.index'));
        $response->assertSee('Fake Movie');
    }

    /** @test */
    public function the_search_dropdown_works_correctly()
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie?query=jumanji' => $this->fakeSearchMovies(),
        ]);

        Livewire::test('search-dropdown')->assertDontSee('Jumanji')
            ->set('search', 'jumanji')
            ->assertSee('Jumanji');
    }

    private function fakeSearchMovies()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 406.677,
                    "vote_count" => 2607,
                    "video" => false,
                    "poster_path" => '/xBvHzcjRiWyobQkxBh0682dtRI.jpg',
                    'id' => 419704,
                    'original_title' => 'Jumanji',
                    'genre_ids' => [12, 18, 9648, 878, 53],
                    'title' => 'Jumanji',
                    'vote_average' => 6,
                    'overview' => 'Jumanji description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond.',
                    'release_date' => '2019-09-17'
                ],
            ],
        ]);
    }
    private function fakePopularMovies()
    {
        return Http::response([
            'results' => [
                [
                    'id' => 112345,
                    'title' => 'fake Movie',
                    'original_language' => 'en'
                ]
            ]
        ], 200);
    }
}
