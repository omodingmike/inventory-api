<?php

namespace App\helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Uploads
{
    public static function upload_image(Request $request): string
    {
        $uploaded_image = $request->file('photo');
        $filename       = 'public/images/' . time() . '.' . $uploaded_image->getClientOriginalExtension();
        // Create an instance of the Intervention Image
        $image = Image::make($uploaded_image);
        // Resize the image if needed
        $image->resize(100, 100);
        Storage::put($filename, $image->encode());
//        $validated['photo'] = url('/') . Storage::url($filename);
        return url('/') . Storage::url($filename);
    }

}
