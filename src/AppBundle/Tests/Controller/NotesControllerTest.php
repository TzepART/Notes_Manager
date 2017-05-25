<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Notes;
use Liip\FunctionalTestBundle\Test\WebTestCase;


class NotesControllerTest extends WebTestCase
{
    use ControllerTestTrait;

    public function testCheckNewNotePageByCircle()
    {
        $client = $this->loadDefaultData();
        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/notes/new/'.$circle->getId());
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function testCheckNotesPageByCircle()
    {
        $client = $this->loadDefaultData();
        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/notes/list/'.$circle->getId().'/');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function testCheckEditNotePage()
    {
        $client = $this->loadDefaultData();
        $note = $this->returnEntityByUser($client,Notes::class);

        $client->request('GET',  '/notes/'.$note->getId().'/edit');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }



}
