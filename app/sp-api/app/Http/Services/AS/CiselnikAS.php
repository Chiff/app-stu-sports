<?php


namespace App\Http\Services\AS;


use App\Dto\CiselnikDTO;
use App\Models\Ciselnik;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;

class CiselnikAS
{
    private JsonMapper $mapper;

    public function __construct(
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
    }

    /**
     * @param Collection $ciselniky
     * @return CiselnikDTO[]
     */
    public function mapCiselnikCollection(Collection $ciselniky): array
    {
        $result = [];

        foreach ($ciselniky as $ciselnik) {
            $dto = $this->mapCiselnik($ciselnik);
            array_push($result, $dto);
        }

        return $result;

    }

    public function mapCiselnik(Ciselnik $ciselnik): CiselnikDTO
    {
        $dto = new CiselnikDTO();
        $this->mapper->mapObjectFromString($ciselnik->toJson(), $dto);

        return $dto;
    }
}
