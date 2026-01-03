<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

use function PHPUnit\Framework\isEmpty;

class SubmissionController extends Controller
{
    public function store(SubmissionRequest $request, Classwork $classwork)
    {
        $this->authorize('create', [Submission::class, $classwork]);
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $data = [];
            foreach ($request->file('files') as $file) {
                $data[] = [
                    'classwork_id' => $classwork->id,
                    'content' => $file->store("submission/{$classwork->id}"),
                    'type' => 'file',
                ];
            }
            $user = Auth::user();
            $user->submissions()->createMany($data);
            ClassworkUser::where([
                'user_id' => $user->id,
                'classwork_id' => $classwork->id,
            ])->update([
                'status' => 'submitted',
                'submitted_at' => now()
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Work submitted successfully');
    }

    public function file(Submission $submission)
    {
        $this->authorize('view', $submission);
        return response()->file(storage_path('app/private/' . $submission->content));
    }
}
