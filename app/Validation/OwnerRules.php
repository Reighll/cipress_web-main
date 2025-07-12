<?php

namespace App\Validation;

use App\Models\OwnerModel;

class OwnerRules
{
    /**
     * Checks if a given system key exists in the owner table.
     *
     * @param string $str The system key to check.
     *
     * @return bool
     */
    public function is_existing_system_key(string $str): bool
    {
        // If the string is empty, the 'required' rule will catch it.
        // This rule should only check for existence if a value is provided.
        if (empty($str)) {
            return true;
        }

        $ownerModel = new OwnerModel();

        $owner = $ownerModel->where('owner_systemkey', $str)->first();

        return $owner !== null;
    }
}
