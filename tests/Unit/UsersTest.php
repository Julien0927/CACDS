<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Users\Users;
use PDO;
use PDOStatement;

class UsersTest extends TestCase
{
    private $dbMock;
    private $users;
    private $statementMock;

    protected function setUp(): void
    {
        // Création des mocks
        $this->dbMock = $this->createMock(PDO::class);
        $this->statementMock = $this->createMock(PDOStatement::class);
        
        // Initialisation de l'objet Users
        $this->users = new Users($this->dbMock);
    }

    protected function tearDown(): void
    {
        // Nettoyage des sessions après chaque test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function testCreateUserSuccess()
    {
        // Arrangement
        $userData = [
            'name' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123',
            'role' => 'user',
            'sport_id' => 1,
            'poule_id' => 1
        ];

        // Configuration du mock pour vérifier si l'email existe déjà
        $this->dbMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->statementMock);

        // Premier appel pour vérifier l'email
        $this->statementMock->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        // Simuler qu'aucun utilisateur n'existe avec cet email
        $this->statementMock->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        // Action
        $result = $this->users->createUser($userData);

        // Assertion
        $this->assertTrue($result);
    }

    public function testCreateUserWithExistingEmail()
    {
        // Arrangement
        $userData = [
            'name' => 'Doe',
            'firstname' => 'John',
            'email' => 'existing@example.com',
            'password' => 'SecurePass123',
            'role' => 'user',
            'sport_id' => 1,
            'poule_id' => 1
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statementMock);

        $this->statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statementMock->expects($this->once())
            ->method('fetch')
            ->willReturn(['email' => 'existing@example.com']);

        // Action & Assertion
        $this->expectException(\RuntimeException::class);
        $this->users->createUser($userData);
    }

    public function testLoginSuccess()
    {
        // Arrangement
        $email = 'john.doe@example.com';
        $password = 'SecurePass123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'id' => 1,
            'name' => 'Doe',
            'firstname' => 'John',
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
            'sport_id' => 1,
            'poule_id' => 1
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statementMock);

        $this->statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statementMock->expects($this->once())
            ->method('fetch')
            ->willReturn($userData);

        // Action
        $result = $this->users->login($email, $password);

        // Assertions
        $this->assertTrue($result);
        $this->assertEquals($userData['id'], $_SESSION['user_id']);
        $this->assertEquals($userData['role'], $_SESSION['role']);
        $this->assertEquals($userData['sport_id'], $_SESSION['sport_id']);
        $this->assertEquals($userData['poule_id'], $_SESSION['poule_id']);
    }

    public function testLoginWithInvalidCredentials()
    {
        // Arrangement
        $email = 'john.doe@example.com';
        $password = 'WrongPassword';

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statementMock);

        $this->statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statementMock->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        // Action
        $result = $this->users->login($email, $password);

        // Assertion
        $this->assertFalse($result);
    }

    public function testCreateUserWithInvalidData()
    {
        // Arrangement
        $invalidUserData = [
            'name' => '', // Nom vide invalide
            'firstname' => 'John',
            'email' => 'invalid-email', // Email invalide
            'password' => '123', // Mot de passe trop court
            'role' => 'user',
            'sport_id' => 1,
            'poule_id' => 1
        ];

        // Action & Assertion
        $this->expectException(\InvalidArgumentException::class);
        $this->users->createUser($invalidUserData);
    }
}