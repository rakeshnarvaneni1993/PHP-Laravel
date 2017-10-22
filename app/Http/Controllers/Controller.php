<?php

namespace App\Http\Controllers;
use App\Articles;
use App\BeadsExamples;
use App\Chapters;
use App\author;
use App\Courses;
use App\Beads;
use App\UpdateTopics;
use COursePut;
use App\UpdateBeads;
use DB;
use Hash;
use App\beads_problems;
use App\CourseData;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; /* User model */
use Illuminate\Database\Eloquent\ModelNotFoundException; 


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function formatValidationErrors(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $status = 422;
        return [
            "message" => $status . " error",
            "errors" => [
                "message" => $validator->getMessageBag()->first(),
                "info" => [$validator->getMessageBag()->keys()[0]],
            ],
            "status_code" => $status
        ];
    }
    public function edit($user_id)
{
    try{
        //Find the user object from model if it exists
        $user= User::findOrFail($user_id);
        //Redirect to edit user form with the user info found above.
        return view('add',['user'=>$user]);
    }
    catch(ModelNotFoundException $err){
        //redirect to your error page
    }
}
public function update(Request $request, $user_id)
{
    try{
        //Find the user object from model if it exists
        $user= User::findOrFail($user_id);
        //Set user object attributes
        //the $request index should match your form field ids!!!!!
        //you can exclude any field you want.
        $user->description = $request['idemployee'];
        $user->contactName = $request['contactName'];
        $user->contactPhone = $request['contactPhone'];
        $user->timeZone = $request['timeZone'];
        //Save/update user.
        $user->save();

        //redirect to somewhere?
    }
    catch(ModelNotFoundException $err){
        //Show error page
    }       
}      
}
class ExampleController extends Controller
{
    public function index()
    {
        $beads = Post::get();
        return response()->success(compact('beads'));
    
    }
}
class BeadsArticleController extends Controller
{
    public function index()
    {
        $beadsArticles = Articles::get();

        return response()->success(compact('beadsArticles'));
    
    }
}
class BeadsExampleController extends Controller
{
    public function index()
    {
        $beadsExamples = BeadsExamples::get();

        return response()->success(compact('beadsExamples'));
    
    }
}
class AuthorController extends Controller
{
    public function index()
    {
        $author = author::get();

        return response()->success(compact('author'));
    
    }
}
class CoursesController extends Controller
{
    public function index()
    {
        $courses = Courses::get();

        return response()->success(compact('courses'));
    
    }
}
class BeadsProblems extends Controller
{
    public function index()
    {
        $problems = beads_problems::get();

        return response()->success(compact('problems'));
    
    }
}
class ChaptersController extends Controller
{
    public function index()     
    {
        $chapters = DB::table('courses')
                         ->join('chapters', 'chapters.ID', '=', 'courses.ID')
                         ->select('courses.*', 'chapters.*')
                         ->get();

        return response()->success(compact('chapters'));
    
    }
}
class BeadsJoinController extends Controller
{
    public function index($id)
    {

        $problems = DB::select('select * from beads_problems');
        $examples = DB::select('select * from beads_examples');
        $articles = DB::select('select * from beads_articles');
        $actualBeads = DB::select('select * from beads');
        $beadFlash = DB::select('select * from beads_flash');
        $beadsummary = DB::select('select * from beads_summary');
        $beadDefinition = DB::select('select * from beads_definition');

        $beadsData = new \StdClass();
        foreach($problems as $problem){
            $beadId = $problem->Bead_ID;
            if(property_exists($beadsData,$beadId)){

                if($problem->Level == 'Easy'){
                    array_push($beadsData->$beadId->problems->Easy, $problem->Problem);
                }
                if($problem->Level == 'Medium'){
                    array_push($beadsData->$beadId->problems->Medium,$problem->Problem);
                }
                if($problem->Level == 'Hard'){
                    array_push($beadsData->$beadId->problems->Hard,$problem->Problem);
                }
                // $temp->problem = $problem->Problem;
                // $temp->Level = $problem->Level;
                // array_push($beadsData->$beadId->problems,$temp);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $problem->Bead_ID;
                    $temp = new \StdClass();
                    $temp->Easy = array();
                    $temp->Medium = array();
                    $temp->Hard = array();
                    if($problem->Level == 'Easy'){
                        array_push($temp->Easy,$problem->Problem);
                    }
                    if($problem->Level == 'Medium'){
                        array_push($temp->Medium,$problem->Problem);
                    }
                    if($problem->Level == 'Hard'){
                        array_push($temp->Hard,$problem->Problem);
                    }
                    $beadsData->$beadId->problems = $temp;

                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array();
                }
        }
        foreach($examples as $example){
            $beadId = $example->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->examples,$example->Example);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $example->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array($example->Example);
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array();
                }
        }
        foreach($articles as $article){
            $beadId = $article->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->articles,$article->Article);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $article->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array($article->Article);
                }
        }
        foreach($beadFlash as $flash){
            $beadId = $flash->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->flash,$flash->flash);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $flash->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->flash = array($flash->flash);
                }
        }
        foreach($beadsummary as $summary){
            $beadId = $summary->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->summary,$summary->summary);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $summary->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->summary = array($summary->summary);
                }
        }
        foreach($beadDefinition as $def){
            $beadId = $def->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->definition,$def->definition);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $summary->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->definition = array($def->definition);
                    $beadsData->$beadId->summary = array();
                }
        }
