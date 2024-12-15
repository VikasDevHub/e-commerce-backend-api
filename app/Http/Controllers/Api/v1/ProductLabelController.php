<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ProductLabel;
use App\Services\v1\Service\TagService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductLabelController extends Controller
{

    protected $tagService;
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $tags = $this->tagService->getAllTags();

            return sendResponse('Tag List', $tags->toArray());

        }catch (\Exception $e)
        {
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'product_tag' => 'required|string|min:2|max:255',
            ]);

            if ($validator->fails())
            {
                $error = trans('errors.validation_error');

                return sendError($error['message'], $validator->errors()->toArray(), $error['status_code']);
            }

            $data = $validator->validated();

            $tag = $this->tagService->addTag($data);

            return sendResponse('Tag is created successfully', $tag->toArray());

        }
        catch (\Exception $e)
        {
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $tagId)
    {
        try
        {
            $tag = $this->tagService->getTagById($tagId);

            if (is_null($tag))
            {
                $error = trans('errors.tag_not_found');

                return sendError($error['message'], [], $error['status_code']);
            }

            return sendResponse('Tag Info', $tag->toArray());

        }

        catch (\Exception $e)
        {

            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductLabel $productLabel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $tagId)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'product_tag' => 'required|string|min:2|max:255',
            ]);

            if ($validator->fails())
            {
                $error = trans('errors.validation_error');

                return sendError($error['message'], $validator->errors()->toArray(), $error['status_code']);
            }

            $data = $validator->validated();

            $tag = $this->tagService->updateTag($tagId, $data);

            if (is_null($tag))
            {
                $error = trans('errors.tag_not_found');

                return sendError($error['message'], [], $error['status_code']);
            }

            return sendResponse('Tag is updated successfully', $tag->toArray());

        }
        catch (\Exception $e)
        {
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $tagId)
    {
        try {

            $tag = $this->tagService->deleteTag($tagId);

            if (is_null($tag))
            {
                $error = trans('errors.tag_not_found');

                return sendError($error['message'], [], $error['status_code']);
            }

            return sendResponse('Tag is Deleted Successfully', $tag->toArray());

        }catch (\Exception $e)
        {
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }
}
