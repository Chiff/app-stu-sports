<?php


namespace App\Http\Services\AS;


use App\Dto\CiselnikDTO;
use App\Models\Ciselnik;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperInterface;

class CiselnikAS
{
    private JsonMapper $mapper;

    public function __construct(
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
    }

    public static function toDto(int|CiselnikDTO|\stdClass $value, JsonMapperInterface $mapper): CiselnikDTO
    {
        if (is_a($value, 'CiselnikDTO')) {
            return $value;
        }

        if (is_a($value, 'stdClass')) {
            $dto = new CiselnikDTO();
            $mapper->mapObject($value, $dto);

            return $dto;
        }

        $c = Ciselnik::whereId($value)->get()->first();
        $dto = new CiselnikDTO();

        if ($c) {
            $mapper->mapObjectFromString($c->toJson(), $dto);
        }

        return $dto;
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
