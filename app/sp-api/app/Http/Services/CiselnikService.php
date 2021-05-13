<?php


namespace App\Http\Services;

use App\Dto\CiselnikDTO;
use App\Http\Services\AS\CiselnikAS;
use App\Models\Ciselnik;
use Exception;
use JsonMapper\JsonMapper;

class CiselnikService
{
    private JsonMapper $mapper;
    private CiselnikAS $ciselnikAS;

    public function __construct(
        JsonMapper $mapper,
        CiselnikAS $ciselnikAS,
    )
    {
        $this->mapper = $mapper;
        $this->ciselnikAS = $ciselnikAS;
    }

    /**
     * @param string $type
     * @return CiselnikDTO[]
     * @throws Exception
     */
    public function getType(string $type): array
    {
        if (!$this->isValidType($type)) {
            throw new Exception("type not found", 404);
        }

        $ciselniky = Ciselnik::whereType($type)->get();
        return $this->ciselnikAS->mapCiselnikCollection($ciselniky);
    }

    public function create(CiselnikDTO $dto): bool
    {
        if (!$this->isValidType($dto->type)) {
            throw new Exception("type not found", 404);
        }

        $c = new Ciselnik;
        $c->group = $dto->group;
        $c->label = $dto->label;
        $c->type = $dto->type;

        return $c->save();
    }

    public function isValidType(string $type): bool
    {
        return in_array($type, CiselnikDTO::$types);
    }

    public function getOrCreateCiselnik(?CiselnikDTO $dto, string $newDtoType = null, string $newDtoGroup = null): Ciselnik
    {
        if (!$dto || !$dto->label) {
            throw new Exception("must not be null", 500);
        }

        if ($newDtoType && !$this->isValidType($newDtoType)) {
            throw new Exception("must be valid type", 500);
        }

        if (isset($dto->id)) {
            return Ciselnik::whereId($dto->id)->first();
        }

        // ak neexistuje, vytvorim novy
        $dto->type = $newDtoType;
        $dto->group = $newDtoGroup;

        $c = new Ciselnik;
        $c->group = $dto->group;
        $c->type = $dto->type;
        $c->label = $dto->label;

        if (!$c->save()) {
            throw new Exception("could not save ciselnik", 500);
        }

        return $c;
    }

}
