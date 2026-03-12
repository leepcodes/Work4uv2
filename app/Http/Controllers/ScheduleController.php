<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService,
    ) {}

    public function mytutor()
    {
        $classes = $this->scheduleService->getTutorList();

        return Inertia::render('landingpage/student/mytutor', [
            'classes' => $classes,
        ]);
    }

    public function mystudents()
    {
        $students = $this->scheduleService->getStudentList();

        return Inertia::render('landingpage/tutor/mystudents', [
            'students' => $students,
        ]);
    }

    public function studentSubjects(string $uuid)
    {
        $data = $this->scheduleService->getStudentWithSubjects($uuid);

        return Inertia::render('landingpage/tutor/studentsubj', $data);
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer',
            'tutor_id'           => 'required|integer',
            'total_class_count'  => 'required|integer|min:1',
            'tutor_custom_price' => 'required|numeric|min:0',
        ]);

        $this->scheduleService->enroll($validated);

        return back()->with('success', 'Enrolled successfully!');
    }

    // TUTOR: package classes
    public function packageClasses(string $uuid)
    {
        $data = $this->scheduleService->getPackageClasses($uuid);

        return Inertia::render('landingpage/tutor/packageclasses', [
            'student' => $data['student'],  // ← wrong key name
            'subject' => $data['subject'],
            'package' => $data['schedule'],
            'classes' => $data['classes'],
        ]);
    }

    // STUDENT: package classes
    public function studentPackageClasses(string $uuid)
    {
        $data = $this->scheduleService->getStudentPackageClasses($uuid);
        
        
        return Inertia::render('landingpage/student/packageclasses', [
            'tutor'   => $data['tutor'],
            'subject' => $data['subject'],
            'package' => $data['schedule'],
            'classes' => $data['classes'],
        ]);
    }

    public function tutorandsubj(string $uuid)
    {
        $data = $this->scheduleService->getStudentSubjectsAndPackages($uuid);

        return Inertia::render('landingpage/student/tutorsandsubject', [
            'tutor'    => $data['tutor'],
            'subjects' => $data['subjects'],
        ]);
    }

    // TUTOR: single class details
   public function tutorClassDetails(string $uuid, string $classUuid)
    {
        $data = $this->scheduleService->getTutorClassDetails($uuid, $classUuid);
        return Inertia::render('landingpage/tutor/classdetails', $data);
    }

    // STUDENT: single class details
  
    public function studentClassDetails(string $uuid, string $classUuid)
    {
        $data = $this->scheduleService->getStudentClassDetails($uuid, $classUuid);
        return Inertia::render('landingpage/student/classdetails', $data);
    }

    // TUTOR CALENDAR
    
    public function calendarData()
    {
        $data = $this->scheduleService->getTutorCalendarData();
        return response()->json($data);
    }

    // STUDENT CALENDAR
    public function studentCalendarData(string $uuid)
    {
        $data = $this->scheduleService->getStudentCalendarData($uuid);
        return response()->json($data);
    }

    //POST SA CALENDAR

    public function updateClass(Request $request, string $uuid)
{
        $request->validate([
            'schedule'        => 'nullable|date',
            'scheduled_time'  => 'nullable|string',
            'video_link'      => 'nullable|string',
            'notes'           => 'nullable|array',
            'notes.*'         => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'documents'       => 'nullable|array',
            'documents.*'     => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $this->scheduleService->updateClass(
            $request->except('documents'),
            $request->file() ?? [],
            $uuid
    );

    return back()->with('success', 'Class saved successfully!');
}

}