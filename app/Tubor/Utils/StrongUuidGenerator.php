<?php
namespace App\Turbor\Utils;
use Ramsey\Uuid\Uuid;

class StrongUuidGenerator {
    public function getNextId() {
        return Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON)->toString();
    }
}