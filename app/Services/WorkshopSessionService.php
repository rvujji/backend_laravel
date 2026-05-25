<?php

namespace App\Services;

use App\Models\WorkshopSession;

class WorkshopSessionService
{
    public function create(array $data): WorkshopSession
    {
        return WorkshopSession::create($data);
    }

    public function update(
        WorkshopSession $session,
        array $data
    ): WorkshopSession {

        $session->update($data);

        return $session->fresh();
    }

    public function delete(
        WorkshopSession $session
    ): bool {

        return $session->delete();
    }
}
