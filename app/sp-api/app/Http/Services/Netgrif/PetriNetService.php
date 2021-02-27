<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class PetriNetService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'getAllUsingGET' => 'api/petrinet',
            'getDataFieldReferencesUsingPOST' => 'api/petrinet/data',
            'importPetriNetUsingPOST' => 'api/petrinet/import',
            'searchPetriNetsUsingPOST' => 'api/petrinet/search',
            'getTransitionReferencesUsingGET' => 'api/petrinet/transitions',
            'getOneUsingGET' => 'api/petrinet/{identifier}/{version}',
            'deletePetriNetUsingDELETE' => 'api/petrinet/{id}',
            'getOneUsingGET1' => 'api/petrinet/{id}',
            'getNetFileUsingGET' => 'api/petrinet/{netId}/file',
            'getRolesUsingGET' => 'api/petrinet/{netId}/roles',
            'getTransactionsUsingGET' => 'api/petrinet/{netId}/transactions',
        ];
    }
}