$data = $beadsData->$id;
                return response()->success(compact('data'));

    
    }
}

class CourseDataController extends Controller
{
    public function index()
    {
        $courses = DB::select('select * from courses');
        $chapters = DB::select('select * from chapters');
        $topics = DB::select('select * from topics');

        // $userCourseData = new \StdClass();

        // foreach($courses as $course){
        //     $id = $course->ID;
        //     if(property_exists($userCourseData,$id)){
        //         $userCourseData->$id = $course;
        //     }else{
        //             $userCourseData->$id = new \StdClass();
        //             $userCourseData->$id = $course;
        //     }
        // }
        // foreach($chapters as $chapter){
        //     $id = $chapter->course_id;
        //     $chapterId = $chapter->Chapter_ID;
        //     if(property_exists($userCourseData,$id)){
        //         if(property_exists($userCourseData->$id,'chapters')){
        //             $userCourseData->$id->chapters->$chapterId = $chapter;
        //             $userCourseData->$id->chapters->$chapterId->topics = new \StdClass();

        //         }
        //         else{
        //             $userCourseData->$id->chapters = new \StdClass();
        //             $userCourseData->$id->chapters->$chapterId = $chapter;
        //             $userCourseData->$id->chapters->$chapterId->topics = new \StdClass();


        //         }
                
        //     }else{
        //             $userCourseData->$id->$Chapter_ID = new \StdClass();
        //             $userCourseData->$id->chapters->$chapterId->topcis = new \StdClass();
        //             $userCourseData->$id->chapter = $chapter;
        //     }
        // }

        // foreach($topics as $topic){
        //     $courseId = $topic->course_id;
        //     $chapterId = $topic->Chapter_ID;
        //     $topicId = $topic->Bead_ID;
        //     $userCourseData->$courseId->chapters->$chapterId->topics->$topicId = $topic;
        // }
        $userCourseData = array();

        foreach($courses as $course){
            $id = $course->ID;
            $course->chapters = array();
                    // $userCourseData->$id = new \StdClass();
            array_push($userCourseData,$course);

            foreach($chapters as $chapter){   
                $chapter->topics = array();   
                foreach ($topics as $topic) {
                    if($topic->Chapter_ID == $chapter->Chapter_ID){
                        array_push($chapter->topics,$topic); 
                    }
                          }          
                array_push($userCourseData[0]->chapters,$chapter);
        };
        };
        // foreach($chapters as $chapter){
        //     $id = $chapter->course_id;
        //     $chapterId = $chapter->Chapter_ID;


            // if(property_exists($userCourseData,$id)){
            //     if(property_exists($userCourseData->$id,'chapters')){
            //         $userCourseData->$id->chapters->$chapterId = $chapter;
            //         $userCourseData->$id->chapters->$chapterId->topics = new \StdClass();

            //     }
            //     else{
            //         $userCourseData->$id->chapters = new \StdClass();
            //         $userCourseData->$id->chapters->$chapterId = $chapter;
            //         $userCourseData->$id->chapters->$chapterId->topics = new \StdClass();


            //     }
                
            // }else{
            //         $userCourseData->$id->$Chapter_ID = new \StdClass();
            //         $userCourseData->$id->chapters->$chapterId->topcis = new \StdClass();
            //         $userCourseData->$id->chapter = $chapter;
            // }
        // }

        // foreach($topics as $topic){
        //     $courseId = $topic->course_id;
        //     $chapterId = $topic->Chapter_ID;
        //     $topicId = $topic->Bead_ID;
        //     $userCourseData->$courseId->chapters->$chapterId->topics->$topicId = $topic;
        // }

        return response()->success(compact('userCourseData'));
    
    }
}   

