<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhishaGiftControllerTest extends WebTestCase
{
    public function testEdit()
    {
        $client = static::createClient();

        $editPage = $client->request('GET', '/edit/224cc6f3-c306-4e17-90e8-45bb8ea9cbe0');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $Form = $editPage->filter('#createForm')->selectButton("Save")->form();
        $Form['gift_list[firstName]'] = 'John';
        $Form['gift_list[title]'] = 'Crazy on the coast';
//        $Form['gift_list[gifts][0][title]'] = 'Engraved glass';
        $Form['gift_list[description]'] = 'Public gift list';
        $client->submit($Form);

        $redirectaftersubmit = $client->followRedirect();

        $redirectaftersubmit->filter('a:contains("Edit")')->link();
        $this->assertContains('Share with Friends', $client->getResponse()->getContent());

        $expectedFirstName = $redirectaftersubmit->filter('.section-heading')->first()->text();
        $expectedTitle = $redirectaftersubmit->filter('.d-inline-block .d-inline')->first()->text();
//        $expectedGift = $redirectaftersubmit->filter('.input-group > .form-control:not(:last-child)')->text();
        $expectedDescription = $redirectaftersubmit->filter('.col-md-12 .d-inline-block p.text-muted')->first()->text();
        $this->assertEquals(strtok($expectedFirstName, ' '), 'John', 'First Name');
        $this->assertEquals($expectedTitle, 'Crazy on the coast', 'Title');
//        $this->assertEquals($expectedGift, 'Engraved glass', 'Gift');
        $this->assertEquals($expectedDescription, 'Public gift list', 'Description');
    }
}
