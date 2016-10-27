<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Course;
use App\interest;
use Sentinel;
use App\User;
use Carbon;
class JobController extends Controller
{
    public function addcourse(Request $request){
		$Course = new Course;
		$Course->user_id = Sentinel::getUser()->id;
		$Course->subject = $request->input('subject');
		$Course->credit = 3;
		$Course->length = $request->input('length');
		$Course->startdate = $request->input('date');
		$Course->place = $request->input('place');
		$Course->objective = $request->input('objective');
		$Course->time = $request->input('time');
		$Course->topic = $request->input('topic');
		$Course->inter = $request->input('inter');
		$Course->group = $request->input('group');
		$Course->verificationcode = mt_rand(100000, 999999);
		$Course->available = true;
		if($Course->save()){
			$request->session()->flash('status', 'เพิ่มคอร์สเรียบร้อยแล้ว');
			return redirect()->route('viewmycourse');
		}
		else{
			return 'error';
		}
	}
	public function viewmycourse(){
		$Courses = Course::where('user_id', Sentinel::getUser()->id)->get();
		foreach ($Courses as $Course) {
			$Course->date = Carbon::parse($Course->startdate)->day;
			$Course->month = Carbon::parse($Course->startdate)->format('M');
			$Course->month = Carbon::parse($Course->startdate)->format('M');
			$Course->timestring = Carbon::parse($Course->time)->format('H:i') . '-' . Carbon::parse($Course->time)->addHours($Course->length)->format('H:i');
		}
		
		return view('student-viewmycourse')->with('data', ['courses'=>$Courses]);
	}
	public function showcourse(){
		$Courses = Course::where('available', True)->get();
		foreach ($Courses as $Course) {
			$Course->date = Carbon::parse($Course->startdate)->day;
			$Course->month = Carbon::parse($Course->startdate)->format('M');
			$Course->timestring = Carbon::parse($Course->time)->format('H:i') . '-' . Carbon::parse($Course->time)->addHours($Course->length)->format('H:i');
		}
		return view('tutor-showcourse')->with('Courses', $Courses);
	}
	public function interest($id){
		if(!interest::where([['user_id', Sentinel::getUser()->id], ['course_id', $id]])->first()){
		$interest = new interest;
		$interest->user_id = Sentinel::getUser()->id;
		$interest->course_id = $id;
		$interest->save();
		return back();
		}
		else{
			return 'fuckoff';
		}
	}
	public function showcoursepage($id){
		$Course = Course::find($id);
		$Course->date = Carbon::parse($Course->startdate)->day;
		$Course->month = Carbon::parse($Course->startdate)->format('M');
		$Course->timestring = Carbon::parse($Course->time)->format('H:i') . '-' . Carbon::parse($Course->time)->addHours($Course->length)->format('H:i');
		$tutor = false;
		$haveinterest = true;
		if(Sentinel::check()){
			if(Sentinel::getUser()->hasAccess(['Interest',])){
				$tutor = true;
			}
			if(interest::where([['user_id', Sentinel::getUser()->id], ['course_id', $id]])->get()->isEmpty()){
				$haveinterest = false;
			}
			}
		
		return view('tutor-showcoursepage')->with('data', ['Course'=>$Course, 'tutor'=>$tutor, 'haveinterest'=>$haveinterest]);
	}
	public function uninterest($id){
		interest::where([['course_id', $id], ['user_id', Sentinel::getUser()->id],])->delete();
		return back();
	}
	public function manage($id){
		$Course = Course::find($id);
		$Course->date = Carbon::parse($Course->startdate)->day;
		$Course->month = Carbon::parse($Course->startdate)->format('M');
		$Course->timestring = Carbon::parse($Course->time)->format('H:i') . '-' . Carbon::parse($Course->time)->addHours($Course->length)->format('H:i');
		return view('student-managecourse')->with('data', ['course'=>$Course, 'haveinterest'=>$this->haveinterest($id), 'available'=>$Course->available ]);
	}
	public function viewprofile($id, $tutorid){
		$Course = Course::find($id);
		$User = User::find($tutorid);
		if($User->profile->status=='Tutor'){
			return view('student-managecourse-tutorprofile')->with('data', ['profile'=>$User->profile, 'course'=>$Course, 'id'=>$id, 'tutorid'=>$tutorid, 'available'=>$Course->available]);
		}
		else{
			abort(404);
		}
	}
	public function haveinterest($id){
		$haveinterest = false;
		if(interest::where([['user_id', Sentinel::getUser()->id], ['course_id', $id]])->get()->isEmpty() == false){
			$haveinterest = true;
		}
		return $haveinterest;
	}
	public function tutoranswered(){
		$Courses = Course::where([['tutor_id', Sentinel::getUser()->id], ['available', false], ['verified', false]])->get();
		foreach ($Courses as $Course) {
			$Course->date = Carbon::parse($Course->startdate)->day;
			$Course->month = Carbon::parse($Course->startdate)->format('M');
			$Course->timestring = Carbon::parse($Course->time)->format('H:i') . '-' . Carbon::parse($Course->time)->addHours($Course->length)->format('H:i');
		}
		return view('tutor-answered')->with('Courses', $Courses);
	}
	public function verify($id){
		//input course $ID to check verification code
		$Course = Course::find($id);
		return view('tutor-verify')->with('Course', $Course);
	}
	public function doverify(Request $request){
		$Course = Course::find($request->courseid);
		if($Course->verificationcode == $request->code){
			$Tutor = User::find(Sentinel::getUser()->id);
			$User = $Course->user;
			$User->credit->reservedcredit -= $Course->credit;
			$Tutor->credit->credit += $Course->credit;
			$request->session()->flash('status', true);
			$Course->verified = true;
			$Tutor->credit->save();
			$User->credit->save();
			$Course->save();
			return redirect()->route('verify', ['id'=>$request->courseid]);
		}
		else{
			$request->session()->flash('status', false);
			return redirect()->route('verify', ['id'=>$request->courseid]);
		}
	}
	public function selecttutor(Request $request, $id, $tutorid){
		$User = User::find(Sentinel::getUser()->id);
		$Course = Course::find($id);
		
			if($User->credit->credit >= $Course->credit){
				$Course->tutor_id = $tutorid;
				$Course->available = 0;
				$Course->save();
				$User->credit->reservedcredit += $Course->credit;
				$User->credit->credit = $User->credit->credit - $Course->credit;
				$User->credit->save();
				$request->session()->flash('status', 'เลือกติวเตอร์สำเร็จ');
				return redirect()->route('viewmycourse', ['id'=>$id]);
			}
			else{
				return "not enough credit";
			}
}
}