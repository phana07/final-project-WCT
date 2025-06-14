<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // Corrected import
use App\Http\Resources\StudentResource;
use App\Http\Resources\CourseResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StudentResource::collection(Student::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'date_of_birth' => 'nullable|date',
        ]);

        $student = Student::create($validatedData);
        return new StudentResource($student); // Laravel resource handles 200 OK by default, you can chain ->response()->setStatusCode(Response::HTTP_CREATED) if you want 201
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return new StudentResource($student->load('courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:students,email,' . $student->id,
            'date_of_birth' => 'nullable|date',
        ]);

        $student->update($validatedData);
        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->noContent(); // Laravel helper for 204 No Content
    }

    /**
     * Display the courses a student is enrolled in.
     */
    public function courses(Student $student)
    {
        return CourseResource::collection($student->courses);
    }

    /**
     * Enroll a student in a course.
     */
    public function enroll(Request $request, Student $student)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student->courses()->syncWithoutDetaching([$request->course_id]);
        return response()->json(['message' => 'Student enrolled in course successfully.']);
    }

    /**
     * Unenroll a student from a course.
     */
    public function unenroll(Request $request, Student $student)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student->courses()->detach($request->course_id);
        return response()->json(['message' => 'Student unenrolled from course successfully.']);
    }
}
