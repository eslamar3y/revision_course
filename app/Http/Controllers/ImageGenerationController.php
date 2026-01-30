<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePromptRequest;
use App\Models\ImageGeneration;
use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageGenerationController extends Controller
{
    public function __construct(private OpenAiService $openAiService) {}


    public function index() {}

    public function store(GeneratePromptRequest $request)
    {
        $user = $request->user();
        $image = $request->file('image');

        $originalFilename = $image->getClientOriginalName();
        $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '-', pathinfo($originalFilename, PATHINFO_FILENAME));
        $extension = $image->getClientOriginalExtension();
        $safeFilename = $sanitizedName . '_' . Str::random(10) . '.' . $extension;
        $imagePath = $image->storeAs('uploads/images', $safeFilename, 'public');
        $generatedPrompt = $this->openAiService->generatePromptFromImage($image);
        $imageGeneration = ImageGeneration::create([
            'user_id' => $user->id,
            'image_path' => $imagePath,
            'generated_prompt' => $generatedPrompt,
            'original_filename' => $originalFilename,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
        ]);
        return response()->json($imageGeneration, 201);
    }
}
