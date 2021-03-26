<?php

namespace BrianHenryIE\WC_My_Host;

use PHPUnit\Framework\TestCase;

class MyHostTest extends TestCase
{

    public function test_digital_ocean () {
        $sut = new My_Host();

        $ip_blog_brianhenry_ie = "165.232.159.3";

        $result = $sut->get_host_provider( $ip_blog_brianhenry_ie );

        $this->assertEquals( 'DigitalOcean', $result );
    }

    public function test_nexcess () {
        $sut = new My_Host();

        $ip_nexcess = '173.249.147.68';

        $result = $sut->get_host_provider( $ip_nexcess );

        $this->assertEquals( 'Liquid Web/Nexcess', $result );
    }

    public function test_siteground () {

        $sut = new My_Host();

        $ip_siteground = '35.208.11.105';

        $result = $sut->get_host_provider( $ip_siteground );

        $this->assertEquals( 'SiteGround', $result );
    }



}

