<?php


namespace App\Controller\Refactor;


use App\DiscogsApi\DiscogsClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InventoryFunctionsController extends AbstractController
{

    public function getPriceSuggestion(DiscogsClient $dc, $item) {
        $suggestedPrices = [];
        $suggestions = $dc->getMyDiscogsClient()->getPriceSuggestion($item->release->id);
        foreach ($suggestions as $suggestion) {
            $suggestedPrices[] = $suggestion;
        }
        $condition = $item->condition;
        $suggested = 0;
        switch ($condition) :
            case 'Mint (M)' : $suggested = $suggestedPrices[0]->value; break;
            case 'Near Mint (NM or M-)' : $suggested = $suggestedPrices[1]->value; break;
            case 'Very Good Plus (VG+)' : $suggested = $suggestedPrices[2]->value; break;
            case 'Very Good (VG)' : $suggested = $suggestedPrices[3]->value; break;
            case 'Good Plus (G+)' : $suggested = $suggestedPrices[4]->value; break;
            case 'Good (G)' : $suggested = $suggestedPrices[5]->value; break;
            case 'Fair (F)' : $suggested = $suggestedPrices[6]->value; break;
            case 'Poor (P)' : $suggested = $suggestedPrices[7]->value; break;
        endswitch;

        return $suggested;
    }
}