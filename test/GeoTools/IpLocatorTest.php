<?php
namespace LeroyMerlin\GeoTools;

use Mockery as m;

class IpLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldGetLocation()
    {
        // Set
        $ip = '123.456.789.10';
        $curl = m::mock('Curl\Curl');
        $locator = new IpLocator($curl);

        // Expectation
        $curl->shouldReceive('close');
        $curl->shouldReceive('get')
            ->once()
            ->with('http://ip-api.com/json/'.$ip)
            ->andReturn();


        $curl->error = false;
        $curl->response = '{"success":true}';

        // Assertion
        $result = $locator->getLocation($ip);
        $this->assertEquals(
            ['success'=>true],
            $result
        );
    }

    public function testShouldGetCoordinates()
    {
        // Set
        $ip = '123.456.789.10';
        $curl = m::mock('Curl\Curl');
        $locator = m::mock(
            'LeroyMerlin\GeoTools\IpLocator[getLocation]',
            [$curl]
        );

        // Expectation
        $curl->shouldReceive('close');
        $locator->shouldReceive('getLocation')
            ->once()
            ->with($ip)
            ->andReturn(['lat'=>123, 'lon'=>456]);

        // Assertion
        $this->assertEquals(
            ['x'=>123, 'y'=>456],
            $locator->getCoordinates($ip)
        );
    }
}
