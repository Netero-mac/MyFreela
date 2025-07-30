<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // Importe o model User
use NeteroMac\MeuFreela\Models\Client; // Importe o model Client
use PHPUnit\Framework\Attributes\Test;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase; // Essencial! Reseta o banco de dados antes de cada teste.

    #[Test]
    public function an_authenticated_user_can_view_the_project_creation_page()
    {
        // Arrange: Preparar o ambiente
        $user = User::factory()->create();

        // Act & Assert: Agir e Verificar
        $this->actingAs($user) // Autentica o usuário para a requisição
             ->get(route('projects.create')) // Acessa a rota de criação de projeto
             ->assertStatus(200) // Verifica se a página carregou com sucesso
             ->assertSee('Novo Projeto'); // Verifica se um texto chave da página está presente
    }

    #[Test]
    public function an_authenticated_user_can_create_a_project()
    {
        // 1. Arrange (Preparação)
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]); // Cria um cliente para o usuário
        $this->actingAs($user); // Autentica o usuário

        $projectData = [
            'title' => 'Criação de Logo',
            'description' => 'Descrição detalhada do projeto de logo.',
            'value' => 1500.50,
            'deadline' => '2025-12-31',
            'client_id' => $client->id,
            'user_id' => $user->id,
        ];

        // 2. Act (Ação)
        $response = $this->post(route('projects.store'), $projectData);

        // 3. Assert (Verificação)
        // Verifica se o projeto foi efetivamente salvo no banco de dados com os dados corretos
        $this->assertDatabaseHas('projects', [
            'title' => 'Criação de Logo',
            'user_id' => $user->id,
            'client_id' => $client->id
        ]);

        // Verifica se o usuário foi redirecionado para a listagem de projetos com uma mensagem de sucesso
        $response->assertRedirect(route('projects.index'))
                 ->assertSessionHas('success', 'Projeto criado com sucesso!');
    }
}