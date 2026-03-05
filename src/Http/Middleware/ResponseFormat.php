<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseFormat
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Skip formatting for streamed or binary file responses (e.g., downloads, ZipStream)
        if (
            $response instanceof BinaryFileResponse ||
            $response instanceof StreamedResponse ||
            $response instanceof Stream ||
            $request->is('*api-docs*') ||
            $request->is('*documentation*')
        ) {
            return $response;
        }

        // Skip formatting for direct PDF output (e.g., Browsershot)
        if (
            $response instanceof Response &&
            $response->headers->get('Content-Type') === 'application/pdf'
        ) {
            return $response;
        }

        // Only handle JSON success responses (200, 201, 204)
        if (
            ($response instanceof JsonResponse || $response instanceof Response) &&
            in_array((int) $response->status(), [200, 201, 204])
        ) {
            $data = $response instanceof JsonResponse ? $response->getData(true) : $response->getContent();

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            // If data is not an array (after decoding if it was a string), it's not something we want to format
            if (! is_array($data)) {
                return $response;
            }

            $responseData = array_key_exists('data', $data) ? $data['data'] : $data;

            $message = $data['__message'] ?? $data['message'] ?? null;
            if ($message === null && isset($response->original) && is_array($response->original)) {
                $message = $response->original['__message'] ?? $response->original['message'] ?? null;
            }
            if ($message === null && is_array($responseData) && isset($responseData['__message'])) {
                $message = $responseData['__message'];
            }
            if ($message === null && is_array($responseData) && isset($responseData['message'])) {
                $message = $responseData['message'];
            }

            if (is_array($responseData)) {
                unset($responseData['__message'], $responseData['message']);
            }

            if ($responseData === null) {
                $responseData = (object) [];
            }

            // Create formatted response, preserving original data structure via array_merge
            $resp = array_merge($data, [
                'success'   => true,
                'data'      => $responseData,
                '__message' => $message,
            ]);

            unset($resp['message']);

            // Format meta if available (pagination)
            if (isset($data['meta']['links'])
                && is_array($data['meta']['links'])) {
                $meta = $data['meta'];

                // Try to get next/prev from top-level links (Standard JSON Resource)
                $nextUrl = null;
                $prevUrl = null;

                if (isset($data['links'])) {
                    $nextUrl = $data['links']['next'] ?? null;
                    $prevUrl = $data['links']['prev'] ?? null;
                } else {
                    // Fallback: Try to parse meta['links'] by label
                    // "Next" is usually the last element, "Previous" the first.
                    $lastLink = end($meta['links']);
                    if ($lastLink && isset($lastLink['label']) && stripos($lastLink['label'], 'Next') !== false) {
                        $nextUrl = $lastLink['url'];
                    }

                    $firstLink = reset($meta['links']);
                    if ($firstLink && isset($firstLink['label']) && stripos($firstLink['label'], 'Previous') !== false) {
                        $prevUrl = $firstLink['url'];
                    }
                }

                $meta['previous'] = ! is_null($prevUrl);
                $meta['next'] = ! is_null($nextUrl);
                $meta['next_link'] = $nextUrl;

                unset($meta['links']);
                unset($meta['path']);

                $resp['meta'] = $meta;
                
                // Keep 'data' clean in a paginated response
                if (isset($resp['data']['meta'])) {
                    unset($resp['data']['meta']);
                }

                unset($resp['meta']['data']);
                unset($resp['meta']['first_page_url']);
                unset($resp['meta']['last_page_url']);
                unset($resp['meta']['next_page_url']);
                unset($resp['meta']['prev_page_url']);
            }

            return response()->json($resp, $response->status(), [], JSON_PRESERVE_ZERO_FRACTION);
        }

        // Return all other responses (including errors/exceptions) unchanged
        return $response;
    }
}
