<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_making_an_api_request()
    {
        $response = $this->postJson('/api/login', ['email' => 'test@example.com','password'=>'123456']);
 //$this->assertTrue($response['created']);


        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }
}
