<?php
/**
 * Created by PhpStorm.
 * Date: 12/05/2018
 * Time: 17:59
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Model\Postnumber;

use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Model\Postnumber;
use Marcuspi\Postnummer\Model\PostnumberFactory;
use PHPUnit\Framework\TestCase;

class PostnumberRepositoryTest extends TestCase
{
    protected $factory;
    protected $database;

    protected function setUp()
    {
        $mockNumber = $this->createMock(Postnumber::class);

        $mockCreation = $this->createMock(PostnumberCreation::class);
        $mockCreation->method("create")
            ->willReturn($mockNumber);

        $this->factory = $this->createMock(PostNumberCreationFactory::class);
        $this->factory->method("create")->willReturn($mockCreation);

        $this->database = $this->createMock(Database::class);
        $this->database->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => Postnumber::TYPE_COMBINED,
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $this->database->method("valid")->willReturn(true, false);

    }

    public function testCanGetOneFromId()
    {
        $repo = new PostnumberRepository(
            new Database(),
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("5146"));
    }

    public function testThrowsExceptionOnMissingNumber()
    {
        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessageRegExp("/^No such entity with .* = .*$/");

        $repo = new PostnumberRepository(
            new Database(),
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("10000"));
    }


    public function testCombinedType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "B",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testMultipleType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "F",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testStreetType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "G",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testBoxType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "P",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }


    public function testServiceType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "S",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }
}
