<?php

namespace App\Services;

use App\Models\Tenant;

class TenantContext
{
    protected ?Tenant $tenant = null;

    public function setTenant(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function getTenantId(): ?int
    {
        return $this->tenant?->id;
    }

    public function getName(): ?string
    {
        return $this->tenant?->name;
    }

    public function getType(): ?string
    {
        return $this->tenant?->type;
    }
}
