<?php
/**
 * Created by PhpStorm.
 * Date: 12/05/2018
 * Time: 17:59
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Model\Postnumber;

use Marcuspi\Postnummer\Model\Postnumber;
use Marcuspi\Postnummer\Model\PostnumberFactory;
use PHPUnit\Framework\TestCase;

class PostnumberCreationTest extends TestCase
{
    protected $factory;

    protected function setUp()
    {
        $mockPostnumber = $this->createMock(Postnumber::class);

        $mockFactory = $this->createMock(PostnumberFactory::class);
        $mockFactory->method("create")
            ->willReturn($mockPostnumber);

        $this->factory = $mockFactory;
    }

    public function testSettersAndGetters()
    {
        $creation = new PostnumberCreation($this->factory);

        $this->assertSame($creation, $creation->setNumber(5146));
        $this->assertSame($creation, $creation->setArea("FYLLINGSDALEN"));
        $this->assertSame($creation, $creation->setType(Postnumber::TYPE_STREET));
        $this->assertSame($creation, $creation->setMunicipality("BERGEN"));
        $this->assertSame($creation, $creation->setMunicipalityNumber(1201));

        $this->assertEquals(5146, $creation->getNumber());
        $this->assertEquals("FYLLINGSDALEN", $creation->getArea());
        $this->assertEquals(Postnumber::TYPE_STREET, $creation->getType());
        $this->assertEquals("BERGEN", $creation->getMunicipality());
        $this->assertEquals(1201, $creation->getMunicipalityNumber());
    }

    public function testReturnsNewPostnumber()
    {
        $creation = new PostnumberCreation($this->factory);

        $creation->setNumber(5146)
            ->setArea("FYLLINGSDALEN")
            ->setType(Postnumber::TYPE_STREET)
            ->setMunicipality("BERGEN")
            ->setMunicipalityNumber(1201);

        $output = $creation->create();

        $this->assertInstanceOf(Postnumber::class, $output);
    }

    public function testThrowsExceptionOnMissingData()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp("/Missing data for instantiation(\:.*)?/");

        $creation = new PostnumberCreation($this->factory);

        $creation->setNumber(5146)
            ->setType(Postnumber::TYPE_STREET)
            ->setMunicipality("BERGEN")
            ->setMunicipalityNumber(1201);

        $output = $creation->create();
    }


}
