<?php
//		tests/AppBundle/Controller/DefaultControllerTest.php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
	private $client = null;

	public function setUp()
	{
		$this->client = static::createClient();
	}

	/**
	 * @dataProvider invalideDates
	 */
	public function testInvalidateDate($date)
	{
        $crawler = $this->client->request('GET', '/reservation');
        static::assertSame(1, $crawler->filter('h1:contains("Réservation")')->count());

        $form = $crawler->selectButton('Continuer')->form();
        $form['appbundle_order[bookingDate]'] = $date;
        $form['appbundle_order[wholeDay]'] = true;
        $form['appbundle_order[quantity]'] = 1;
        $this->client->submit($form);

        $this->expectException('LogicException');
        $crawler = $this->client->followRedirect();
	}

	public function invalideDates()
	{
		return [
			['01-01-2018'], // Past day
			['30-10-2018'], // Tuesday
			['01-11-2018'], // Closing day
			['01-05-2019'], // Closing day
			['01-11-2019'],  // Closing day
			['25-12-2019'] // Closing day
		];
	}

	/**
	 * @dataProvider routesToTest
	 */
	public function testInaccessibleRoutes($route)
	{
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', $route);

        static::assertSame(1, $crawler->filter('h1:contains("Réservation")')->count());
	}

	public function routesToTest()
	{
		return [
			['/informations'],
			['/recapitulatif'],
			['/confirmation']
		];
	}

	public function testPurchaseTunnel()
	{
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', '/index');
		static::assertSame(1, $crawler->filter('h1:contains("Exposition du Modern Era")')->count());

		$link = $crawler->selectLink('Réserver')->link();
		$crawler = $this->client->click($link);
		static::assertSame(1, $crawler->filter('h1:contains("Réservation")')->count());

		// Tests for booking
		$form = $crawler->selectButton('Continuer')->form();
		$form['appbundle_order[bookingDate]'] = '12-10-2018';
		$form['appbundle_order[wholeDay]'] = false;
		$form['appbundle_order[quantity]'] = 1;
		$crawler = $this->client->submit($form);
		static::assertSame(1, $crawler->filter('h1:contains("Informations")')->count());

		// Tests for information form + summary page
		$form = $crawler->selectButton('Continuer')->form();
		$form['appbundle_order[tickets][0][name]'] = 'Husseini';
		$form['appbundle_order[tickets][0][lastName]'] = 'Mbenguia';
		$form['appbundle_order[tickets][0][country]']->select('FR');
		$form['appbundle_order[tickets][0][birthDate]'] = '22/05/1993';
		$form['appbundle_order[email]'] = 'mbenguia.husseini@live.fr';
		$form['appbundle_order[cgv]']->tick();
		$crawler = $this->client->submit($form);
		static::assertSame(1, $crawler->filter('h1:contains("Récapitulatif")')->count());
		static::assertSame(1, $crawler->filter('h4:contains("Total à payer : 16.00 €")')->count());
		static::assertSame(1, $crawler->filter('h4:contains("Mbenguia Husseini")')->count());
		static::assertSame(1, $crawler->filter('h4:contains("mbenguia.husseini@live.fr")')->count());
		static::assertSame(1, $crawler->filter('p:contains("22-05-1993")')->count());
		static::assertSame(1, $crawler->filter('p:contains("FR")')->count());
		static::assertSame(1, $crawler->filter('p:contains("Billet normal")')->count());

		// Tests for return back
		$link = $crawler->selectLink('Modifier les informations')->link();
		$crawler = $this->client->click($link);
		static::assertSame(1, $crawler->filter('h1:contains("Informations")')->count());
		static::assertSame('Husseini', $crawler->filter('input[type=text]')->attr('value'));
		static::assertSame('mbenguia.husseini@live.fr', $crawler->filter('input[type=email]')->attr('value'));
	}

	public function tearDown()
	{
		$this->client = null;
	}
}