<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleAttachmentRequest;
use App\Models\Article;
use App\Models\ArticleAttachment;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articleAttachments = ArticleAttachment::all();
            return response()->json([
                'result' => $articleAttachments,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleAttachmentRequest $request)
    {
        try {
            $article = Article::find($request->article);
            $attachment = Attachment::find($request->attachment);
            $articleAttachment = new ArticleAttachment($request->all());
            if (
                $articleAttachment->article()->associate($article) &&
                $articleAttachment->attachment()->associate($attachment) &&
                $articleAttachment->save()
            ) {
                $result = $articleAttachment;
                $msg = Str::ucfirst(__('article attachment was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('article attachment was not successfully added'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ArticleAttachment $articleAttachment)
    {
        try {
            return response()->json([
                'result' => $articleAttachment,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleAttachmentRequest $request, ArticleAttachment $articleAttachment)
    {
        try {
            if ($articleAttachment->update($request->all())) {
                $result = $articleAttachment;
                $msg = Str::ucfirst(__('article attachment was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('article attachment was not successfully updated'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleAttachment $articleAttachment)
    {
        try {
            if ($articleAttachment->delete()) {
                $result = $articleAttachment;
                $msg = Str::ucfirst(__('article attachment was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('article attachment was not successfully deleted'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
