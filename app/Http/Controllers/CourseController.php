<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use App\Models\Rule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        // $data['courses'] = Course::orderBy('id', 'asc')->paginate(4);
        return view('course.index');
    }

    public function fetchcourse()
    {
        $data['courses'] = Course::orderBy('id', 'asc')->paginate(4);
        $data['message'] = "";
        return view('course.index', $data);
    }

    public function insertcourse()
    {
        $course = new Course();
        return view('course.insert-course', compact('course'));
    }

    // private function getCourse()
    // {
    //     $course['id'] = 0;
    //     $course['name'] = "";
    //     $course['code'] = "";
    //     $course['description'] = "";
    //     return $course;
    // }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'code' => 'required|max:191',
                'description' => 'required|max:191',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $course = new Course;
                $course->name = $request->input('name');
                $course->code = $request->input('code');
                $course->description = $request->input('description');
                $course->save();

                $courseId = $course->id;
                $shop = Auth::user();
                for ($i = 0; $i < count($request->product); $i++) {
                    $result = $shop->api()->rest('GET', '/admin/api/2022-04/products/' .
                        $request->product[$i] . '.json');
                    Rule::create([
                        'course_id' => $courseId,
                        'product_id' => $request->product[$i],
                        'title' => $result['body']['product']['title']
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Course Added Successfully.'
            ]);
        } catch (Exception $exp) {
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Please select atleast one product.'
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $courses = Course::where('id', $id)->with('rules')->get();

        foreach ($courses[0]['rules'] as $cou) {
            $dataId = $cou->product_id;
            $dataText = $cou->title;
            $dataArray[$dataId] = $dataText;
        }
        if ($courses) {
            return view('course.edit', compact('course', 'dataArray'))->render();
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No course Found.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'code' => 'required|max:191',
                'description' => 'required|max:191',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                $course = Course::find($id);
                if ($course) {
                    $course->name = $request->input('name');
                    $course->code = $request->input('code');
                    $course->description = $request->input('description');
                    $course->update();

                    $courseId = $course->id;
                    Rule::where('course_id', $courseId)->delete();
                    $shop = Auth::user();
                    for ($i = 0; $i < count($request->product); $i++) {
                        $result = $shop->api()->rest('GET', '/admin/api/2022-04/products/' .
                            $request->product[$i] . '.json');

                        Rule::create([
                            'course_id' => $courseId,
                            'product_id' => $request->product[$i],
                            'title' => $result['body']['product']['title']
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Course Updated Successfully.'
                    ]);
                }
            }
        } catch (Exception $exp) {
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Please select atleast one product'
            ]);
        }
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Course Deleted Successfully.'
            ]);
        }
    }

    public function search(Request $request)
    {
        $search['courses'] = Course::where('name', 'LIKE', '%' . $request->search . "%")
            ->orderBy('name', 'asc')->paginate(4);
        if ($search['courses']) {
            $search['paginate'] = view('course.search-pagination', $search)->render();
            return response()->json($search);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No course Found.'
            ]);
        }
    }

    public function getallproducts(Request $request)
    {
        $searchTitle = $request->get('term');
        if ($searchTitle == '') {
        }
        $fields = [
            'fields' => 'id,title',
            'limit' => 249
        ];
        $shop = Auth::user();
        $result = $shop->api()->rest('GET', '/admin/products.json', $fields);
        foreach ($result['body']['products'] as $product) {
            $dataArray[] = (object)[
                'text' => $product['title'],
                'id' => $product['id']
            ];
        }
        return response()->json($dataArray);
    }

    public function getproducthandle(Request $request)
    {
        $fields = [
            'fields' => 'id,handle',
            'limit' => 249
        ];
        // $shop = Auth::user();
        $user = User::where("name", $request->domain_name)->first();
        $result = $user->api()->rest('GET', '/admin/products.json', $fields);
        foreach ($result['body']['products'] as $product) {
            $dataArray[] = (object)[
                'id' => $product['id'],
                'handle' => $product['handle']
            ];
        }
        // return $dataArray;
        return response()->json($dataArray);
    }


    public function storeProduct(Request $request)
    {
        for ($i = 0; $i < count($request->product); $i++) {
            Rule::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product[$i],
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Product Added Successfully.'
        ]);
    }

    public function matchProduct()
    {
        $product = Rule::pluck('product_id');
        return response()->json([
            'status' => 404,
            'id' => $product
        ]);
    }

    public function getorderstatus(Request $request)
    {
        $user = User::where("name", $request->domain_name)->first();
        $orderId = $request->order_Id;
        $result = $user->api()->rest('POST', '/admin/api/2022-04/orders/' . $orderId . '/cancel.json');
        return $result;
    }

    public function orderreview(Request $request)
    {
        $review = new Review();
        $user = User::where("name", $request->domain_name)->first();
        $review->user_id = $user->id;
        $review->order_id = $request->order_Id;
        $review->review = $request->reviewval;
        $review->rating = $request->rating;
        $review->product_id = $request->productId;
        $review->variant_id = $request->variantId;
        $review->save();
        return response()->json([
            'status' => 200,
            'message' => 'Review Saved Successfully.'
        ]);
    }

    public function starRating(){
        $product_id = Review::pluck('product_id');
        
        foreach($product_id as $id){
            // $product_rating = $id->rating;  
            // $product_rating = Review::where('product_id', $product_id)->get(['rating']);  
        }
        return response()->json([
            'id' => $product_id,
            'rating' => $product_rating

        ]);
    }
}
