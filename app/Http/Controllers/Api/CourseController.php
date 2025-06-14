<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // Corrected import
use App\Http\Resources\CourseResource;
use App\Http\Resources\StudentResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CourseResource::collection(Course::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $course = Course::create($validatedData);
        return new CourseResource($course);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return new CourseResource($course->load(['teacher', 'students']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $course->update($validatedData);
        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->noContent();
    }

    /**
     * Display the students enrolled in the specified course.
     */
    public function students(Course $course)
    {
        return StudentResource::collection($course->students);
    }

    /**
     * Enroll a student in a course.
     */
    public function enrollStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $course->students()->syncWithoutDetaching([$request->student_id]);
        return response()->json(['message' => 'Student enrolled in course successfully.']);
    }

    /**
     * Unenroll a student from a course.
     */
    public function unenrollStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $course->students()->detach($request->student_id);
        return response()->json(['message' => 'Student unenrolled from course successfully.']);
    }
}