class PutCourseDataController extends Controller
{
    public function index( Request $request )     
    {
        $exist = DB::table('chapters')->where(['Title'=>$request->input('Title')])->get();
         if(count($exist) > 0){
             echo 'Chapter Already Exists';
        } else {
            DB::table('chapters')->insert(
                ['Title' => $request->input('Title'),'course_id' => $request->input('course_id'),
                'Domain_ID' => $request->input('Domain_ID')]
    );
    echo DB::getPdo()->lastInsertId();

         }
    
    }
}
class UpdateBeadsController extends Controller
{
    public function index( Request $request )     
    {
        echo is_null($request->index);

        if(is_null($request->index)){
             DB::table('beads_'.$request->table) ->where('bead_id', $request->id)
            ->update([$request->table => $request->updatedData]);

            echo 'Added Successfully';
        }
        else{

            $id = DB::select('select * from beads_'.$request->table .'s '.'where bead_id = '. $request->id );
            $result = $id[$request->index];
            $actualId = $result -> ID;
            DB::table('beads_' . $request->table.'s') 
            ->where('bead_id', $request->id)
            ->where('ID',$actualId)
            ->update([$request->table => $request->updatedData]);

            echo 'Added Successfully';
        }
    }
}

class UpdateBeadsProblemsController extends Controller
{
    public function index( Request $request )     
    {

        $id = DB::select('select * from beads_problems where bead_id = '.$request->id );
            $result = $id[$request->index];
            $actualId = $result -> ID;
            DB::table('beads_' . $request->table.'s') 
            ->where('bead_id', $request->id)
            ->where('ID',$actualId)
            ->update([$request->table => $request->updatedData]);

            echo 'Added Successfully';
}

}
class UpdateTopicsController extends Controller
{
    public function index( Request $request )     
    {
        $exist = DB::table('topics')->where(['Title'=>$request->input('Title'),
        'Chapter_ID'=>$request->input('Chapter_ID')])->get();  
        if(count($exist) > 0){
            echo 'Chapter Already Exists';
        } else {
            DB::table('topics')->insert(
                ['Title' => $request->input('Title'),'course_id' => $request->input('course_id'),
                'Chapter_ID' => $request->input('Chapter_ID')]
    );
    echo 'Topic Added Successfully';
    
        }      

    
    }
}


