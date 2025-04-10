<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\News\News;
use PDO;
use PDOStatement;

class NewsTest extends TestCase
{
    private $dbMock;
    private $news;
    private $statementMock;

    protected function setUp(): void
    {
        // Création du mock PDO
        $this->dbMock = $this->createMock(PDO::class);
        
        // Création du mock PDOStatement
        $this->statementMock = $this->createMock(PDOStatement::class);
        
        // Initialisation de l'objet News avec le mock
        $this->news = new News($this->dbMock);
        
        // Simulation des données de session
        $_SESSION = [
            'sport_id' => 1,
            'poule_id' => 1,
            'user_id' => 1,
            'role' => 'admin'
        ];
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        $_SESSION = [];
    }

    public function testAddNewSuccess()
    {
        // Arrangement
        $newsData = [
            'title' => 'Test News',
            'content' => 'Test Content',
            'image' => 'test.jpg',
            'date' => '2025-02-20'
        ];

        $this->news->setTitle($newsData['title']);
        $this->news->setContent($newsData['content']);
        $this->news->setImage($newsData['image']);
        $this->news->setDate($newsData['date']);

        // Configuration du mock pour simuler une insertion réussie
        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statementMock);

        $this->statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Action
        $result = $this->news->addNew();

        // Assertion
        $this->assertTrue($result);
    }

    public function testGetAllNewsSuccess()
    {
        // Arrangement
        $expectedNews = [
            [
                'id' => 1,
                'title' => 'Test News',
                'content' => 'Test Content',
                'image' => 'test.jpg',
                'date' => '2025-02-20',
                'sport_id' => 1,
                'poule_id' => 1
            ]
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statementMock);

        $this->statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statementMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedNews);

        // Action
        $result = $this->news->getAllNews();

        // Assertion
        $this->assertEquals($expectedNews, $result);
    }

    public function testAddNewWithInvalidData()
    {
        // Arrangement
        $this->news->setTitle(''); // Titre vide invalide

        // Action & Assertion
        $this->expectException(\InvalidArgumentException::class);
        $this->news->addNew();
    }

    public function testAddNewWithoutRequiredSession()
    {
        // Arrangement
        $_SESSION = []; // Session vide
        $this->news->setTitle('Test Title');

        // Action & Assertion
        $this->expectException(\RuntimeException::class);
        $this->news->addNew();
    }
}