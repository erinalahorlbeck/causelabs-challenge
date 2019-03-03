<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostToApiTest extends TestCase
{
    /** @var string */
    protected $jsonString = '{"data":[{"first_name":"cody","last_name":"duder","age":38,"email":"codyduder@causelabs.com","secret":"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="},{"first_name":"ladee","last_name":"linter","age":99,"email": "lindaladee@causelabs.com","secret":"cmVzb3VyY2UgdmlvbGF0aW9u"}]}';

    /** @test */
    public function it_validates_post_data_as_json()
    {
        $response = $this->json('POST', '/api/v1/people', [
            'people' => "Hello I am not JSON and should trigger a 422 http response"
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_returns_a_success_response_with_data_modified_as_required()
    {
        $response = $this->json('POST', '/api/v1/people', [
            'people' => $this->jsonString
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'People saved!',
                'data' => [
                    "email_addresses" => "[\"lindaladee@causelabs.com\",\"codyduder@causelabs.com\"]",
                    "original_data" => "[{\"first_name\":\"ladee\",\"last_name\":\"linter\",\"age\":99,\"email\":\"lindaladee@causelabs.com\",\"secret\":\"cmVzb3VyY2UgdmlvbGF0aW9u\",\"name\":\"ladee linter\"},{\"first_name\":\"cody\",\"last_name\":\"duder\",\"age\":38,\"email\":\"codyduder@causelabs.com\",\"secret\":\"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM=\",\"name\":\"cody duder\"}]",
                    // "ip_address" => request()->ip(),
                    // "updated_at" => "2019-03-03 20:08:27",
                    // "created_at" => "2019-03-03 20:08:27",
                ]
            ]);
    }
}
