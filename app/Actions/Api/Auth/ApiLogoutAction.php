<?php

namespace App\Actions\Api\Auth;

use Illuminate\Http\Request;

class ApiLogoutAction
{
    /**
     * Delete the current access token for the authenticated user.
     */
    public function execute(Request $request): array
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'success' => true,
            'message' => 'Logout berhasil',
        ];
    }
}
