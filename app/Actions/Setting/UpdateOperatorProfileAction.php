<?php

namespace App\Actions\Setting;

use App\Models\Operator;
use Illuminate\Support\Facades\Hash;

class UpdateOperatorProfileAction
{
    /**
     * Memperbarui profil operator (TU)
     *
     * @param  \Illuminate\Http\UploadedFile|null  $photo
     */
    public function execute(int $operatorId, array $data, $photo = null): Operator
    {
        $operator = Operator::where('operator_id', $operatorId)->firstOrFail();

        $operator->name = $data['name'];
        $operator->username = $data['username'];

        if (! empty($data['new_password'])) {
            $operator->password = Hash::make($data['new_password']);
        }

        if ($photo) {
            // Hapus foto lama jika ada
            if ($operator->photo_url && file_exists(public_path($operator->photo_url))) {
                unlink(public_path($operator->photo_url));
            }

            // Simpan foto baru
            $filename = time().'_'.$photo->getClientOriginalName();
            $photo->move(public_path('img/operator'), $filename);
            $operator->photo_url = 'img/operator/'.$filename;
        }

        $operator->save();

        return $operator;
    }
}
