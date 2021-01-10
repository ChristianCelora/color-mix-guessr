<?php
namespace App\Services;
use App\Services\DTO\IDto;

interface IService{
    /**
     * Creates Service instance
     * @param IDto $dto data transfer object
     * @return IService
     */
    public static function make(IDto $dto): IService;
}