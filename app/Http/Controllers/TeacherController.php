<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{

    public function index()
    {
        $data['teachers'] = Teacher::orderBy('id', 'asc')->paginate(3);
        return view('teacher.index', $data);
    }

    public function fetchteacher()
    {
        $teachers = Teacher::paginate(3);
        return response()->json([
            'teachers' => $teachers,
        ]);
    }
        public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:10|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $teacher = new Teacher;
            $teacher->name = $request->input('name');
            $teacher->course = $request->input('course');
            $teacher->email = $request->input('email');
            $teacher->phone = $request->input('phone');
            $teacher->save();
            return response()->json([
                'status' => 200,
                'message' => 'Teacher Added Successfully.'
            ]);
        
    }
    }

    public function edit($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            return response()->json([
                'status' => 200,
                'teacher' => $teacher,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No teacher Found.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:10|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $teacher = Teacher::find($id);
            if ($teacher) {
                $teacher->name = $request->input('name');
                $teacher->course = $request->input('course');
                $teacher->email = $request->input('email');
                $teacher->phone = $request->input('phone');
                $teacher->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'teacher Updated Successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No teacher Found.'
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->delete();
            return response()->json([
                'status' => 200,
                'message' => 'teacher Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No teacher Found.'
            ]);
        }
    }

    public function search(Request $request)
    {
        $search['students'] = Teacher::where('name', 'LIKE', '%' . $request->search . "%")
            ->orderBy('name', 'asc')->paginate(4);
        $search['paginate'] = view('student.search-pagination', $search)->render();
        return response()->json($search);
        //return view('student.search-pagination',compact('students'))->render();
    }
}