class SignUpController extends Controller
{
    public function index( Request $request )     
    {

        try {
        $user = ['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)];

        User::create($user);

        echo 'Added Successfully';

    }  catch(\Illuminate\Database\QueryException $ex){ 
            echo "Account Already Exists" ;
}

        }
    
        } 
    


class KWBeadsController extends Controller
{
    public function index($id)
    {

        $problems = DB::select('select * from kw_beads_problems');
        $examples = DB::select('select * from kw_beads_examples');
        $articles = DB::select('select * from kw_beads_article');
        $actualBeads = DB::select('select * from kw_beads');
        $beadFlash = DB::select('select * from kw_beads_flash');
        $beadsummary = DB::select('select * from kw_beads_summary');
        $beadDefinition = DB::select('select * from kw_beads_definition');

        $beadsData = new \StdClass();
        foreach($problems as $problem){
            $beadId = $problem->Bead_ID;
            if(property_exists($beadsData,$beadId)){

                if($problem->Level == 'Easy'){
                    array_push($beadsData->$beadId->problems->Easy, $problem->Problem);
                }
                if($problem->Level == 'Medium'){
                    array_push($beadsData->$beadId->problems->Medium,$problem->Problem);
                }
                if($problem->Level == 'Hard'){
                    array_push($beadsData->$beadId->problems->Hard,$problem->Problem);
                }
                // $temp->problem = $problem->Problem;
                // $temp->Level = $problem->Level;
                // array_push($beadsData->$beadId->problems,$temp);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $problem->Bead_ID;
                    $temp = new \StdClass();
                    $temp->Easy = array();
                    $temp->Medium = array();
                    $temp->Hard = array();
                    if($problem->Level == 'Easy'){
                        array_push($temp->Easy,$problem->Problem);
                    }
                    if($problem->Level == 'Medium'){
                        array_push($temp->Medium,$problem->Problem);
                    }
                    if($problem->Level == 'Hard'){
                        array_push($temp->Hard,$problem->Problem);
                    }
                    $beadsData->$beadId->problems = $temp;

                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array();
                }
        }
        foreach($examples as $example){
            $beadId = $example->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->examples,$example->Example);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $example->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array($example->Example);
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array();
                }
        }
        foreach($articles as $article){
            $beadId = $article->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->articles,$article->Article);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $article->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->articles = array($article->Article);
                }
        }
        foreach($beadFlash as $flash){
            $beadId = $flash->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->flash,$flash->flash);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $flash->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->summary = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->flash = array($flash->flash);
                }
        }
        foreach($beadsummary as $summary){
            $beadId = $summary->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->summary,$summary->summary);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $summary->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->definition = array();
                    $beadsData->$beadId->summary = array($summary->summary);
                }
        }
        foreach($beadDefinition as $def){
            $beadId = $def->Bead_ID;
            if(property_exists($beadsData,$beadId)){
                array_push($beadsData->$beadId->definition,$def->definition);
            }
            else{
                    $beadsData->$beadId = new \StdClass();
                    $beadsData->$beadId->bead_id = $summary->Bead_ID;
                    $beadsData->$beadId->problems = new \StdClass();
                    $beadsData->$beadId->examples = array();
                    $beadsData->$beadId->articles = array();
                    $beadsData->$beadId->flash = array();
                    $beadsData->$beadId->definition = array($def->definition);
                    $beadsData->$beadId->summary = array();
                }
        }
$data = $beadsData->$id;
                return response()->success(compact('data'));

    
    }
}


class KWCourseDataController extends Controller
{
    public function index()
    {
        $courses = DB::select('select * from kw_courses');
        $chapters = DB::select('select * from kw_chapters');
        $topics = DB::select('select * from kw_topics');
        $userCourseData = array();

        foreach($courses as $course){
            $id = $course->ID;
            $course->chapters = array();
            array_push($userCourseData,$course);

            foreach($chapters as $chapter){   
                $chapter->topics = array();   
                foreach ($topics as $topic) {
                    if($topic->Chapter_ID == $chapter->Chapter_ID){
                        array_push($chapter->topics,$topic); 
                    }
                          }          
                array_push($userCourseData[0]->chapters,$chapter);
        };
        };
        return response()->success(compact('userCourseData'));
    
    }
}   