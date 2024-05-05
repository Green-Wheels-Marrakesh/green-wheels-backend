<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonAttachmentRequest;
use App\Models\Attachment;
use App\Models\Person;
use App\Models\PersonAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $personAttachments = PersonAttachment::all();
            return response()->json([
                'result' => $personAttachments,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonAttachmentRequest $request)
    {
        try {
            $person = Person::find($request->person);
            $attachment = Attachment::find($request->attachment);
            $personAttachment = new PersonAttachment($request->all());
            if (
                $personAttachment->person()->associate($person) &&
                $personAttachment->attachment()->associate($attachment) &&
                $personAttachment->save()
            ) {
                $result = $personAttachment;
                $msg = Str::ucfirst(__('person attachment was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person attachment was not successfully added'));
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
    public function show(PersonAttachment $personAttachment)
    {
        try {
            return response()->json([
                'result' => $personAttachment,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonAttachmentRequest $request, PersonAttachment $personAttachment)
    {
        try {
            if ($personAttachment->update($request->all())) {
                $result = $personAttachment;
                $msg = Str::ucfirst(__('person attachment was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person attachment was not successfully updated'));
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
    public function destroy(PersonAttachment $personAttachment)
    {
        try {
            if ($personAttachment->delete()) {
                $result = $personAttachment;
                $msg = Str::ucfirst(__('person attachment was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person attachment was not successfully deleted'));
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
