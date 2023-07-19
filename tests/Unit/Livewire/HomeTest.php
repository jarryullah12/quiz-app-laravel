<?php

namespace Tests\Unit\Livewire;

use App\Http\Livewire\Home;
use App\Http\Livewire\Users;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Exceptions\BypassViewHandler;
use Livewire\Livewire;
use Mockery\Mock;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * @var Users
     */
    private $usersComponent;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function test_render_home_page()
    {
        $this->withoutExceptionHandling();

        Livewire::test(Home::class)
            ->call('render')
            ->assertViewIs('livewire.home');
    }

    public function test_can_store_classroom_students()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        Livewire::actingAs($user);
        $classroom = Classroom::factory()->create(['teacher_id' => $user->id, 'classroom_unique_id' => rand(1, 10)]);

        Livewire::test(Home::class)
            ->set('classroom_id', $classroom->classroom_unique_id)
            ->call('store')
            ->assertPayloadNotSet('classroom_id', 'test')
            ->assertViewIs('livewire.home')
            ->assertRedirect(route('classroom.show', $classroom->id));

    }
}