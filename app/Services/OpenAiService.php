<?php

namespace App\Services;

use OpenAI\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OpenAiService
{
    public function generatePromptFromImage(UploadedFile $image): string
    {
        $imageData = base64_encode(file_get_contents($image->getPathname()));
        $mimeType = $image->getMimeType();
        // $client = (new Factory())->make(config('services.openai'));
        $client = (new Factory())->withApiKey(config('services.openai.key'))->make();
        // $client = (new Factory())
        //     ->withApiKey(config('services.openai.key'))
        //     ->make();

        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            // 'model' => 'gpt-4o-mini',

            'messages' => [
                [
                    'role' => 'user',
                    'content' =>
                    [
                        'type' => 'text',
                        'text' => 'analyze the image and generate a detailed, descriptive prompt that could be used to create similar image with ai image generation tools. the prompt should be comprehensive, descriping the visual elements, style compossiton, lighting, and any other details that are present in the image, you must preserve aspect ration exact as the original image has or very close to it.'
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'data:' . $mimeType . ';base64,' . $imageData,
                        ],
                    ]
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }
}
