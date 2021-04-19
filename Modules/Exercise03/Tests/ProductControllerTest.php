<?php

namespace Tests\Feature;

use Tests\SetupDatabaseTrait;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    public function testIndexSuccess()
    {
        $response = $this->get(route('exercise03.index'));

        $response->assertStatus(200);
    }

    public function testCheckoutWithError()
    {
        $response = $this->postJson(route('exercise03.checkout'), [
            'total_products' => [
                1 => -1,
                2 => -2,
                3 => -3,
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'The given data was invalid.');
    }

    public function testCheckoutSuccessAllProducts()
    {
        $response = $this->json('POST', route('exercise03.checkout'), [
            'total_products' => [
                1 => 3,
                2 => 2,
                3 => 2,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('discount', 12);
    }

    public function testCheckoutSuccessAllProductsMore()
    {
        $response = $this->json('POST', route('exercise03.checkout'), [
            'total_products' => [
                1 => 3,
                2 => 2,
                3 => 8,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('discount', 12);
    }

    public function testCheckoutSuccessOnlyCravatWhiteShirt()
    {
        $response = $this->json('POST', route('exercise03.checkout'), [
            'total_products' => [
                1 => 2,
                2 => 3,
                3 => 1,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('discount', 5);
    }
}
