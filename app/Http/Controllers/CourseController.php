<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Episode;
use App\Youtube\YoutubeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function __construct()  
    {  
        $this->middleware('auth')->only('show');  
    }       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with('user')
        ->select('courses.*', DB::raw(
            '(
                SELECT COUNT(DISTINCT(user_id))
                FROM completions
                INNER JOIN episodes ON completions.episode_id = episodes.id
                WHERE episodes.course_id = courses.id
            ) AS participants'
        ))
        ->addSelect(DB::raw(
            '(
                SELECT SUM(duration)
                FROM episodes
                WHERE episodes.course_id = courses.id
            ) AS total_duration'
        ))
        ->withCount('episodes')->latest()->paginate(5);
        return Inertia::render('Courses/Index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request, YoutubeServices $ytb)
    {
        
        //$ytb will contain the api key that has been passed to the YoutubeServices constructer through the AppServiceProvider boot() method

        $course = Course::create($request->validated());

        foreach($request->input('episodes') as $episode){
            $episode['course_id'] = $course->id;
            $episode['duration'] = $ytb->handleYoutubeVideoDuration($episode['video_url']);
            Episode::create($episode);
        }

        return redirect()->route("dashboard")->with("message", "La formation a bien été mise en ligne");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::where('id', $id)->with('user')->with('episodes')->first();
        $watched = auth()->user()->episodes()->where('course_id', $id)->get();
        //dd($watched);
        return Inertia::render('Courses/Show', compact('course', 'watched'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::where('id', $id)->with('user')->with('episodes')->first();
        $this->authorize('edit', $course);
        return Inertia::render('Courses/Edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseRequest $request, $id)
    {
        $course = Course::where('id', $id)->with('user')->with('episodes')->first();

        $course->update($request->validated());

        $course->episodes()->delete();

        foreach($request->input('episodes') as $episode){
            $episode['course_id'] = $id;
            Episode::create($episode);
        }

        return redirect()->route("courses.edit", $id)->with("message", "La formation a bien été modifiée");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function toggleProgress(Request $req)
    {
        $id = $req->episodeId;
        $user = auth()->user();
        $user->episodes()->toggle($id);

        $courseId = Episode::where('id', $id)->get('course_id')[0]['course_id'];
        $wathcedEp = $user->episodes()->where('course_id', $courseId)->get();

        return $wathcedEp;
    }
}
