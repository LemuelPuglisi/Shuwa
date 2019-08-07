<?php 

	require_once '../vendor/autoload.php';

	use Goutte\Client;

	// Documentation: 
	// https://goutte.readthedocs.io/en/latest/

	$client = new Client(); 

	$crawler = $client->request('GET', 'https://free-proxy-list.net/uk-proxy.html'); 
	$infos = $crawler->filter('tbody > tr')->each(function ($node){
		// var_dump($node); 
		foreach($node as $item)
		{
			echo $item->nodeValue . "\n"; 
		}
	}); 
	




	

